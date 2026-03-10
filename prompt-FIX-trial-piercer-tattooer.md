# 🚨 FIX CRITIQUE — Trial Piercer & Tattooer : 4 bugs restants

## Contexte
Le prompt précédent a partiellement corrigé le trial. Il reste 4 bugs distincts
identifiés par observation directe des pages. Ne touche PAS à la logique Studio.

---

## 🔍 Bugs observés (à corriger exactement)

### BUG 1 — Piercer `subscription-plans` : cartes comparatives affichées pendant le trial
**Page** : `/pierceur/subscription-plans`
**Symptôme** : Le bloc "PRO Essai gratuit — 13 jours restants" s'affiche correctement
MAIS les cartes STARTER et PRO avec boutons "Activer" s'affichent aussi en dessous.
**Attendu** : Pendant le trial → UNIQUEMENT le bloc trial, ZERO carte comparative.

### BUG 2 — Piercer `profil` : badge plan incorrect
**Page** : `/pierceur/profil`
**Symptôme** : Affiche "🟡 Plan STARTER" alors que le piercer est en trial PRO.
**Attendu** : Afficher "⭐ Plan PRO" (ou "🎁 Essai PRO") pendant le trial.

### BUG 3 — Tattooer `subscription-plans` : trial traité comme abonnement PRO payant
**Page** : `/tattooer/subscription-plans`
**Symptôme** : Affiche "PRO 29,99€/mois · Commission 0%" avec "💳 Gérer le paiement"
et "Annuler l'abonnement" → comme si c'était un PRO payant actif.
Le tattooer est en trial (is_subscribed=false, trial_ends_at futur), PAS abonné.
**Attendu** : Afficher le bloc trial (comme le Piercer : "Essai gratuit — X jours restants").
Pas de bouton "Gérer paiement" ni "Annuler" pendant le trial.

### BUG 4 — Tattooer `dashboard` : bannière trial absente
**Page** : `/tattooer/dashboard`
**Symptôme** : Aucune bannière trial. Le Piercer affiche correctement
"🎁 Essai gratuit — 13 jours restants / Activer mon abonnement".
**Attendu** : Même bannière trial que le Piercer sur le dashboard tattooer.

---

## 🔧 Instructions de correction

### Étape 1 — Diagnostiquer `isOnTrial()` sur Tattooer
Vérifie dans `app/Models/Tattooer.php` :
- `isOnTrial()` doit retourner `true` si `trial_ends_at > now() && !is_subscribed`
- Si le modèle hérite du trait HasSubscription, vérifie que l'override local existe
  et que `!is_subscribed` est bien dans la condition (le trait seul ne le vérifie peut-être pas)
- Vérifie aussi en DB : le tattooer de test a-t-il bien `is_subscribed=false` et `trial_ends_at` futur ?
  Si `is_subscribed=true`, c'est le RegisteredUserListener ou le controller d'inscription
  qui le met à true → corriger à la source.

### Étape 2 — Corriger `resources/views/tattooer/subscription-plans.blade.php`
La structure conditionnelle principale DOIT suivre exactement :

```blade
@php
    $isOnTrial    = $artist->isOnTrial();
    $isSubscribed = $artist->is_subscribed;
    $currentPlan  = $artist->current_plan;
@endphp

{{-- SECTION STATUT --}}
@if ($isOnTrial)
    {{-- Bloc trial uniquement — PAS de boutons paiement/annulation --}}
    {{-- Modèle : copier le bloc équivalent de piercer/subscription-plans.blade.php --}}
@elseif ($isSubscribed && $currentPlan === 'pro')
    {{-- PRO payant actif : Gérer paiement + Annuler --}}
@elseif ($isSubscribed && $currentPlan === 'starter')
    {{-- STARTER payant actif : Gérer paiement + Annuler --}}
@else
    {{-- Expiré ou sans abonnement --}}
@endif

{{-- CARTES COMPARATIVES : masquer pendant le trial --}}
@if (!$isOnTrial)
    {{-- Afficher les cartes STARTER / PRO ici seulement --}}
@endif
```

### Étape 3 — Corriger `resources/views/piercer/subscription-plans.blade.php`
Même correctif que Tattooer pour les cartes comparatives :
Entourer le bloc des cartes avec `@if (!$isOnTrial)` ... `@endif`.
Le bloc statut trial fonctionne déjà, ne pas y toucher.

### Étape 4 — Corriger le badge plan sur `resources/views/piercer/profil.blade.php`
Chercher le bloc qui affiche "🟡 Plan STARTER" / "⭐ Plan PRO".
La condition utilise probablement `isPro()` ou `$currentPlan`.
Ajouter la vérification trial EN PREMIER :

```blade
@if ($piercer->isOnTrial())
    <span>⭐ Plan PRO</span> {{-- ou le badge PRO existant --}}
@elseif ($piercer->isPro())
    <span>⭐ Plan PRO</span>
@elseif ($piercer->isStarter())
    <span>🟡 Plan STARTER</span>
@else
    <span>⛔ Aucun abonnement</span>
@endif
```
Appliquer la même logique sur `tattooer/profil.blade.php` par cohérence.

### Étape 5 — Corriger `resources/views/tattooer/dashboard.blade.php`
Chercher comment la bannière trial est implémentée dans `piercer/dashboard.blade.php`.
Copier/adapter le bloc identique dans le dashboard tattooer, en remplaçant
`$piercer` par `$tattooer` (ou la variable injectée par le controller).
Vérifier que le TattooerController injecte bien `trial_ends_at` ou l'objet complet.

---

## ✅ Validation attendue après correction

| Page | État | Résultat attendu |
|------|------|-----------------|
| `/pierceur/subscription-plans` | Trial actif | Bloc trial UNIQUEMENT, zéro carte |
| `/pierceur/subscription-plans` | Trial actif | Bloc trial UNIQUEMENT, zéro carte |
| `/pierceur/profil` | Trial actif | Badge "⭐ Plan PRO" |
| `/tattooer/subscription-plans` | Trial actif | Bloc trial UNIQUEMENT, pas de "Gérer paiement" |
| `/tattooer/dashboard` | Trial actif | Bannière "🎁 Essai gratuit — X jours restants" |
| `/tattooer/subscription-plans` | PRO payant | "Gérer paiement" + "Annuler" |
| `/tattooer/subscription-plans` | Trial expiré | Proposer STARTER ou PRO |

---

## ⚠️ Contraintes absolues
- Ne PAS toucher à la logique Studio (elle fonctionne)
- Ne PAS modifier les migrations ni les colonnes DB
- Ne PAS changer Stripe/Cashier
- Si `is_subscribed=true` en DB pour un compte trial → tracer la source dans
  le listener/controller d'inscription et corriger là, pas en patches dans les vues
