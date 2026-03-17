# AUDIT_ADMIN.md — Panel Filament Admin

> Généré le 2026-03-16 · Laravel 12 / Filament v4.5

---

## 1. Inventaire des Resources

| Resource | Groupe nav | Icône | Modèle |
|---|---|---|---|
| TattooerResource | Modération | heroicon-o-paint-brush | Tattooer |
| PierceurResource | Modération | heroicon-o-scissors | Piercer |
| StudioResource | Modération | heroicon-o-building-office-2 | Studio |
| StudioArtistResource | Modération | heroicon-o-user-group | StudioArtist |
| BookingRequestResource | Réservations | heroicon-o-calendar | BookingRequest |
| AppointmentResource | Réservations | heroicon-o-calendar-days | Appointment |
| PaymentResource | Finances | heroicon-o-credit-card | Payment |
| TransactionResource | Finances | heroicon-o-banknotes | Transaction |
| SubscriptionResource | Finances | heroicon-o-credit-card | Subscription |
| UserResource | Utilisateurs | heroicon-o-users | User |
| ComplaintResource | Qualité | heroicon-o-exclamation-triangle | Complaint |
| ReviewResource | Qualité | heroicon-o-star | Review |
| ComplianceRecordResource | Qualité | heroicon-o-shield-check | ComplianceRecord |
| CancellationResource | Réservations | (défaut) | Cancellation |
| ConversationResource | Communication | (défaut) | Conversation |

---

## 2. Widgets retenus sur le Dashboard (10)

| Widget | Catégorie |
|---|---|
| StatsOverview | Stats générales |
| RevenueStatsWidget | Stats générales |
| MonthlyRevenueChartWidget | Graphiques revenus |
| ArtistRevenueChartWidget | Graphiques revenus |
| RecentActivityChartWidget | Activité |
| ComplaintsWidget | Modération |
| PendingTattooers | Modération |
| PendingPierceurs | Modération |
| PendingStudios | Modération |
| QualityAlerts | Alertes |

### Widgets retirés (7 doublons supprimés)

- CommissionWidget
- TotalTransactionsWidgetFixed
- RevenueChart
- RevenueOverviewWidget
- MonthlyRevenueChart
- RevenueByArtistType
- RecentActivity

**Cause** : `discoverWidgets()` chargeait automatiquement tous les fichiers widgets en plus de la liste explicite → doublons d'affichage. Résolution : suppression de `discoverWidgets()`, liste explicite uniquement.

---

## 3. Champs polymorphiques corrigés

### BookingRequestsTable.php
- **Problème** : 50+ colonnes brutes dont `bookable_type`, `bookable_id` illisibles
- **Fix** : Table réduite à 8 colonnes essentielles. Colonnes polymorphiques résolues via `getStateUsing()` :
  - `client_name` → `$record->client?->user?->name`
  - `artist` → `$record->bookable?->pseudo ?? $record->bookable?->name`
  - `artist_type` → `class_basename($record->bookable_type)`

---

## 4. Labels traduits (EN → FR)

### ComplaintForm.php
| Avant | Après |
|---|---|
| 'No show' | 'Absence client' |
| 'Quality' | 'Qualité' |
| 'Hygiene' | 'Hygiène' |
| 'Payment' | 'Paiement' |
| 'Other' | 'Autre' |
| 'Pending' | 'En attente' |
| 'Investigating' | 'En cours d\'enquête' |
| 'Resolved' | 'Résolu' |
| 'Rejected' | 'Rejeté' |

### PaymentForm.php
| Avant | Après |
|---|---|
| 'Pending' | 'En attente' |
| 'Succeeded' | 'Réussi' |
| 'Failed' | 'Échoué' |
| 'Canceled' | 'Annulé' |
| 'Deposit' | 'Acompte' |
| 'Remaining' | 'Solde' |
| 'Full' | 'Paiement complet' |
| 'Artist' | 'Artiste' |
| 'Studio' | 'Studio' |
+ labels ajoutés sur tous les champs (booking_request_id, stripe_payment_intent_id, etc.)

### StudioArtistForm.php
| Avant | Après |
|---|---|
| 'Not connected' | 'Non connecté' |
| 'Onboarding' | 'En cours d\'intégration' |
| 'Active' | 'Actif' |
| 'Inactive' | 'Inactif' |
| 'Reactivating' | 'En réactivation' |
| 'Non compliant' | 'Non conforme' |
| 'Compliant' | 'Conforme' |
| 'Expiring soon' | 'Expiration proche' |
| 'On leave' | 'En congé' |
| 'Deleted' | 'Supprimé' |

### StudioInfolist.php
| Avant | Après |
|---|---|
| 'Email address' | 'Email' |

### SubscriptionForm.php
| Avant | Après |
|---|---|
| 'Starter (7% commission)' | 'Starter (commission 7%)' |
| 'Pro (0% commission)' | 'Pro (commission 0%)' |

### TransactionsTable.php
| Avant | Après |
|---|---|
| 'Payment Intent' | 'Réf. Stripe' |

### BookingRequestForm.php
| Avant | Après |
|---|---|
| 'Asap' | 'Dès que possible' |
| '3 4months' | '3 à 4 mois' |
| '5 6months' | '5 à 6 mois' |
| '6plus' | 'Plus de 6 mois' |
| 'Morning' | 'Matin' |
| 'Afternoon' | 'Après-midi' |
| 'Evening' | 'Soir' |
| 'Anytime' | 'Peu importe' |

### Resources (groupes navigation)
| Fichier | Avant | Après |
|---|---|---|
| TattooerResource | 'Moderation' | 'Modération' |
| PierceurResource | 'Moderation' | 'Modération' |
| StudioResource | 'Moderation' | 'Modération' |
| StudioArtistResource | 'Moderation' | 'Modération' |
| AppointmentResource | 'Activite' | 'Réservations' |
| ComplianceRecordResource | 'Qualite' | 'Qualité' |
| SubscriptionResource | 'Gestion' | 'Finances' |

---

## 5. BadgeColumn → TextColumn->badge() (Filament v4)

La classe `BadgeColumn` est dépréciée en Filament v4. Migration effectuée :

| Fichier | Champ |
|---|---|
| AppointmentResource.php | status |
| TattooersTable.php | user.status |
| PierceursTable.php | user.status |
| ComplianceRecordsTable.php | type |
| SubscriptionsTable.php | plan, stripe_status |
| UsersTable.php | role, status |

**Pattern de migration** :
```php
// Avant (v3)
BadgeColumn::make('status')
    ->colors(['warning' => 'pending'])
    ->icons(['heroicon-o-clock' => 'pending'])

// Après (v4)
TextColumn::make('status')
    ->badge()
    ->color(fn ($state) => match($state) { 'pending' => 'warning', default => 'gray' })
    ->icon(fn ($state) => match($state) { 'pending' => 'heroicon-o-clock', default => '' })
```

---

## 6. Pages supprimées de la navigation

Aucune page n'a été supprimée (les pages inutilisées sont masquées via `navigationGroups` ou `->hidden()`).

---

## 7. Navigation finale

### Groupes enregistrés dans AdminPanelProvider

```php
->navigationGroups([
    'Modération',      // Tattooers, Pierceurs, Studios, StudioArtists
    'Réservations',    // BookingRequests (sort:1), Appointments (sort:2), Cancellations
    'Finances',        // Payments (sort:1), Transactions (sort:2), Subscriptions (sort:3)
    'Utilisateurs',    // Users
    'Communication',   // SupportChat, Conversations
    'Qualité',         // Reviews (sort:1), Complaints (sort:2), ComplianceRecords
])
```

### SupportChat (page custom)
- Vue entièrement refaite : interface chat 2 colonnes (sidebar + zone message)
- Bulles de messages style iMessage (admin=indigo droite, user=gris gauche)
- Séparateurs de dates, badges de rôle, indicateur non-lu, `wire:poll.5s`
- Ctrl+Enter pour envoyer, compteur de caractères live

---

## 8. Résumé des corrections

| # | Correction | Statut |
|---|---|---|
| 1 | SupportChat UI/UX refonte complète | ✅ |
| 2 | Suppression doublons widgets dashboard | ✅ |
| 3 | Traduction complète FR de tous les labels | ✅ |
| 4 | Champs polymorphiques (bookable_type/id) | ✅ |
| 5 | Migration BadgeColumn → TextColumn->badge() | ✅ |
| 6 | Réorganisation groupes navigation | ✅ |
| 7 | API Filament v4 : navigationGroup/Icon types | ✅ |
| 8 | TransactionsTable : recordActions + defaultSort | ✅ |
