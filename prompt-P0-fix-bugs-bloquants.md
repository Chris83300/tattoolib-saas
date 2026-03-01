# 🚨 P0 — FIX BUGS BLOQUANTS (Audit Global)
# Pour Claude Code — Commit après chaque fix

## CONTEXTE

L'audit global Ink&Pik (2026-03-01) a identifié 6 problèmes bloquants pour le lancement.
Ce prompt les corrige TOUS dans l'ordre.

Stack : Laravel 12, Livewire 3.7, TailwindCSS v4, Alpine.js, MySQL.

---

## PHASE 0 — AUDIT CIBLÉ

```bash
echo "=== AUDIT P0 ==="

# 0A. Notifications manquantes — vérifier les TODO exacts
echo "--- TODO NOTIFICATIONS ---"
grep -n "TODO\|todo\|FIXME" app/Actions/AcceptBookingRequest.php | head -5
grep -n "TODO\|todo\|FIXME" app/Actions/RejectBookingRequest.php | head -5
grep -n "TODO\|todo\|FIXME" app/Actions/ConfirmAppointmentDate.php | head -5
grep -n "TODO\|todo\|FIXME" app/Actions/ReportNoShowAction.php | head -5
grep -n "TODO\|todo\|FIXME" app/Http/Controllers/ClientController.php | head -10
grep -n "TODO\|todo\|FIXME" app/Livewire/Client/DateSelection.php | head -5
grep -n "TODO\|todo\|FIXME" app/Livewire/Tattooer/AppointmentDetailModal.php | head -5
grep -n "TODO\|todo\|FIXME" app/Livewire/Tattooer/BookingQuickCreate.php | head -5
grep -n "TODO\|todo\|FIXME" app/Livewire/Tattooer/QuickBookingModal.php | head -5

# 0B. Classes de notification existantes — pour les réutiliser
echo "--- NOTIFICATIONS EXISTANTES ---"
ls app/Notifications/ | sort

# 0C. pending_deposits
echo "--- PENDING DEPOSITS ---"
grep -n "pending_deposits" app/Http/Controllers/TattooerController.php | head -5
grep -B 5 -A 10 "pending_deposits" app/Http/Controllers/TattooerController.php | head -20

# 0D. portfolio_count
echo "--- PORTFOLIO COUNT ---"
grep -n "portfolio_count" app/Http/Resources/ArtistResource.php | head -3
grep -B 3 -A 5 "portfolio_count" app/Http/Resources/ArtistResource.php

# 0E. Doublons routes
echo "--- DOUBLONS ROUTES ---"
php artisan route:list 2>&1 | grep "studio\.studio\.\|tattooer\.tattooer\." | head -15

# 0F. Route pierceur.messages.livewire
echo "--- PIERCEUR MESSAGES LIVEWIRE ---"
php artisan route:list --name="pierceur.messages" 2>&1
php artisan route:list --name="tattooer.messages" 2>&1

# 0G. Copie médias à expiration
echo "--- COPIE MEDIAS EXPIRATION ---"
grep -n "TODO\|todo\|copie\|copy.*media\|media.*copy" app/Jobs/CheckExpiredBookingRequests.php | head -5
grep -B 5 -A 10 "TODO" app/Jobs/CheckExpiredBookingRequests.php | head -30

# 0H. Appointment Events
echo "--- APPOINTMENT EVENTS ---"
grep -n "TODO\|Event\|event\|dispatch" app/Models/Appointment.php | head -10

# 0I. DeactivateInactiveStripeAccounts notification
echo "--- DEACTIVATE STRIPE ---"
grep -n "TODO\|notification\|mail\|Mail" app/Console/Commands/DeactivateInactiveStripeAccounts.php | head -5
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## FIX P0.1 — NOTIFICATIONS MANQUANTES DANS LES ACTIONS

C'est le fix le plus critique : le workflow est SILENCIEUX. Les clients et artistes ne sont pas notifiés des étapes clés.

### Méthode

Pour chaque TODO trouvé, il faut :
1. Identifier la classe de notification existante qui correspond (elles existent dans app/Notifications/)
2. Brancher la notification au bon endroit
3. Supprimer le commentaire TODO

Voici le mapping des notifications à brancher :

### P0.1a — AcceptBookingRequest (client pas notifié quand sa demande est acceptée)

```bash
cat app/Actions/AcceptBookingRequest.php
```

La notification `BookingAcceptedNotification` ou `NewBookingRequestNotification` devrait exister. Vérifier :
```bash
ls app/Notifications/ | grep -i "accept\|booking"
```

Brancher à l'endroit du TODO :
```php
// Remplacer le TODO par :
$bookingRequest->client->user->notify(new \App\Notifications\BookingAcceptedNotification($bookingRequest));
```

Si `BookingAcceptedNotification` n'existe pas, utiliser la notification la plus proche ou en créer une :
```bash
php artisan make:notification BookingAcceptedNotification
```

```php
// app/Notifications/BookingAcceptedNotification.php
namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingAcceptedNotification extends Notification
{
    use Queueable;

    public function __construct(public BookingRequest $bookingRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $artisanName = $this->bookingRequest->bookable?->user?->name ?? 'L\'artiste';
        
        return (new MailMessage)
            ->subject('Votre demande a été acceptée !')
            ->greeting('Bonne nouvelle !')
            ->line("{$artisanName} a accepté votre demande de réservation.")
            ->line('Vous pouvez maintenant sélectionner un créneau et procéder au paiement de l\'acompte.')
            ->action('Voir ma demande', url('/client/demandes/' . $this->bookingRequest->id))
            ->line('Merci de votre confiance !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking_accepted',
            'booking_request_id' => $this->bookingRequest->id,
            'message' => 'Votre demande a été acceptée',
        ];
    }
}
```

### P0.1b — RejectBookingRequest (client pas notifié quand sa demande est rejetée)

```bash
cat app/Actions/RejectBookingRequest.php
```

Vérifier si `BookingRejectedNotification` existe :
```bash
ls app/Notifications/ | grep -i "reject"
```

Si elle n'existe pas :
```bash
php artisan make:notification BookingRejectedNotification
```

```php
// app/Notifications/BookingRejectedNotification.php
namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public BookingRequest $bookingRequest,
        public ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $artisanName = $this->bookingRequest->bookable?->user?->name ?? 'L\'artiste';
        $mail = (new MailMessage)
            ->subject('Mise à jour de votre demande')
            ->greeting('Bonjour,')
            ->line("{$artisanName} n'a pas pu donner suite à votre demande.");

        if ($this->reason) {
            $mail->line("Motif : {$this->reason}");
        }

        return $mail
            ->line('Vous pouvez rechercher d\'autres artistes sur la marketplace Ink&Pik.')
            ->action('Explorer la marketplace', url('/marketplace'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking_rejected',
            'booking_request_id' => $this->bookingRequest->id,
            'reason' => $this->reason,
            'message' => 'Votre demande n\'a pas été retenue',
        ];
    }
}
```

Brancher dans RejectBookingRequest à l'endroit du TODO :
```php
$bookingRequest->client->user->notify(
    new \App\Notifications\BookingRejectedNotification($bookingRequest, $reason ?? null)
);
```

### P0.1c — ConfirmAppointmentDate (artiste pas notifié quand le client confirme une date)

```bash
cat app/Actions/ConfirmAppointmentDate.php
```

Brancher la notification vers l'artiste (tattooer/piercer) :
```php
// L'artiste doit être notifié que le client a confirmé la date
$artisan = $bookingRequest->bookable;
if ($artisan && $artisan->user) {
    $artisan->user->notify(new \App\Notifications\AppointmentConfirmedNotification($bookingRequest));
}
```

Vérifier si AppointmentConfirmedNotification existe déjà :
```bash
ls app/Notifications/ | grep -i "confirm\|appointment"
```

Si elle existe, la réutiliser. Sinon la créer avec le même pattern.

### P0.1d — DateSelection Livewire (artiste pas notifié quand le client sélectionne une date)

```bash
cat app/Livewire/Client/DateSelection.php
```

Aux endroits des TODO (l.70, l.89), brancher :
```php
// Notifier l'artiste qu'une date a été sélectionnée
$artisan = $this->bookingRequest->bookable;
if ($artisan && $artisan->user) {
    $artisan->user->notify(new \App\Notifications\NewBookingRequestNotification($this->bookingRequest));
}
```

OU créer une notification dédiée `DateSelectedNotification` si la sémantique est différente.

### P0.1e — ClientController (artiste pas notifié pour certaines actions client)

```bash
grep -n "TODO" app/Http/Controllers/ClientController.php
```

Pour CHAQUE TODO lié à une notification manquante, brancher la notification appropriée en fonction du contexte (création client, action booking, etc.).

### P0.1f — AppointmentDetailModal + BookingQuickCreate + QuickBookingModal

```bash
grep -n "TODO" app/Livewire/Tattooer/AppointmentDetailModal.php
grep -n "TODO" app/Livewire/Tattooer/BookingQuickCreate.php
grep -n "TODO" app/Livewire/Tattooer/QuickBookingModal.php
```

Pour chaque TODO lié à une notification client manquante, brancher la notification appropriée.

### P0.1g — ReportNoShowAction (admin pas notifié)

```bash
cat app/Actions/ReportNoShowAction.php
```

Brancher une notification admin :
```php
// Notifier l'admin du no-show
$admins = \App\Models\User::where('role', 'admin')->get();
foreach ($admins as $admin) {
    $admin->notify(new \App\Notifications\NoShowReportedNotification($bookingRequest));
}
```

Vérifier si NoShowReportedNotification existe déjà :
```bash
ls app/Notifications/ | grep -i "noshow\|no.show\|no_show"
```

### P0.1h — DeactivateInactiveStripeAccounts (email pas envoyé)

```bash
cat app/Console/Commands/DeactivateInactiveStripeAccounts.php
```

Au TODO, ajouter :
```php
// Notifier l'artiste de la désactivation
$user = $artisan->user;
if ($user) {
    $user->notify(new \App\Notifications\StripeAccountDeactivatedNotification($artisan));
}
```

Créer la notification si elle n'existe pas.

### P0.1i — Appointment Events manquants

```bash
grep -B 5 -A 10 "TODO\|Event\|dispatch" app/Models/Appointment.php | head -40
```

Pour les events `AppointmentCompleted`, `ClientNoShow`, `AppointmentDisputed` :

Vérifier si les classes Event existent :
```bash
find app/Events -name "*Appointment*" -o -name "*NoShow*" -o -name "*Disputed*" 2>/dev/null
```

Si elles n'existent pas, les créer :
```bash
php artisan make:event AppointmentCompleted
php artisan make:event ClientNoShow
php artisan make:event AppointmentDisputed
```

Chaque event prend l'Appointment en constructeur :
```php
namespace App\Events;
use App\Models\Appointment;
use Illuminate\Foundation\Events\Dispatchable;

class AppointmentCompleted
{
    use Dispatchable;
    public function __construct(public Appointment $appointment) {}
}
```

Brancher les `event(new ...)` aux endroits des TODO dans Appointment model.

### Récap P0.1

```bash
# Vérifier que tous les TODO notifications ont été traités
echo "--- TODO RESTANTS ---"
grep -rn "TODO.*notif\|TODO.*Notif\|todo.*notif" app/Actions/ app/Http/Controllers/ app/Livewire/ app/Jobs/ app/Console/ app/Models/Appointment.php 2>/dev/null | head -20
echo "Si vide = tout traité ✅"

git add -A && git commit -m "fix(P0.1): brancher toutes les notifications manquantes dans le workflow"
```

---

## FIX P0.2 — pending_deposits = 0 DANS LE DASHBOARD

```bash
grep -B 10 -A 10 "pending_deposits" app/Http/Controllers/TattooerController.php
```

Le `pending_deposits` doit calculer le montant total des acomptes en attente de paiement pour les demandes acceptées de l'artiste.

Remplacer le `0` par le vrai calcul :

```php
// pending_deposits = somme des acomptes des bookings en status "awaiting_deposit" ou "accepted"
$pendingDeposits = \App\Models\BookingRequest::where('bookable_type', get_class($artisan))
    ->where('bookable_id', $artisan->id)
    ->whereIn('status', [
        \App\Enums\BookingRequestStatus::ACCEPTED,
        \App\Enums\BookingRequestStatus::AWAITING_DEPOSIT,
    ])
    ->sum('deposit_amount');
```

IMPORTANT : vérifier les noms exacts des statuts dans l'enum :
```bash
cat app/Enums/BookingRequestStatus.php
```

Et vérifier si `deposit_amount` est en centimes ou en euros :
```bash
grep -n "deposit_amount" app/Models/BookingRequest.php | head -5
```

Si en centimes, diviser par 100 pour l'affichage.

Adapter le code existant — ne pas ajouter une nouvelle variable, mais corriger la valeur existante.

```bash
git add -A && git commit -m "fix(P0.2): calculer pending_deposits réel dans dashboard tattooer"
```

---

## FIX P0.3 — portfolio_count = 0 DANS ArtistResource

```bash
cat app/Http/Resources/ArtistResource.php
```

Le `portfolio_count` doit compter le nombre de médias dans la collection portfolio de l'artiste.

Remplacer le `0` par :

```php
'portfolio_count' => $this->getMedia('portfolio')->count(),
```

OU si la collection s'appelle autrement :
```bash
grep -n "registerMediaCollections\|addMediaCollection\|portfolio" app/Models/Tattooer.php | head -5
grep -n "registerMediaCollections\|addMediaCollection\|portfolio" app/Models/Traits/IsArtisan.php | head -5
```

Adapter le nom de la collection au nom réel.

Si le model utilise une relation au lieu de Spatie Media :
```bash
grep -n "portfolio\|Portfolio\|photos\|works" app/Models/Tattooer.php | head -10
```

Adapter selon la structure réelle.

```bash
git add -A && git commit -m "fix(P0.3): portfolio_count réel dans ArtistResource API"
```

---

## FIX P0.4 — DOUBLONS DANS LES NOMS DE ROUTES

Les routes `studio.studio.*` et `tattooer.tattooer.*` ont un préfixe dupliqué, probablement à cause d'un groupe de routes mal nommé (groupe avec name('studio.') contenant des routes qui commencent déjà par studio.).

```bash
# Identifier les doublons
php artisan route:list 2>&1 | grep "studio\.studio\.\|tattooer\.tattooer\." | head -20

# Trouver dans les fichiers de routes
grep -n "name.*studio\.\|->name.*studio" routes/web.php | head -10
grep -n "name.*tattooer\.\|->name.*tattooer" routes/web.php | head -10
```

### Méthode de correction

Le problème vient d'un `->name('studio.')` sur un groupe qui contient des routes déjà nommées `studio.xxx`. Il y a 2 façons de corriger :

**Option A** — Retirer le préfixe du groupe parent :
```php
// AVANT (mauvais) :
Route::prefix('studio')->name('studio.')->group(function () {
    Route::get('/compliance', ...)->name('studio.compliance'); // → studio.studio.compliance
});

// APRÈS (corrigé) :
Route::prefix('studio')->name('studio.')->group(function () {
    Route::get('/compliance', ...)->name('compliance'); // → studio.compliance
});
```

**Option B** — Retirer le name() du groupe et garder les noms complets sur chaque route.

IMPORTANT :
- Après correction, vérifier que TOUTES les vues qui référencent ces routes sont mises à jour
- Chercher les anciens noms dans les vues :

```bash
# Trouver les références aux anciens noms de route dans les vues
grep -rn "studio\.studio\." resources/views/ --include="*.blade.php" | head -20
grep -rn "tattooer\.tattooer\." resources/views/ --include="*.blade.php" | head -20

# Et dans les controllers/Livewire
grep -rn "studio\.studio\." app/ --include="*.php" | head -20
grep -rn "tattooer\.tattooer\." app/ --include="*.php" | head -20
```

Mettre à jour CHAQUE référence trouvée.

```bash
# Vérifier qu'il n'y a plus de doublons
php artisan route:list 2>&1 | grep "studio\.studio\.\|tattooer\.tattooer\." | wc -l
echo "Doit être 0"

git add -A && git commit -m "fix(P0.4): supprimer doublons noms de routes studio.studio.* et tattooer.tattooer.*"
```

---

## FIX P0.5 — ROUTE pierceur.messages.livewire ABSENTE

```bash
# Vérifier ce que le tattooer a
php artisan route:list --name="tattooer.messages" --columns=method,uri,name 2>&1

# Ce que le pierceur a
php artisan route:list --name="pierceur.messages" --columns=method,uri,name 2>&1
```

Le pierceur doit avoir les MÊMES routes messages que le tattooer (architecture polymorphique miroir).

```bash
# Trouver la route tattooer.messages.livewire dans routes/web.php
grep -n "messages.livewire\|messages-livewire\|MessagesLivewire\|messages.*livewire" routes/web.php | head -5
```

Dupliquer la route dans le groupe pierceur avec le même controller/composant Livewire :

```php
// Dans le groupe pierceur, ajouter la route manquante
// Copier exactement la route du tattooer en changeant le prefix/name
Route::get('/messages', [/* même controller ou composant Livewire */])->name('messages.livewire');
```

IMPORTANT : Si le composant Livewire est spécifique au tattooer (namespace Tattooer\Messages), vérifier s'il est compatible avec le pierceur via le trait IsArtisan. Si oui, réutiliser le même composant. Si non, créer un alias ou un composant dédié.

```bash
# Vérifier
php artisan route:list --name="pierceur.messages" --columns=method,uri,name 2>&1

git add -A && git commit -m "fix(P0.5): ajouter route pierceur.messages.livewire manquante"
```

---

## FIX P0.6 — COPIE MÉDIAS → FICHE CLIENT À EXPIRATION BOOKING

```bash
cat app/Jobs/CheckExpiredBookingRequests.php
```

Quand un booking expire, les médias (photos de référence, dessins envoyés) doivent être copiés vers la fiche client pour ne pas être perdus.

À l'endroit du TODO (l.170), implémenter :

```php
// Copier les médias du booking vers la fiche client
$this->copyMediaToClientCareSheet($bookingRequest);
```

Créer la méthode helper dans le job :

```php
/**
 * Copie les médias d'un booking expiré vers la fiche client.
 */
private function copyMediaToClientCareSheet(BookingRequest $bookingRequest): void
{
    $client = $bookingRequest->client;
    $artisan = $bookingRequest->bookable;
    
    if (!$client || !$artisan) return;

    // Chercher ou créer la fiche client
    $careSheet = \App\Models\ClientCareSheet::firstOrCreate(
        [
            'client_id' => $client->id,
            'bookable_type' => get_class($artisan),
            'bookable_id' => $artisan->id,
        ],
        [
            'studio_id' => $artisan->studio_id ?? null,
        ]
    );

    // Copier les médias du booking vers la fiche client
    // Collections possibles : 'reference_photos', 'designs', 'attachments'
    $collections = ['reference_photos', 'designs', 'attachments'];
    
    foreach ($collections as $collection) {
        foreach ($bookingRequest->getMedia($collection) as $media) {
            try {
                $media->copy($careSheet, 'booking_archives');
            } catch (\Exception $e) {
                \Log::warning("Erreur copie média {$media->id} vers fiche client : {$e->getMessage()}");
            }
        }
    }
}
```

IMPORTANT :
- Vérifier les vrais noms de collections média sur BookingRequest :
```bash
grep -n "registerMediaCollections\|addMediaCollection" app/Models/BookingRequest.php | head -10
```
- Vérifier la structure de ClientCareSheet :
```bash
grep -n "fillable\|function " app/Models/ClientCareSheet.php | head -15
```
- Adapter `firstOrCreate` selon les colonnes réelles (client_id pourrait être différent)

Pour les notifications manquantes aux l.185 et l.188 du même job :
```php
// Notifier le client que le booking a expiré
if ($bookingRequest->client?->user) {
    $bookingRequest->client->user->notify(
        new \App\Notifications\BookingExpiredNotification($bookingRequest)
    );
}

// Notifier l'artiste
if ($bookingRequest->bookable?->user) {
    $bookingRequest->bookable->user->notify(
        new \App\Notifications\BookingExpiredNotification($bookingRequest)
    );
}
```

Créer `BookingExpiredNotification` si elle n'existe pas :
```bash
ls app/Notifications/ | grep -i "expir"
```

```bash
git add -A && git commit -m "fix(P0.6): copie médias vers fiche client à expiration booking + notifications"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION P0 ==="

# V1. Plus de TODO critiques dans les actions
echo "--- TODO RESTANTS ACTIONS ---"
grep -c "TODO" app/Actions/AcceptBookingRequest.php app/Actions/RejectBookingRequest.php app/Actions/ConfirmAppointmentDate.php app/Actions/ReportNoShowAction.php 2>/dev/null

# V2. pending_deposits
echo "--- PENDING DEPOSITS ---"
grep "pending_deposits" app/Http/Controllers/TattooerController.php | grep -v "// 0\|= 0"

# V3. portfolio_count
echo "--- PORTFOLIO COUNT ---"
grep "portfolio_count" app/Http/Resources/ArtistResource.php | grep -v "// 0\|=> 0"

# V4. Plus de doublons routes
echo "--- DOUBLONS ROUTES ---"
php artisan route:list 2>&1 | grep -c "studio\.studio\.\|tattooer\.tattooer\."
echo "Doit être 0"

# V5. Route pierceur.messages.livewire
echo "--- PIERCEUR MESSAGES ---"
php artisan route:list --name="pierceur.messages" 2>&1 | wc -l
echo "Doit être > 0"

# V6. Copie médias
echo "--- COPIE MEDIAS ---"
grep "copyMediaToClientCareSheet\|copy.*media\|media.*copy" app/Jobs/CheckExpiredBookingRequests.php | head -3

# V7. Events Appointment
echo "--- EVENTS ---"
grep "event\|dispatch" app/Models/Appointment.php | grep -v "TODO" | head -5

# V8. Notifications créées
echo "--- NOUVELLES NOTIFICATIONS ---"
ls app/Notifications/ | wc -l

# V9. Compilation
php artisan route:clear
php artisan view:clear
php artisan route:list 2>&1 | head -3
echo "Routes OK si pas d'erreur"

# V10. TODO restants dans le projet
echo "--- TOUS LES TODO RESTANTS ---"
grep -rn "TODO" app/Actions/ app/Http/Controllers/ app/Livewire/ app/Jobs/ app/Console/Commands/ app/Models/Appointment.php --include="*.php" 2>/dev/null | wc -l
echo "Nombre de TODO restants (devrait être proche de 0 pour les critiques)"

echo "=== P0 TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 obligatoire
2. **Réutiliser les notifications existantes** — 23 classes existent déjà, ne pas dupliquer si une classe convient
3. **Ne pas casser le workflow existant** — ajouter les notifications, ne pas modifier la logique métier
4. **Supprimer les commentaires TODO** après les avoir traités
5. **Commit après chaque fix** (6 commits au total)
6. **Architecture polymorphique** — les notifications doivent fonctionner pour Tattooer ET Piercer ($bookingRequest->bookable)
7. **Tester la compilation** — `php artisan route:list` sans erreur à la fin
