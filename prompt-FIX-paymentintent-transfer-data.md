# 🔧 FIX CRITIQUE — PaymentIntents sans transfer_data (argent non routé vers compte Connect)

## Contexte
Les transactions apparaissent dans la BDD Laravel mais le solde du compte
Stripe Connect de l'artiste reste à 0,00 €. Cause : les PaymentIntents sont
créés sans `transfer_data`, l'argent va sur le compte plateforme MWD Creative Sense
au lieu du compte Connect de l'artiste (acct_1TA87XIWeEzH43h2).

Les transactions passées ne peuvent pas être corrigées rétroactivement.
Objectif : corriger le flux pour TOUS les futurs paiements.

---

## PHASE 1 — LOCALISER TOUS LES ENDROITS OÙ UN PAYMENTINTENT EST CRÉÉ

```bash
grep -rn "PaymentIntent::create\|paymentIntents->create\|createPaymentIntent" \
  app/ --include="*.php"

grep -rn "checkout\|newSubscription\|charge" \
  app/Http/Controllers/ --include="*.php" | grep -v "vendor"
```

Lire CHAQUE fichier trouvé et afficher le code complet de la création du PaymentIntent.
Il peut y en avoir plusieurs :
- Controller paiement acompte (deposit)
- Controller paiement solde restant (balance/final payment)
- Controller paiement chat/direct
- Tout autre point de création de charge Stripe

---

## PHASE 2 — CORRIGER CHAQUE PAYMENTINTENT

Pour CHAQUE endroit trouvé en Phase 1, appliquer ce pattern :

```php
// Récupérer l'artiste concerné par le paiement
$artist = $booking->tattooer ?? $booking->piercer ?? null;

// Déterminer le compte destinataire
$destinationAccountId = null;
if ($artist) {
    $studio = $artist->studioArtist?->studio ?? null;

    if ($studio && $studio->payment_mode === 'studio') {
        $destinationAccountId = $studio->stripe_connect_id;
    } else {
        $destinationAccountId = $artist->stripe_connect_id;
    }
}

// Calculer l'application fee
$feeAmount = 0;
if ($artist && $destinationAccountId) {
    $studio    = $artist->studioArtist?->studio ?? null;
    $feeAmount = app(\App\Services\StripeService::class)
        ->calculateApplicationFee($amountCents, $artist, $studio);
}

// Construire le PaymentIntent
$paymentIntentData = [
    'amount'               => $amountCents,
    'currency'             => 'eur',
    'payment_method_types' => ['card'],
    'metadata'             => [
        'booking_id'   => $booking->id ?? null,
        'artist_id'    => $artist?->id,
        'artist_type'  => $artist ? get_class($artist) : null,
    ],
];

// Ajouter transfer_data UNIQUEMENT si un compte Connect actif existe
if ($destinationAccountId) {
    $paymentIntentData['transfer_data'] = [
        'destination' => $destinationAccountId,
    ];
    // Ajouter la commission plateforme si applicable
    if ($feeAmount > 0) {
        $paymentIntentData['application_fee_amount'] = $feeAmount;
    }
}

$paymentIntent = \Stripe\PaymentIntent::create($paymentIntentData);
```

> ⚠️ Ne pas ajouter `transfer_data` si `$destinationAccountId` est null
> (compte Connect pas encore configuré) — le paiement part sur la plateforme
> dans ce cas, ce qui est acceptable en fallback.

---

## PHASE 3 — VÉRIFIER QUE calculateApplicationFee EXISTE ET EST CORRECTE

```bash
grep -n "calculateApplicationFee\|getCommissionRate\|commission" \
  app/Services/StripeService.php
```

La méthode doit retourner un entier (centimes) selon cette logique :

```php
public function calculateApplicationFee(int $amountCents, $artist, ?Studio $studio = null): int
{
    // Artiste de studio
    if ($studio) {
        $rate = $studio->artist_commission_rate;
        if (is_null($rate) || $rate <= 0) return 0;
        return (int) round($amountCents * ($rate / 100));
    }

    // Artiste indépendant STARTER → 7%
    if (method_exists($artist, 'isStarter') && $artist->isStarter()) {
        return (int) round($amountCents * 0.07);
    }

    // PRO ou trial → 0%
    return 0;
}
```

Si la méthode existe déjà avec une logique différente, la préserver et adapter
l'appel en Phase 2 pour utiliser les bons paramètres.

---

## PHASE 4 — TEST AVEC UN NOUVEAU PAIEMENT

Après correction, créer un nouveau paiement test depuis l'interface et vérifier :

```bash
# Terminal 1 — écouter les webhooks
stripe listen --forward-to http://tattoolib-saas.test/webhooks/stripe

# Terminal 2 — surveiller les logs
tail -f storage/logs/laravel.log | grep -i "stripe\|payment\|transfer"
```

Puis dans le dashboard Stripe → Développeurs → Logs, vérifier que le nouveau
PaymentIntent a bien :
- `transfer_data.destination` = `acct_1TA87XIWeEzH43h2`
- `application_fee_amount` = montant × 7% (si STARTER) ou 0 (si PRO)
- `status` = `succeeded`

Et dans le compte Connect de l'artiste : le solde doit augmenter.

---

## PHASE 5 — VÉRIFICATION RAPIDE EN TINKER

```bash
php artisan tinker
```

```php
// Récupérer le dernier PaymentIntent créé après le fix
$stripe = new \Stripe\StripeClient(config('cashier.secret'));

// Lister les 5 derniers PaymentIntents de la plateforme
$pis = $stripe->paymentIntents->all(['limit' => 5]);
foreach ($pis->data as $pi) {
    echo "\n--- " . $pi->id . " ---";
    echo "\nStatus: " . $pi->status;
    echo "\nAmount: " . ($pi->amount / 100) . "€";
    echo "\nTransfer destination: " . ($pi->transfer_data?->destination ?? 'AUCUN ⚠️');
    echo "\nApplication fee: " . (($pi->application_fee_amount ?? 0) / 100) . "€";
}
```

→ Les nouveaux PIs doivent avoir `transfer_data.destination = acct_1TA87XIWeEzH43h2`
→ Les anciens auront `AUCUN` (normal, ils ne peuvent pas être rétroactivement corrigés)

---

## ⚠️ Contraintes
- Ne corriger QUE les créations de PaymentIntent, pas le reste
- Ne pas modifier les migrations
- Ne pas toucher à la logique trial/subscription
- Si un PaymentIntent est créé via Checkout Session Stripe (redirect), la logique
  `transfer_data` doit être ajoutée dans les `payment_intent_data` de la session :
  ```php
  \Stripe\Checkout\Session::create([
      // ...
      'payment_intent_data' => [
          'transfer_data'           => ['destination' => $destinationAccountId],
          'application_fee_amount'  => $feeAmount,
      ],
  ]);
  ```

## 📋 Rapport attendu
1. Liste de TOUS les endroits où un PaymentIntent / Charge est créé
2. Confirmation que transfer_data est ajouté à chacun
3. Résultat tinker Phase 5 montrant destination correcte sur un nouveau PI
4. Confirmation du solde Connect mis à jour après un paiement test
