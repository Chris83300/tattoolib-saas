# 🔧 PROMPT CASCADE — CORRECTION SYSTÈME DE PAIEMENT INK&PIK

## CONTEXTE

Tu travailles sur Ink&Pik, une plateforme SaaS Laravel 12 / Livewire 3 / Stripe Connect pour les professionnels du body art en France.

Un audit complet du système de paiement d'acompte a révélé **5 problèmes critiques** à corriger. Le paiement Stripe fonctionne (le statut passe bien à `deposit_paid`), mais tout ce qui suit le paiement est cassé ou inexistant.

**Stack :** Laravel 12, Livewire 3, TailwindCSS v4, Alpine.js, Stripe Connect, Pest PHP (TDD), Spatie Media Library.

**Booking Request #27 (test actuel) :**
- Status: `deposit_paid` ✅
- Deposit paid at: `2026-02-07 17:56:52` ✅
- Deposit amount: `150.00€` ✅
- Deposit deadline: `NULL` ❌
- Appointment datetime: `NULL` ❌
- Appointment: **AUCUN** ❌
- Accounting Transactions: **TABLE INEXISTANTE** ❌
- Reçu de paiement (média): **AUCUN** ❌
- Webhook Stripe: **NON CONFIGURÉ** ❌
- Conversation deadlines (deposit_deadline_at, expires_at, chat_closes_at): **TOUS NULL** ❌

---

## PROBLÈMES À CORRIGER (PAR ORDRE DE PRIORITÉ)

### 1. 🔴 Créer le modèle AccountingTransaction + migration

Créer la migration `create_accounting_transactions_table` avec ces champs :

```php
Schema::create('accounting_transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('booking_request_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // le payeur (client)
    $table->string('type'); // 'deposit', 'final_payment', 'refund', 'commission'
    $table->decimal('amount', 10, 2);
    $table->string('currency', 3)->default('eur');
    $table->string('status'); // 'pending', 'completed', 'failed', 'refunded'
    $table->string('payment_method')->nullable(); // 'stripe', 'cash', etc.
    $table->string('stripe_payment_intent_id')->nullable();
    $table->string('stripe_session_id')->nullable();
    $table->json('metadata')->nullable();
    $table->timestamps();

    $table->index(['booking_request_id', 'type']);
    $table->index('stripe_payment_intent_id');
});
```

Créer le modèle `App\Models\AccountingTransaction` avec :
- Relations : `bookingRequest()`, `user()`
- Fillable appropriés
- Casts : `amount` → `decimal:2`, `metadata` → `array`
- Scopes : `scopeDeposits()`, `scopeCompleted()`, `scopeFailed()`

### 2. 🔴 Compléter la méthode `success()` du DepositController

Après le paiement Stripe réussi, la méthode `success()` doit **en une seule transaction DB** :

```php
// Dans DepositController::success()
DB::transaction(function () use ($bookingRequest, $session) {
    // 1. Marquer le paiement (déjà fait)
    $bookingRequest->update([
        'status' => 'deposit_paid',
        'deposit_paid_at' => now(),
    ]);

    // 2. Créer la transaction comptable
    AccountingTransaction::create([
        'booking_request_id' => $bookingRequest->id,
        'user_id' => $bookingRequest->client->user_id,
        'type' => 'deposit',
        'amount' => $bookingRequest->total_deposit_amount,
        'currency' => 'eur',
        'status' => 'completed',
        'payment_method' => 'stripe',
        'stripe_session_id' => $session->id,
        'stripe_payment_intent_id' => $session->payment_intent,
    ]);

    // 3. Créer l'appointment SI appointment_datetime est défini
    if ($bookingRequest->appointment_datetime) {
        $bookingRequest->createAppointment();
    }

    // 4. Synchroniser les deadlines de la conversation
    if ($conversation = $bookingRequest->conversation) {
        $conversation->update([
            'status' => 'active',
            'deposit_deadline_at' => null, // plus besoin, déjà payé
            'expires_at' => $bookingRequest->appointment_datetime
                ? $bookingRequest->appointment_datetime->addDays(1)
                : now()->addMonths(6),
        ]);
    }
});
```

**⚠️ ATTENTION :** L'appointment_datetime peut être NULL à ce stade si le tattooer n'a pas encore fixé la date. C'est normal dans le flow Ink&Pik — la date peut être définie après le paiement d'acompte. Ne force PAS la création d'appointment si la date n'existe pas.

### 3. 🔴 Créer le StripeWebhookController

Créer `App\Http\Controllers\StripeWebhookController` pour gérer les événements Stripe :

```
Route : POST /webhooks/stripe (sans middleware CSRF, avec vérification signature Stripe)
```

Événements à gérer :
- `checkout.session.completed` → Confirmer le paiement (idempotent, au cas où success() échoue)
- `payment_intent.payment_failed` → Marquer la transaction comme échouée
- `charge.refunded` → Créer une transaction de type 'refund'

**IMPORTANT :**
- Exclure cette route du middleware `VerifyCsrfToken`
- Valider la signature webhook avec `STRIPE_WEBHOOK_SECRET`
- Être **idempotent** : si le paiement est déjà marqué, ne rien faire
- Logger chaque événement reçu

### 4. 🟡 Synchroniser les deadlines BookingRequest ↔ Conversation

Quand le tattooer envoie la demande d'acompte (méthode `requestDeposit()` ou équivalent) :

```php
// Mettre à jour BookingRequest
$bookingRequest->update([
    'deposit_deadline' => now()->addDays(3), // ou la valeur configurée
]);

// Synchroniser avec la Conversation
$bookingRequest->conversation->update([
    'deposit_deadline_at' => $bookingRequest->deposit_deadline,
]);
```

Après paiement, reset le deadline :
```php
$bookingRequest->update(['deposit_deadline' => null]);
$bookingRequest->conversation->update(['deposit_deadline_at' => null]);
```

### 5. 🟡 Sauvegarder le reçu de paiement (Stripe receipt)

Dans `success()`, après confirmation du paiement, récupérer et stocker le reçu Stripe :

```php
// Récupérer l'URL du reçu depuis Stripe
$paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
$charge = $paymentIntent->latest_charge;
if ($charge) {
    $chargeObj = \Stripe\Charge::retrieve($charge);
    $receiptUrl = $chargeObj->receipt_url;

    // Stocker dans metadata de la transaction
    $transaction->update([
        'metadata' => array_merge($transaction->metadata ?? [], [
            'receipt_url' => $receiptUrl,
        ]),
    ]);
}
```

**Note :** En mode test Stripe, le `receipt_url` peut être null. Gérer ce cas gracieusement.

---

## CONTRAINTES TECHNIQUES

1. **TDD** : Écrire les tests Pest AVANT ou EN MÊME TEMPS que le code. Minimum :
   - `test('accounting transaction is created after deposit payment')`
   - `test('appointment is created after deposit payment when datetime is set')`
   - `test('appointment is not created when datetime is null')`
   - `test('stripe webhook confirms payment idempotently')`
   - `test('conversation deadlines are synchronized')`
   - `test('double payment is prevented')`

2. **Non-destructif** : Ne modifie PAS les migrations existantes. Crée de nouvelles migrations pour les ajouts.

3. **DB Transactions** : Toute la logique post-paiement dans `DB::transaction()`.

4. **Idempotence** : Le webhook ET success() doivent pouvoir être appelés plusieurs fois sans créer de doublons.

5. **Relations polymorphiques** : Le booking_request utilise `bookable_type` / `bookable_id` (peut être Tattooer ou StudioArtist). En tenir compte.

6. **Convention de code** : Suivre les conventions Laravel, pas de logique métier dans les contrôleurs — utiliser des Services ou Actions si la logique devient complexe.

---

## FICHIERS À CRÉER

- [ ] `database/migrations/xxxx_create_accounting_transactions_table.php`
- [ ] `app/Models/AccountingTransaction.php`
- [ ] `app/Http/Controllers/StripeWebhookController.php`
- [ ] `tests/Feature/Payment/DepositPaymentTest.php`
- [ ] `tests/Feature/Payment/StripeWebhookTest.php`
- [ ] `tests/Feature/Payment/AccountingTransactionTest.php`

## FICHIERS À MODIFIER

- [ ] `app/Http/Controllers/DepositController.php` → Compléter `success()`
- [ ] `routes/web.php` → Ajouter route webhook
- [ ] `app/Http/Middleware/VerifyCsrfToken.php` (ou `bootstrap/app.php` si Laravel 12) → Exclure webhook du CSRF
- [ ] `app/Models/BookingRequest.php` → Ajouter relation `accountingTransactions()`
- [ ] `app/Models/Conversation.php` → Vérifier synchronisation deadlines

---

## ORDRE D'EXÉCUTION

1. **Migration + Modèle** AccountingTransaction
2. **Modifier** DepositController::success() avec DB::transaction
3. **Créer** StripeWebhookController + route
4. **Synchroniser** les deadlines
5. **Tests Pest** pour tout valider
6. **Lancer** `php artisan migrate` puis `php artisan test`

---

## VALIDATION FINALE

Après correction, relancer le script d'audit `audit_payment_system.php` et vérifier que :
- ✅ AccountingTransaction existe avec le bon montant
- ✅ Appointment créé (si datetime défini)
- ✅ Conversation deadlines cohérents
- ✅ Webhook Stripe fonctionnel
- ✅ Tous les tests passent
- ✅ Pas de régressions sur les 253 tests existants
