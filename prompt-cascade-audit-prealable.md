# 🔍 PROMPT CASCADE — AUDIT PRÉ-CORRECTION : SCANNER LE CODE EXISTANT

## OBJECTIF

AVANT de corriger quoi que ce soit, je veux que tu **scannes et comprennes** mon code existant pour que toutes les corrections s'intègrent parfaitement dans l'architecture actuelle. Ne modifie rien, ne crée rien. Documente seulement.

---

## FICHIERS À SCANNER ET DOCUMENTER

Pour chaque fichier, je veux :
- Les **méthodes publiques** et leur signature
- Les **relations Eloquent** et leur type
- Les **champs fillable / casts / appends**
- Les **policies et autorisations** associées
- Les **conventions de nommage** utilisées

### MODÈLES (lire et documenter)

```
app/Models/BookingRequest.php
app/Models/Conversation.php
app/Models/Appointment.php
app/Models/Message.php
app/Models/Client.php
app/Models/Tattooer.php
app/Models/StudioArtist.php (si existe)
app/Models/User.php
```

Pour chaque modèle, documente :
- `$fillable`, `$casts`, `$appends`
- Toutes les relations (type, nom, modèle cible)
- Méthodes métier (ex: `createAppointment()`, `requestDeposit()`, `cancel()`)
- Scopes
- Enums ou constantes de statut utilisées

### CONTRÔLEURS (lire et documenter)

```
app/Http/Controllers/DepositController.php
app/Http/Controllers/BookingRequestController.php (ou tout controller gérant les bookings côté tattooer)
app/Http/Controllers/ClientController.php (ou tout controller gérant le chat/bookings côté client)
```

Pour chaque contrôleur, documente :
- Chaque méthode, sa signature, ce qu'elle fait
- Comment elle accède au user authentifié
- Les validations appliquées
- Les redirections et réponses
- Les services/actions injectés

### POLICIES (lire et documenter)

```
app/Policies/BookingRequestPolicy.php (si existe)
app/Policies/ConversationPolicy.php (si existe)
app/Policies/AppointmentPolicy.php (si existe)
```

Pour chaque policy :
- Quelles actions sont autorisées
- La logique de vérification (qui peut faire quoi)

### MIDDLEWARE (lire et documenter)

```
app/Http/Middleware/ (tous les fichiers custom)
bootstrap/app.php (section middleware)
```

Documente :
- Les middleware custom créés
- Les groupes de middleware configurés
- Comment le CSRF est géré (important pour le webhook Stripe)
- Comment les routes sont protégées

### ROUTES (lire et documenter)

```
routes/web.php
routes/api.php (si existe)
```

Documente :
- Toutes les routes liées aux bookings, deposits, conversations, appointments
- Les middleware appliqués par groupe
- Les noms de routes (`->name('...')`)
- Les paramètres de route et leur binding

### MIGRATIONS (lire et documenter)

```
database/migrations/*booking_requests*
database/migrations/*conversations*
database/migrations/*appointments*
database/migrations/*messages*
```

Documente :
- Tous les champs avec leur type
- Les index et foreign keys
- Les migrations d'altération (add/modify columns)

### VUES BLADE CLÉS (lire et documenter)

```
resources/views/client/deposit-payment.blade.php
resources/views/client/chat.blade.php (ou le composant Livewire équivalent)
resources/views/tattooer/message-show.blade.php (ou équivalent)
```

Documente :
- Les variables passées à chaque vue
- Les composants Livewire utilisés
- Les conditions d'affichage (statut-based)

### SERVICES / ACTIONS (lire et documenter)

```
app/Services/ (tous les fichiers)
app/Actions/ (tous les fichiers)
```

Si des Services ou Actions existent pour les paiements ou bookings, documente-les intégralement.

### TESTS EXISTANTS (lire et documenter)

```
tests/Feature/*Booking*
tests/Feature/*Deposit*
tests/Feature/*Payment*
tests/Feature/*Conversation*
tests/Feature/*Appointment*
tests/Unit/*Booking*
```

Documente :
- Les tests existants et ce qu'ils couvrent
- Les factories utilisées (`BookingRequest::factory()`, etc.)
- Les helpers de test custom
- Les traits de test

### CONFIG STRIPE (lire et documenter)

```
config/services.php (section stripe)
.env (clés STRIPE_* — ne PAS afficher les valeurs complètes, juste confirmer qu'elles existent)
```

---

## FORMAT DE SORTIE ATTENDU

Génère un document structuré avec cette structure exacte :

```
## 1. MODÈLES
### BookingRequest
- Fillable: [...]
- Casts: [...]
- Relations: [...]
- Méthodes métier: [...]
- Statuts possibles: [...]
- Scopes: [...]

### Conversation
(même structure)
...

## 2. CONTRÔLEURS
### DepositController
- success(): [signature, logique actuelle complète]
- process(): [signature, logique actuelle complète]
...

## 3. POLICIES
...

## 4. MIDDLEWARE
...

## 5. ROUTES
...

## 6. MIGRATIONS (schéma actuel)
...

## 7. VUES
...

## 8. SERVICES / ACTIONS
...

## 9. TESTS EXISTANTS
...

## 10. FACTORIES
...
```

---

## IMPORTANT

- **NE MODIFIE RIEN.** C'est un audit de lecture seule.
- Si un fichier n'existe pas, note-le clairement comme `❌ NON TROUVÉ`.
- Si une relation ou méthode semble incomplète ou cassée, note-le avec `⚠️`.
- Je vais utiliser ce document comme base pour le prompt de correction qui suivra.
