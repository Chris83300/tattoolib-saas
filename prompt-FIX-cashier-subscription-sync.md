# 🚨 FIX CRITIQUE — Synchronisation abonnements Stripe ↔ Laravel Cashier

## Problème racine
Les abonnements créés via Stripe n'atterrissent pas dans la table `subscriptions`
de Laravel Cashier. Résultat : toute la logique d'abonnement est cassée côté app
(annulation, vérification plan, trial, etc.) même si Stripe les voit correctement.

---

## PHASE 1 — Diagnostic avant toute modification

### 1.1 — Vérifier l'état réel en base
```bash
php artisan tinker
```
Exécuter dans tinker :
```php
// Trouver l'user du tattooer/piercer de test
$user = User::where('email', 'TON_EMAIL_TEST')->first();
dd([
    'user_id'            => $user->id,
    'stripe_id'          => $user->stripe_id,
    'subscriptions_db'   => $user->subscriptions()->get()->toArray(),
    'tattooer'           => $user->tattooer?->only(['is_subscribed','current_plan','trial_ends_at']),
    'piercer'            => $user->piercer?->only(['is_subscribed','current_plan','trial_ends_at']),
]);
```
→ Si `stripe_id` est null : Cashier n'a jamais créé le customer Stripe côté Laravel.
→ Si `stripe_id` existe mais `subscriptions_db` est vide : le webhook n'a pas été reçu/traité.

### 1.2 — Vérifier les logs webhook Stripe
```bash
# Chercher les appels webhook dans les logs Laravel
grep -i "stripe\|webhook\|subscription" storage/logs/laravel.log | tail -50

# Vérifier si la route webhook existe
php artisan route:list | grep stripe
```
→ La route doit exister : `POST stripe/webhook` → `Laravel\Cashier\Http\Controllers\WebhookController`

### 1.3 — Vérifier la config Cashier
```bash
php artisan tinker --execute="dd(config('cashier'));"
```
Vérifier :
- `cashier.model` pointe bien sur `App\Models\User`
- `cashier.webhook.secret` n'est pas null
- La table `subscriptions` existe : `php artisan migrate:status | grep subscription`

### 1.4 — Inspecter le controller de souscription
Ouvrir le controller qui gère `subscribe()` pour Tattooer/Piercer.
Identifier si il utilise `checkout()` ou `newSubscription()`.
**Afficher le code complet de la méthode `subscribe()` avant toute modification.**

---

## PHASE 2 — Corrections selon diagnostic

### CAS A — Le webhook n'est pas configuré (route manquante ou CSRF bloquant)

Vérifier dans `app/Http/Middleware/VerifyCsrfToken.php` :
```php
protected $except = [
    'stripe/*',  // ou 'stripe/webhook'
];
```
Si absent → ajouter l'exception CSRF pour la route webhook Stripe.

Vérifier dans `routes/web.php` ou `routes/api.php` que la route Cashier est publiée.
Si manquante, Cashier la publie automatiquement via son ServiceProvider — vérifier
que `Laravel\Cashier\CashierServiceProvider` est bien dans `bootstrap/providers.php`.

### CAS B — `checkout()` utilisé au lieu de `newSubscription()` (cause probable principale)

`checkout()` crée une Stripe Checkout Session qui redirige vers une page hébergée Stripe.
Cashier ne crée PAS l'entrée `subscriptions` automatiquement avec `checkout()` :
il dépend entièrement du webhook `checkout.session.completed` pour le faire.

Si le webhook ne fonctionne pas en local (Laragon), `checkout()` ne synchronisera jamais.

**Solution recommandée : passer à `newSubscription()` pour un flux synchrone fiable.**

Remplacer dans le controller de souscription :
```php
// ❌ AVANT — dépend du webhook pour créer l'entrée subscriptions
$checkout = $user->checkout($priceId, [
    'success_url' => route('...'),
    'cancel_url'  => route('...'),
    'mode' => 'subscription',
]);
return redirect($checkout->url);

// ✅ APRÈS — crée directement l'entrée subscriptions + redirige vers Stripe Billing Portal
// Option 1 : Checkout Session via Cashier (méthode correcte)
return $user->newSubscription('default', $priceId)
    ->checkout([
        'success_url' => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url'  => route('subscription.cancel'),
    ]);

// Option 2 : Si déjà un stripe_id et moyen de paiement enregistré
$user->newSubscription('default', $priceId)->create($paymentMethodId);
```

> ⚠️ Utiliser `newSubscription()->checkout()` (pas `$user->checkout()`) :
> Cashier gère alors lui-même la session ET la synchronisation post-paiement.

### CAS C — Webhook Stripe non reçu en local (Laragon)

En développement local, Stripe ne peut pas joindre `tattoolib-saas.test`.
Il faut Stripe CLI pour forwarder les événements :

```bash
# Installer Stripe CLI si pas fait
# https://stripe.com/docs/stripe-cli

# Forwarder les webhooks vers l'app locale
stripe listen --forward-to http://tattoolib-saas.test/stripe/webhook

# Dans un autre terminal, simuler un événement
stripe trigger customer.subscription.created
```

Le secret affiché par `stripe listen` doit être mis dans `.env` :
```env
STRIPE_WEBHOOK_SECRET=whsec_XXXX_LOCAL_DEPUIS_STRIPE_CLI
```
⚠️ Ce secret est différent du secret webhook du Dashboard Stripe (qui est pour la prod).

### CAS D — `stripe_id` null sur l'User (Cashier ne connaît pas le customer)

Si `$user->stripe_id` est null mais que Stripe a un customer pour cet email :
```php
// Dans tinker — retrouver le customer Stripe et le lier
$user = User::find(ID);
$stripeCustomerId = 'cus_XXXX'; // depuis le Dashboard Stripe
$user->stripe_id = $stripeCustomerId;
$user->save();

// Puis importer les abonnements existants depuis Stripe
$user->createOrGetStripeCustomer();
```

Ou laisser Cashier créer le customer automatiquement via `newSubscription()`.

---

## PHASE 3 — Corriger les relations activeSubscription

Une fois la table `subscriptions` correctement alimentée, simplifier les relations
dans `Tattooer.php` et `Piercer.php` :

```php
// ✅ Relation correcte via l'User (Billable est sur User)
public function activeSubscription()
{
    return $this->hasOneThrough(
        \Laravel\Cashier\Subscription::class,
        \App\Models\User::class,
        'id',       // FK sur users (clé locale tattooers.user_id → users.id)
        'user_id',  // FK sur subscriptions (subscriptions.user_id)
        'user_id',  // Clé locale sur tattooers
        'id'        // Clé locale sur users
    )->whereIn('stripe_status', ['active', 'trialing'])
     ->where(function ($q) {
         $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
     })
     ->latestOfMany();
}

// Helper court pour les vues
public function hasActiveSubscription(): bool
{
    return $this->user->subscribed('default');
}
```

---

## PHASE 4 — Vérifier et corriger les colonnes `is_subscribed` et `current_plan`

Ces colonnes doivent être mises à jour APRÈS confirmation du paiement, pas avant.
Vérifier le flow complet :

1. **`subscription.success` route** : Existe-t-elle ? Que fait-elle ?
   Elle doit appeler un service qui met à jour `is_subscribed=true` et `current_plan`.

2. **Webhook handler** : Si un `WebhookHandler` custom existe dans l'app,
   vérifier qu'il met à jour Tattooer/Piercer lors de `customer.subscription.created`.

3. **Ne jamais mettre `is_subscribed=true` à l'inscription** :
   Chercher dans tout le codebase :
   ```bash
   grep -r "is_subscribed.*true\|is_subscribed = 1" app/ --include="*.php"
   ```
   Toute occurrence dans un listener d'inscription, observer/factory est un bug → supprimer.

---

## PHASE 5 — Test end-to-end

Après corrections, tester le flux complet :

```bash
# Terminal 1 : forwarder les webhooks Stripe
stripe listen --forward-to http://tattoolib-saas.test/stripe/webhook

# Terminal 2 : tail des logs Laravel
tail -f storage/logs/laravel.log
```

Flux à valider :
1. Artiste clique "Activer le plan PRO"
2. Redirigé vers Stripe Checkout
3. Paiement avec carte test `4242 4242 4242 4242`
4. Retour sur `success_url`
5. **Vérifier** : `subscriptions` table a une nouvelle ligne
6. **Vérifier** : `tattooers.is_subscribed = true` et `current_plan = 'pro'`
7. Page "Mon plan" affiche PRO payant avec "Gérer paiement" / "Annuler"
8. Clic "Annuler" → fonctionne sans erreur

---

## ⚠️ Contraintes absolues
- Ne PAS toucher à la logique Studio (elle fonctionne)
- Ne PAS supprimer ni modifier les migrations existantes
- Garder la compatibilité avec `TrialService` et `inkpik:block-expired-trials`
- Le Billable reste sur `User`, jamais sur Tattooer/Piercer directement
- Si tu modifies `.env`, documenter les clés ajoutées/modifiées dans un commentaire

## 📋 Rapport attendu en fin d'exécution
- Cause racine identifiée (webhook / checkout / stripe_id manquant)
- Liste des fichiers modifiés
- Commande pour vérifier en tinker que tout est en ordre
- Instructions pour tester en local avec Stripe CLI si webhook était le problème
