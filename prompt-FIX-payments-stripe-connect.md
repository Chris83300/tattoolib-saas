# 🔧 AUDIT & FIX — Flux de paiement Stripe Connect + Vue payments

## Symptômes
1. Transactions affichent 0,00 € alors que des paiements existent
2. Test de paiement n'apparaît pas dans le dashboard Stripe
3. Vue `/tattooer/payments` ne reflète pas la réalité

---

## PHASE 1 — AUDIT COMPLET (lire avant toute modification)

### 1.1 — Lire la vue et le controller payments

```bash
# Trouver le controller qui alimente /tattooer/payments
php artisan route:list | grep "tattooer.*payment\|payment.*tattooer"
```

Lire intégralement :
- La vue `resources/views/tattooer/payments.blade.php`
- Le controller correspondant (méthode `payments()` ou `index()`)

Identifier :
- D'où vient `$totalEarned` / total gagné
- D'où vient `$pendingDeposits` / acomptes en attente
- D'où viennent les transactions récentes (table `bookings`, `payments`, `transactions` ?)
- Comment le montant est calculé (somme en DB, ou appel Stripe API ?)

### 1.2 — Inspecter les données en base

```bash
php artisan tinker
```

```php
$tattooer = \App\Models\Tattooer::find(1);

// Vérifier les transactions/paiements liés
dd([
    // Adapter selon les tables qui existent
    'bookings_paid'   => $tattooer->bookings()
                            ->where('status', 'paid')
                            ->sum('amount') ?? 'relation inexistante',
    'payments'        => \App\Models\Payment::where('tattooer_id', 1)
                            ->get(['amount', 'status', 'stripe_payment_intent_id'])
                            ->toArray(),
    'deposits'        => \App\Models\Deposit::where('tattooer_id', 1)
                            ->get(['amount', 'status'])
                            ->toArray(),
    'stripe_connect'  => [
        'id'      => $tattooer->stripe_connect_id,
        'status'  => $tattooer->stripe_connect_status,
        'charges' => $tattooer->stripe_connect_charges_enabled,
    ],
]);
```

> Adapter les modèles selon ce qui existe (`Payment`, `Deposit`, `Transaction`, `BookingPayment`...)

### 1.3 — Vérifier le flux de paiement existant

```bash
# Trouver les controllers qui créent des PaymentIntents
grep -r "PaymentIntent\|paymentIntent\|createPayment\|processPayment" \
  app/Http/Controllers/ --include="*.php" -l

# Trouver où transfer_data et application_fee_amount sont utilisés
grep -r "transfer_data\|application_fee\|destination" \
  app/ --include="*.php"
```

Lire chaque fichier trouvé et afficher le code complet des méthodes de paiement.

### 1.4 — Vérifier le PaymentIntent créé lors du test

```bash
php artisan tinker
```

```php
// Chercher le dernier PaymentIntent créé dans les logs ou en DB
// Récupérer son ID depuis la table payments/transactions

$stripe = new \Stripe\StripeClient(config('cashier.secret'));

// Remplacer par le vrai PI id trouvé en DB
$pi = $stripe->paymentIntents->retrieve('pi_XXXX');
dd([
    'status'                  => $pi->status,
    'amount'                  => $pi->amount,
    'transfer_data'           => $pi->transfer_data,
    'application_fee_amount'  => $pi->application_fee_amount,
    'on_behalf_of'            => $pi->on_behalf_of,
]);
```

Si `transfer_data` est **null** → le paiement ne part pas vers le compte Connect → c'est le bug principal.

### 1.5 — Vérifier les logs Stripe récents

```bash
# Dans le dashboard Stripe → Développeurs → Logs
# Filtrer sur les dernières 24h
# OU via CLI :
stripe events list --limit 10
```

Identifier si les PaymentIntents créés ont `transfer_data.destination` renseigné.

---

## PHASE 2 — CORRECTIONS

### CAS A — `transfer_data` absent dans le PaymentIntent (bug principal probable)

Dans le controller qui crée le paiement, vérifier que `transfer_data` est bien ajouté :

```php
// Récupérer le stripe_connect_id de l'artiste
$artist        = \App\Models\Tattooer::find($tattooerId);
$studio        = $artist->studioArtist?->studio ?? null;
$stripeService = app(\App\Services\StripeService::class);

$destinationAccountId = $studio
    ? ($studio->payment_mode === 'studio'
        ? $studio->stripe_connect_id
        : $artist->stripe_connect_id)
    : $artist->stripe_connect_id;

$feeAmount = $stripeService->calculateApplicationFee($amountCents, $artist, $studio);

// ✅ PaymentIntent AVEC transfer_data
$paymentIntentData = [
    'amount'               => $amountCents,
    'currency'             => 'eur',
    'payment_method_types' => ['card'],
    'transfer_data'        => [
        'destination' => $destinationAccountId,
    ],
    'metadata' => [
        'tattooer_id' => $artist->id,
        'booking_id'  => $bookingId ?? null,
    ],
];

if ($feeAmount > 0) {
    $paymentIntentData['application_fee_amount'] = $feeAmount;
}

$paymentIntent = \Stripe\PaymentIntent::create($paymentIntentData);
```

### CAS B — Le montant en base est 0,00 € (pas sauvegardé après paiement)

Vérifier dans le webhook handler ce qui se passe après `payment_intent.succeeded` :

```bash
grep -r "payment_intent.succeeded\|paymentIntentSucceeded\|handlePaymentIntentSucceeded" \
  app/ --include="*.php"
```

Si la méthode n'existe pas ou ne met pas à jour la table payments/bookings :

```php
// Dans le WebhookController (handler payment_intent.succeeded)
protected function handlePaymentIntentSucceeded(array $payload): Response
{
    $pi         = $payload['data']['object'];
    $tattooerId = $pi['metadata']['tattooer_id'] ?? null;
    $bookingId  = $pi['metadata']['booking_id'] ?? null;
    $amount     = $pi['amount'] / 100; // centimes → euros

    if ($bookingId) {
        \App\Models\Booking::where('id', $bookingId)->update([
            'payment_status'             => 'paid',
            'amount_paid'                => $amount,
            'stripe_payment_intent_id'   => $pi['id'],
            'paid_at'                    => now(),
        ]);
    }

    // Mettre à jour la table payments/transactions si elle existe
    // Adapter selon le schéma exact

    return $this->successMethod();
}
```

### CAS C — La vue calcule les totaux depuis la mauvaise source

Si le total gagné dans la vue vient d'un `sum()` sur une colonne qui n'est pas mise à jour :

```php
// Dans le controller payments() — corriger le calcul
$totalEarned = $tattooer->bookings()
    ->where('payment_status', 'paid')
    ->sum('amount_paid'); // adapter le nom de colonne exact

$thisMonth = $tattooer->bookings()
    ->where('payment_status', 'paid')
    ->whereMonth('paid_at', now()->month)
    ->whereYear('paid_at', now()->year)
    ->sum('amount_paid');

$pendingDeposits = $tattooer->bookings()
    ->where('payment_status', 'deposit_paid')
    ->sum('deposit_amount'); // adapter
```

### CAS D — Transactions récentes avec montant 0,00 €

Les transactions affichent 0,00 € car le montant n'est pas sauvegardé au bon endroit.
Identifier la colonne utilisée dans la vue pour afficher le montant, et vérifier
qu'elle est bien renseignée après paiement.

Si la vue affiche `$booking->amount` mais que le montant est dans `$booking->amount_paid` :
corriger la vue pour utiliser la bonne colonne.

---

## PHASE 3 — TESTER EN LOCAL AVEC STRIPE CLI

```bash
# Terminal 1 — écouter les webhooks
stripe listen --forward-to http://tattoolib-saas.test/webhooks/stripe

# Terminal 2 — simuler un paiement réussi
stripe trigger payment_intent.succeeded

# Vérifier que le handler est bien appelé dans les logs Laravel
tail -f storage/logs/laravel.log | grep -i "payment\|stripe\|webhook"
```

Après le trigger, vérifier en tinker que les montants sont mis à jour en base.

---

## PHASE 4 — SYNCHRONISATION MANUELLE (données existantes)

Pour les transactions existantes qui affichent 0,00 €, récupérer les montants
depuis Stripe et mettre à jour en base :

```bash
php artisan tinker
```

```php
$stripe   = new \Stripe\StripeClient(config('cashier.secret'));

// Adapter selon la table et les colonnes exactes
$payments = \App\Models\Payment::whereNotNull('stripe_payment_intent_id')
    ->where('amount', 0)
    ->get();

foreach ($payments as $payment) {
    try {
        $pi = $stripe->paymentIntents->retrieve($payment->stripe_payment_intent_id);
        if ($pi->status === 'succeeded') {
            $payment->update(['amount' => $pi->amount / 100]);
            echo "Updated payment {$payment->id}: " . ($pi->amount / 100) . "€\n";
        }
    } catch (\Exception $e) {
        echo "Error on {$payment->id}: " . $e->getMessage() . "\n";
    }
}
```

---

## 📋 Rapport attendu

1. **Cause exacte** des montants à 0,00 € (colonne mal renseignée / webhook manquant)
2. **Cause exacte** du paiement absent dans Stripe (transfer_data manquant ou PI mal créé)
3. **Liste des fichiers modifiés**
4. **Résultat tinker** confirmant les montants corrects après fix
5. Confirmation que `stripe trigger payment_intent.succeeded` met bien à jour la vue

## ⚠️ Contraintes
- Ne pas modifier les migrations
- Adapter tous les noms de colonnes/relations aux noms EXACTS trouvés dans l'audit
- Ne pas toucher à la logique Studio ni au système trial
- Les montants restent en euros en base, centimes uniquement dans les PaymentIntents Stripe
