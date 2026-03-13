# 🔧 FIX — Migrer vers Direct Charges (frais Stripe à la charge de l'artiste)

## Contexte et objectif
Actuellement le setup utilise **Destination Charges** :
- Les frais Stripe (~1,5% + 0,25€) sont prélevés sur la PLATEFORME ❌
- La plateforme transfère ensuite le montant brut à l'artiste

Objectif : passer en **Direct Charges** :
- Les frais Stripe sont prélevés sur le compte Connect de l'ARTISTE ✅
- La plateforme prélève uniquement sa commission (7% STARTER, 0% PRO)
- La plateforme ne paie AUCUN frais de transaction

## Différence technique

```
AVANT — Destination Charges :
Client paie 200€ → Plateforme reçoit 200€ - 6,75€ frais = 193,25€
Plateforme transfère 200€ à l'artiste → Plateforme perd 6,75€ ❌

APRÈS — Direct Charges :
Client paie 200€ → Compte Connect artiste reçoit 200€ - 6,75€ frais = 193,25€
Plateforme prélève 14€ commission (7%) via application_fee ✅
Net artiste : 193,25€ - 14€ = 179,25€
Plateforme gagne : 14€ commission, 0€ de frais ✅
```

---

## PHASE 1 — AUDIT

### 1.1 — Localiser tous les PaymentIntents créés

```bash
grep -rn "PaymentIntent::create\|paymentIntents->create" \
  app/ --include="*.php"
```

Lire chaque fichier et afficher le code complet de chaque création.

### 1.2 — Vérifier si on_behalf_of est déjà utilisé

```bash
grep -rn "on_behalf_of\|stripe_account\|stripeAccount" \
  app/ --include="*.php"
```

---

## PHASE 2 — CORRECTION DES PAYMENTINTENTS

Pour chaque endroit où un PaymentIntent est créé, appliquer ce changement :

```php
// ❌ AVANT — Destination Charges
\Stripe\PaymentIntent::create([
    'amount'        => $amountCents,
    'currency'      => 'eur',
    'transfer_data' => ['destination' => $stripeConnectId],
    'application_fee_amount' => $feeAmount,
]);

// ✅ APRÈS — Direct Charges
\Stripe\PaymentIntent::create([
    'amount'                 => $amountCents,
    'currency'               => 'eur',
    'on_behalf_of'           => $stripeConnectId, // paiement au nom de l'artiste
    'application_fee_amount' => $feeAmount,        // commission plateforme (0 si PRO)
    'transfer_data'          => [
        'destination' => $stripeConnectId,         // obligatoire avec on_behalf_of
    ],
    'metadata' => [
        'booking_id'  => $booking->id ?? null,
        'artist_id'   => $artist->id,
        'artist_type' => get_class($artist),
        'plan'        => $artist->current_plan,
    ],
]);
```

> ⚠️ `on_behalf_of` + `transfer_data.destination` sur le même compte = Direct Charges.
> Les frais Stripe sont alors débités sur le compte Connect, pas sur la plateforme.

### Cas Checkout Session (si utilisé)

```php
// ❌ AVANT
\Stripe\Checkout\Session::create([
    'payment_intent_data' => [
        'transfer_data' => ['destination' => $stripeConnectId],
        'application_fee_amount' => $feeAmount,
    ],
]);

// ✅ APRÈS
\Stripe\Checkout\Session::create([
    'payment_intent_data' => [
        'on_behalf_of'           => $stripeConnectId,
        'transfer_data'          => ['destination' => $stripeConnectId],
        'application_fee_amount' => $feeAmount,
    ],
]);
```

---

## PHASE 3 — VÉRIFIER calculateApplicationFee

S'assurer que la commission est correcte dans `StripeService::calculateApplicationFee()` :

```php
public function calculateApplicationFee(int $amountCents, $artist, ?Studio $studio = null): int
{
    // Artiste de studio
    if ($studio) {
        $rate = $studio->artist_commission_rate;
        if (is_null($rate) || $rate <= 0) return 0;
        return (int) round($amountCents * ($rate / 100));
    }

    // Indépendant STARTER → 7% pour la plateforme
    if (method_exists($artist, 'isStarter') && $artist->isStarter()) {
        return (int) round($amountCents * 0.07);
    }

    // PRO ou trial → 0% (plateforme ne prend rien sur les transactions)
    return 0;
}
```

---

## PHASE 4 — TEST

```bash
# Terminal 1
stripe listen --forward-to http://tattoolib-saas.test/webhooks/stripe
```

Créer un nouveau paiement test, puis vérifier en tinker :

```bash
php artisan tinker
```

```php
$stripe = new \Stripe\StripeClient(config('cashier.secret'));
$pis    = $stripe->paymentIntents->all(['limit' => 3]);

foreach ($pis->data as $pi) {
    $amount  = $pi->amount / 100;
    $fee     = ($pi->application_fee_amount ?? 0) / 100;

    echo "\n{$pi->id}";
    echo "\n  Montant         : {$amount}€";
    echo "\n  on_behalf_of    : " . ($pi->on_behalf_of ?? '❌ ABSENT');
    echo "\n  Destination     : " . ($pi->transfer_data?->destination ?? '❌ ABSENT');
    echo "\n  Commission      : {$fee}€";
    echo "\n  Status          : {$pi->status}";
}
```

### Résultat attendu après fix

Dans le dashboard Stripe **PLATEFORME** :
```
Frais perçus  : 14€  (7% sur 200€ — plan STARTER)
Frais Stripe  : 0€   ← la plateforme ne paie plus rien ✅
```

Dans le dashboard Stripe **COMPTE CONNECT ARTISTE** :
```
Paiement reçu : 200€
Frais Stripe  : -6,75€  ← l'artiste paie ses frais ✅
Commission    : -14€    ← prélevée par la plateforme
Net artiste   : 179,25€
```

---

## ⚠️ Contraintes
- Ne modifier QUE les créations de PaymentIntent / Checkout Session
- Ne pas toucher aux migrations, au système trial, aux abonnements Cashier
- Si `$stripeConnectId` est null (artiste sans Connect configuré) :
  ne pas ajouter `on_behalf_of` ni `transfer_data` — laisser le paiement
  aller sur la plateforme en fallback (cas rare, artiste non onboardé)
- Ne pas modifier la logique Studio existante, juste appliquer le même
  pattern `on_behalf_of` quand `$destinationAccountId` est le compte studio

## 📋 Rapport attendu
1. Liste des fichiers modifiés
2. Résultat tinker confirmant `on_behalf_of` présent sur le nouveau PI
3. Confirmation que les frais Stripe n'apparaissent plus dans le relevé plateforme
