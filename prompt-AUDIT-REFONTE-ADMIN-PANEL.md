# 🔍 AUDIT & REFONTE COMPLÈTE — Panel Admin Filament v4

## Stack : Laravel 12, Filament v4, Livewire 3.7
## Structure Filament v4 : Resources/{Nom}/Pages/ + Schemas/ + Tables/ + {Nom}Resource.php

---

## PHASE 1 — AUDIT COMPLET (lecture seule, AUCUNE modification)

### 1.1 — Cartographie complète du panel

```bash
# Structure complète du panel admin
find app/Filament/Admin/ -type f -name "*.php" | sort

# Pages custom (non-Resource)
ls app/Filament/Admin/Pages/

# Widgets
ls app/Filament/Admin/Widgets/

# Provider du panel
cat app/Providers/Filament/AdminPanelProvider.php
```

### 1.2 — Inventaire des Resources

Pour chaque Resource trouvée, lire et documenter :
- Colonnes affichées dans la Table
- Formulaires dans Schemas/
- Actions disponibles
- Relations chargées
- Filtres

```bash
# Lire toutes les Resources
for f in app/Filament/Admin/Resources/*/; do
  echo "=== $f ===";
  find "$f" -name "*.php" | xargs grep -l "class " 2>/dev/null;
done

# Lire tous les widgets
cat app/Filament/Admin/Widgets/*.php 2>/dev/null
```

### 1.3 — Identifier les problèmes

Pour chaque Resource/Widget/Page, documenter dans ce tableau :

| Composant | Problème | Type |
|-----------|---------|------|
| SupportChat | Layout cassé, données mal structurées | UI/UX |
| Dashboard | Doublons de widgets | BUG |
| ? | Champs vides | DATA |
| ? | Labels en anglais | I18N |
| ? | Page inutile/redondante | CLEANUP |

### 1.4 — Vérifier les données vides

```bash
php artisan tinker
```
```php
// Stats générales pour valider ce qui a des données
dd([
    'users'              => \App\Models\User::count(),
    'tattooers'          => \App\Models\Tattooer::count(),
    'piercers'           => \App\Models\Piercer::count(),
    'studios'            => \App\Models\Studio::count(),
    'bookings'           => \App\Models\BookingRequest::count(),
    'transactions'       => \DB::table('transactions')->count(),
    'booking_trans'      => \App\Models\BookingTransaction::count(),
    'complaints'         => \DB::table('complaints')->count(),
    'reviews'            => \DB::table('reviews')->count(),
    'notifications'      => \DB::table('notifications')->count(),
    'conversations'      => \App\Models\Conversation::count(),
    'messages'           => \App\Models\Message::count(),
    'subscriptions'      => \DB::table('subscriptions')->count(),
]);
```

---

## PHASE 2 — CORRECTIONS

### ⚠️ RÈGLES OBLIGATOIRES Filament v4
- Structure : `Resources/{Nom}/{Nom}Resource.php` + `Pages/` + `Schemas/` + `Tables/`
- Tables → `Tables/{Nom}Table.php` avec méthode `static function table(Table $table)`
- Schemas → `Schemas/{Nom}Form.php` avec méthode `static function form(Form $form)`
- Pages → `Pages/List{Nom}.php`, `Create{Nom}.php`, `Edit{Nom}.php`, `View{Nom}.php`
- `getHeaderActions()` dans les Pages (pas `getActions()`)
- Widgets : `extends BaseWidget` ou `extends ChartWidget`
- Tout le texte en **français**

---

## CORRECTION 1 — PAGE SUPPORT CHAT (refonte complète)

La page actuelle est cassée (voir screenshot). Réécrire proprement.

Lire `app/Filament/Admin/Pages/SupportChat.php` et sa vue.

La page doit avoir :
- **Colonne gauche** (1/3) : liste des conversations avec avatar initial, nom, 
  rôle (Tatoueur/Pierceur/Client), extrait dernier message, heure, badge unread
- **Colonne droite** (2/3) : fil de messages de la conversation active + input réponse
- **Polling** `wire:poll.3s` sur les messages uniquement
- **Badge** navigation : count messages non lus

Problèmes à corriger dans la vue actuelle :
1. Avatar placeholder énorme (image cassée) → remplacer par initiales CSS
2. "Profil / Aujourd'hui" affiché dans le fil de messages → c'est un label de section, pas un message
3. Layout non structuré → utiliser CSS Grid ou Flexbox propre
4. Informations utilisateur (nom, rôle, email) → les afficher dans le header de la conversation active, pas dans la liste

```php
// Structure correcte de la vue support-chat.blade.php
// Grid 2 colonnes dans un container fixe height
<div class="grid grid-cols-3 gap-0 h-[calc(100vh-220px)] bg-white rounded-xl
            border border-gray-200 overflow-hidden shadow-sm">
    // Col 1 : liste conversations
    // Col 2+3 : chat actif
</div>
```

---

## CORRECTION 2 — DASHBOARD : SUPPRIMER LES DOUBLONS DE WIDGETS

```bash
# Identifier tous les widgets enregistrés dans le panel
grep -n "widgets\|Widget\|getWidgets" \
  app/Providers/Filament/AdminPanelProvider.php

# Lister les widgets existants
ls app/Filament/Admin/Widgets/
```

Pour chaque widget, vérifier s'il est dupliqué ou redondant.
Supprimer de `AdminPanelProvider::getWidgets()` (ou `->widgets([])`) les doublons.
Ne PAS supprimer les fichiers — juste les retirer de la liste d'enregistrement.

Garder uniquement :
- 1 widget de stats générales (users, bookings, revenus)
- 1 graphique de revenus mensuels
- 1 widget activité récente
- 1 widget alertes/modération (tattooers en attente, réclamations)
- Supprimer tout ce qui est doublon ou vide

---

## CORRECTION 3 — TRADUCTION COMPLÈTE EN FRANÇAIS

Chercher tous les labels en anglais dans les Resources :

```bash
grep -rn "'label' =>\|->label(\|->heading(\|->title(\|->placeholder(" \
  app/Filament/Admin/ --include="*.php" | \
  grep -i "name\|email\|status\|created\|updated\|action\|delete\|edit\|create\|view\|search\|filter\|export\|import" | \
  grep -v "//\|#" | head -50
```

Traduire systématiquement dans chaque fichier :

| Anglais | Français |
|---------|---------|
| `'Name'` | `'Nom'` |
| `'Email'` | `'Email'` (ok) |
| `'Status'` | `'Statut'` |
| `'Created at'` | `'Créé le'` |
| `'Updated at'` | `'Modifié le'` |
| `'Actions'` | `'Actions'` (ok) |
| `'Edit'` | `'Modifier'` |
| `'Delete'` | `'Supprimer'` |
| `'View'` | `'Voir'` |
| `'Create'` | `'Créer'` |
| `'Save'` | `'Enregistrer'` |
| `'Cancel'` | `'Annuler'` |
| `'Search'` | `'Rechercher'` |
| `'Filter'` | `'Filtrer'` |
| `'Export'` | `'Exporter'` |
| `'Are you sure'` | `'Êtes-vous sûr'` |
| `'No records found'` | `'Aucun résultat'` |

---

## CORRECTION 4 — CHAMPS VIDES : RELATIONS MANQUANTES

Les champs vides viennent de relations non chargées ou de colonnes mal nommées.

Pour chaque Resource avec champs vides, vérifier :

```bash
# Exemple pour BookingRequests
grep -n "getStateUsing\|->relationship\|->column\|make(" \
  app/Filament/Admin/Resources/BookingRequests/Tables/BookingRequestsTable.php
```

Pattern correct pour les relations polymorphiques (bookable_type/bookable_id) :

```php
// ❌ AVANT — relation directe qui peut être null
Tables\Columns\TextColumn::make('bookable.name')
    ->label('Artiste'),

// ✅ APRÈS — getStateUsing pour gérer le polymorphisme
Tables\Columns\TextColumn::make('artist')
    ->label('Artiste')
    ->getStateUsing(fn($record) =>
        $record->bookable?->pseudo
        ?? $record->bookable?->name
        ?? ($record->bookable_type
            ? class_basename($record->bookable_type) . ' #' . $record->bookable_id
            : '—')
    )
    ->searchable(query: fn($query, $search) =>
        $query->whereHasMorph('bookable', [\App\Models\Tattooer::class, \App\Models\Piercer::class],
            fn($q) => $q->where('pseudo', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
        )
    ),
```

### Resources à corriger en priorité (champs vides probables) :

**BookingRequests** :
- `bookable` (polymorphique) → utiliser `getStateUsing`
- `client.user.name` → vérifier la relation `client → user`
- `status` → ajouter un badge coloré avec les statuts en français

**Transactions** :
- `artist_id` + `artist_type` (polymorphique) → `getStateUsing`
- Montants → vérifier la colonne exacte (`amount`, `total`, `net_amount`...)

**Subscriptions** :
- Lié à `User` via Cashier → `user.tattooer.pseudo` ou `user.piercer.pseudo`

**Reviews** :
- Relation `reviewable_type/reviewable_id` → polymorphique

---

## CORRECTION 5 — PAGES INUTILES OU REDONDANTES

Après l'audit, identifier et supprimer de `AdminPanelProvider` les Resources qui :
- N'ont pas de données et n'en auront jamais
- Font doublon avec une autre Resource
- Sont des artifacts de développement

Ne PAS supprimer les fichiers — juste les retirer du Provider.

---

## CORRECTION 6 — AMÉLIORATION UI/UX PAR RESOURCE

### Principes généraux à appliquer partout :

```php
// Tables : toujours définir
->striped()          // lignes alternées
->paginated([10, 25, 50])
->defaultSort('created_at', 'desc')
->searchable()       // sur les colonnes pertinentes
->emptyStateHeading('Aucun enregistrement')
->emptyStateDescription('Il n\'y a rien à afficher pour l\'instant.')

// Colonnes de date : format français
Tables\Columns\TextColumn::make('created_at')
    ->label('Créé le')
    ->dateTime('d/m/Y H:i')
    ->sortable()
    ->toggleable(isToggledHiddenByDefault: true), // masquer par défaut si peu utile

// Colonnes monétaires
Tables\Columns\TextColumn::make('amount')
    ->label('Montant')
    ->money('EUR')
    ->sortable(),

// Badges de statut colorés
Tables\Columns\BadgeColumn::make('status')
    ->label('Statut')
    ->colors([
        'gray'    => 'pending',
        'warning' => 'accepted',
        'success' => 'completed',
        'danger'  => 'cancelled',
        'info'    => 'deposit_paid',
    ])
    ->formatStateUsing(fn($state) => match($state) {
        'pending'      => 'En attente',
        'accepted'     => 'Acceptée',
        'deposit_paid' => 'Acompte payé',
        'completed'    => 'Terminée',
        'cancelled'    => 'Annulée',
        'expired'      => 'Expirée',
        default        => $state,
    }),
```

### Resources prioritaires à améliorer :

**1. BookingRequests (Demandes)**
- Table : ref #id, client, artiste (polymorphique), statut badge, montant, date
- Filtres : par statut, par type artiste (tattooer/piercer), par mois
- Actions : voir détail, voir conversation liée

**2. Tattooers / Piercers (Modération)**
- Table : pseudo, email, ville, plan badge, statut vérification, stripe connect badge
- Filtres : par plan, par statut Connect, en attente vérification
- Actions : vérifier, bloquer, voir profil

**3. Studios**
- Table : nom, ville, plan, nb artistes, mode paiement, statut
- Relation correcte avec le user propriétaire

**4. Transactions / BookingTransactions**
- Unifier si doublon
- Montants, commission, statut, lien Stripe

**5. Annulations & Remboursements**
- Déjà revu — vérifier cohérence avec les autres

**6. Subscriptions (Abonnements)**
- Table : user (via tattooer/piercer), plan, statut Stripe, date début, date fin
- Badge statut : actif/en essai/annulé/expiré

---

## CORRECTION 7 — NAVIGATION ADMIN RÉORGANISÉE

Réorganiser les groupes de navigation dans `AdminPanelProvider` :

```php
// Ordre suggéré des groupes :
// 1. Dashboard (sans groupe)
// 2. "Modération" : Tatoueurs, Pierceurs, Studios, Artistes Studio
// 3. "Réservations" : Demandes, Rendez-vous
// 4. "Finances" : Transactions, Abonnements, Annulations & Remboursements
// 5. "Utilisateurs" : Clients, Utilisateurs
// 6. "Communication" : Chat Support, Support & Réclamations
// 7. "Qualité" : Avis, Réclamations, Documents de conformité
// 8. "Administration" : (pages système si besoin)
```

Vérifier que chaque Resource a bien `navigationGroup` et `navigationSort` définis.

---

## PHASE 3 — RAPPORT FINAL ATTENDU

Claude Code doit produire un fichier `AUDIT_ADMIN.md` à la racine avec :

```markdown
# Audit Panel Admin — [DATE]

## 1. Inventaire Resources
| Resource | Fichiers | Données en base | Problèmes |
|----------|---------|----------------|----------|

## 2. Widgets actifs (après dédoublonnage)
Liste des widgets conservés

## 3. Champs vides corrigés
| Resource | Colonne | Cause | Fix |

## 4. Labels traduits
Nombre de labels traduits par fichier

## 5. Pages supprimées de la navigation
Liste

## 6. Navigation finale
Groupes et ordre
```

---

## ⚠️ Contraintes absolues
- Structure Filament v4 stricte : ne jamais mélanger v3 et v4 patterns
- `getHeaderActions()` jamais `getActions()` dans les Pages
- Pas de `->reactive()` → utiliser `->live()` (v4)
- Pas de `->afterStateUpdated()` sans `->live()`
- Ne pas supprimer les fichiers Resource — juste les retirer du Provider si inutiles
- Ne pas toucher à la logique métier (controllers, services, models)
- Tout texte visible par l'admin doit être en français
- Tester après chaque Resource modifiée : `php artisan filament:check-page-access`
