# ⏰ FIX URGENT — Auto-complétion RDV 24h après la date
# Pour Claude Code — Ink&Pik SaaS
# Commit après chaque phase

## CONTEXTE

**Problème** : Une demande de RDV datant du 19 mars (il y a 5 jours) est toujours
en statut "confirmed" ou "deposit_paid" alors qu'elle devrait être auto-complétée.

**Règle métier** :
- **24h après la date du RDV** → le booking passe automatiquement en `completed`
  SI aucune action manuelle n'a été faite (ni complétion manuelle, ni contestation no-show)
- **Fenêtre de contestation** : l'artiste peut toujours signaler un no-show ou
  contester APRÈS l'auto-complétion (dans un délai raisonnable, ex: 7 jours)
- L'auto-complétion ne doit PAS déclencher de paiement automatique — elle change
  seulement le statut pour refléter la réalité

**Architecture existante** :
- Commande `app:check-completed-appointments` existe (cron horaire)
- `BookingRequest` a des colonnes : `status`, `appointment_date`, `preferred_date`,
  `completed_at`, `no_show_reported_at`
- Statuts possibles : pending, accepted, deposit_paid, confirmed, design_sent,
  completed, cancelled, no_show, expired
- `AppointmentCompletedNotification` existe

---

## PHASE 0 — AUDIT

```bash
echo "=== AUDIT AUTO-COMPLÉTION ==="

# 0A. Commande existante
find app/Console/Commands -name "*Completed*" -o -name "*completed*" -o -name "*CheckCompleted*" | head -5
cat app/Console/Commands/CheckCompletedAppointments.php 2>/dev/null

# 0B. Comment est-elle planifiée ?
grep -rn "check-completed\|CheckCompleted\|completed-appointments" routes/console.php app/Console/Kernel.php bootstrap/app.php 2>/dev/null | head -5

# 0C. BookingRequest — colonnes date + statut
php artisan tinker --execute="
  \$cols = Schema::getColumnListing('booking_requests');
  \$relevant = array_filter(\$cols, fn(\$c) => str_contains(\$c, 'date') || str_contains(\$c, 'status') || str_contains(\$c, 'completed') || str_contains(\$c, 'no_show') || str_contains(\$c, 'appointment'));
  echo implode(', ', \$relevant) . PHP_EOL;
"

# 0D. Le booking problématique (#8)
php artisan tinker --execute="
  \$br = \App\Models\BookingRequest::find(8);
  if (\$br) {
    echo 'status: ' . \$br->status . PHP_EOL;
    echo 'appointment_date: ' . (\$br->appointment_date ?? 'NULL') . PHP_EOL;
    echo 'preferred_date: ' . (\$br->preferred_date ?? 'NULL') . PHP_EOL;
    echo 'completed_at: ' . (\$br->completed_at ?? 'NULL') . PHP_EOL;
    echo 'no_show_reported_at: ' . (\$br->no_show_reported_at ?? 'NULL') . PHP_EOL;
    echo 'created_at: ' . \$br->created_at . PHP_EOL;
    echo 'bookable_type: ' . \$br->bookable_type . PHP_EOL;
  } else {
    echo 'BookingRequest #8 introuvable' . PHP_EOL;
  }
"

# 0E. TOUS les bookings qui devraient être auto-complétés
php artisan tinker --execute="
  use App\Models\BookingRequest;
  \$cutoff = now()->subHours(24);
  
  // Bookings avec date passée > 24h et pas encore completed/cancelled/no_show
  \$overdue = BookingRequest::whereIn('status', ['confirmed', 'deposit_paid', 'design_sent', 'accepted'])
    ->where(function(\$q) use (\$cutoff) {
      \$q->where('appointment_date', '<', \$cutoff)
         ->orWhere(function(\$sub) use (\$cutoff) {
           \$sub->whereNull('appointment_date')
                ->where('preferred_date', '<', \$cutoff);
         });
    })
    ->get(['id', 'status', 'appointment_date', 'preferred_date']);
  
  echo 'Bookings en retard (> 24h) : ' . \$overdue->count() . PHP_EOL;
  foreach (\$overdue as \$b) {
    \$date = \$b->appointment_date ?? \$b->preferred_date;
    echo '  #' . \$b->id . ' status=' . \$b->status . ' date=' . \$date . PHP_EOL;
  }
"

# 0F. Statuts enum
cat app/Enums/BookingRequestStatus.php 2>/dev/null | head -30
# Ou
grep -n "COMPLETED\|CONFIRMED\|NO_SHOW\|completed\|confirmed\|no_show" app/Enums/BookingRequestStatus.php 2>/dev/null | head -15

# 0G. Méthode complete dans le modèle ou service
grep -rn "function complete\|function markAsCompleted\|function autoComplete" app/Models/BookingRequest.php app/Services/BookingRequestService.php 2>/dev/null | head -10

# 0H. Notification existante
ls app/Notifications/AppointmentCompletedNotification.php 2>/dev/null && echo "Notification OK"

# 0I. No-show — comment ça marche
grep -rn "no.show\|noShow\|no_show\|reportNoShow" app/Models/BookingRequest.php app/Http/Controllers/Tattooer/TattooerAppointmentController.php app/Http/Controllers/Tattooer/TattooerBookingController.php 2>/dev/null | head -10

echo "=== FIN AUDIT ==="
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## PHASE 1 — CORRIGER LA COMMANDE AUTO-COMPLÉTION

Lire la commande existante en entier, puis la corriger/réécrire.

### Logique attendue :

```
Toutes les heures :
  1. Trouver les bookings avec statut IN (confirmed, deposit_paid, design_sent, accepted)
  2. ET date du RDV (appointment_date OU preferred_date) < now() - 24h
  3. ET completed_at IS NULL
  4. ET no_show_reported_at IS NULL (pas de contestation en cours)
  5. Pour chacun :
     a. Passer le statut à 'completed'
     b. Mettre completed_at = now()
     c. Ajouter une note "Auto-complété 24h après la date du RDV"
     d. Notifier le client (AppointmentCompletedNotification)
     e. Notifier l'artiste (optionnel — info)
     f. Logger l'action
```

```php
// app/Console/Commands/CheckCompletedAppointments.php
// RÉÉCRIRE la méthode handle() :

namespace App\Console\Commands;

use App\Models\BookingRequest;
use App\Notifications\AppointmentCompletedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckCompletedAppointments extends Command
{
    protected $signature = 'app:check-completed-appointments';
    protected $description = 'Auto-compléter les RDV dont la date est dépassée de 24h sans action manuelle';

    public function handle(): int
    {
        $this->info('=== Auto-complétion des RDV ===');

        $cutoff = now()->subHours(24);

        // Bookings éligibles à l'auto-complétion
        $overdueBookings = BookingRequest::query()
            ->whereIn('status', ['confirmed', 'deposit_paid', 'design_sent', 'accepted'])
            ->whereNull('completed_at')
            ->whereNull('no_show_reported_at')
            ->where(function ($q) use ($cutoff) {
                // appointment_date défini et dépassé de 24h
                $q->where(function ($sub) use ($cutoff) {
                    $sub->whereNotNull('appointment_date')
                        ->where('appointment_date', '<', $cutoff);
                })
                // OU pas de appointment_date mais preferred_date dépassé de 24h
                ->orWhere(function ($sub) use ($cutoff) {
                    $sub->whereNull('appointment_date')
                        ->whereNotNull('preferred_date')
                        ->where('preferred_date', '<', $cutoff);
                });
            })
            ->with(['client.user', 'bookable.user'])
            ->get();

        if ($overdueBookings->isEmpty()) {
            $this->info('Aucun RDV à auto-compléter.');
            return self::SUCCESS;
        }

        $completed = 0;

        foreach ($overdueBookings as $booking) {
            try {
                $appointmentDate = $booking->appointment_date ?? $booking->preferred_date;

                // Mettre à jour le statut
                $booking->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                    'completion_notes' => 'Auto-complété automatiquement 24h après la date du RDV (' 
                        . $appointmentDate->format('d/m/Y') . '). '
                        . 'Contestation possible pendant 7 jours.',
                ]);

                // Notifier le client
                if ($booking->client?->user) {
                    try {
                        $booking->client->user->notify(new AppointmentCompletedNotification($booking));
                    } catch (\Exception $e) {
                        Log::warning('[AutoComplete] Notification client échouée', [
                            'booking_id' => $booking->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }

                $completed++;

                Log::info('[AutoComplete] RDV auto-complété', [
                    'booking_id' => $booking->id,
                    'appointment_date' => $appointmentDate->toDateString(),
                    'hours_overdue' => round(now()->diffInHours($appointmentDate), 1),
                    'artist' => $booking->bookable?->user?->name,
                    'client' => $booking->client?->user?->name,
                ]);

                $this->line("  ✅ Booking #{$booking->id} — RDV du {$appointmentDate->format('d/m/Y')} — auto-complété");

            } catch (\Exception $e) {
                Log::error('[AutoComplete] Erreur', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
                $this->error("  ❌ Booking #{$booking->id} — {$e->getMessage()}");
            }
        }

        $this->info("Auto-complétés : {$completed} / {$overdueBookings->count()}");

        return self::SUCCESS;
    }
}
```

> ⚠️ ADAPTER les noms de colonnes selon les résultats de Phase 0.
> `completion_notes` peut ne pas exister — vérifier. Si absent, utiliser un champ
> existant (ex: `admin_notes`, `notes`) ou ajouter le champ.

### Vérifier que la colonne completion_notes existe

```bash
php artisan tinker --execute="
  echo Schema::hasColumn('booking_requests', 'completion_notes') ? 'completion_notes OK' : 'completion_notes ABSENT' . PHP_EOL;
  echo Schema::hasColumn('booking_requests', 'admin_notes') ? 'admin_notes OK' : 'admin_notes ABSENT' . PHP_EOL;
  echo Schema::hasColumn('booking_requests', 'notes') ? 'notes OK' : 'notes ABSENT' . PHP_EOL;
"
```

Si aucun champ de notes n'existe, ajouter `completion_notes` :

```bash
php artisan make:migration add_completion_notes_to_booking_requests
```

```php
public function up(): void
{
    Schema::table('booking_requests', function (Blueprint $table) {
        if (!Schema::hasColumn('booking_requests', 'completion_notes')) {
            $table->text('completion_notes')->nullable()->after('status');
        }
    });
}
```

```bash
php artisan migrate

git add -A && git commit -m "fix(auto-complete): réécrire commande check-completed-appointments — 24h après date RDV"
```

---

## PHASE 2 — FENÊTRE DE CONTESTATION (7 JOURS)

L'artiste doit pouvoir contester un auto-complété (signaler no-show)
pendant 7 jours après l'auto-complétion.

### 2A. Vérifier la méthode no-show existante

```bash
grep -B 5 -A 30 "function.*noShow\|function.*no_show\|function.*reportNoShow" \
  app/Http/Controllers/Tattooer/TattooerBookingController.php \
  app/Http/Controllers/Tattooer/TattooerAppointmentController.php 2>/dev/null | head -40
```

### 2B. Adapter pour permettre la contestation post-complétion

La méthode no-show existante refuse probablement de traiter un booking `completed`.
Il faut ajouter une exception pour les bookings auto-complétés récemment (< 7 jours) :

```php
// Dans le controller qui gère le no-show (TattooerBookingController ou TattooerAppointmentController)
// MODIFIER la validation du statut :

public function reportNoShow(BookingRequest $bookingRequest)
{
    $artisan = $this->artisan();

    // Vérifier ownership
    abort_unless(
        $bookingRequest->bookable_type === get_class($artisan)
        && $bookingRequest->bookable_id === $artisan->id,
        403
    );

    // ✅ MODIFIÉ : Permettre la contestation d'un auto-complété récent (< 7 jours)
    $isAutoCompleted = $bookingRequest->status === 'completed'
        && $bookingRequest->completed_at
        && $bookingRequest->completed_at->diffInDays(now()) <= 7
        && str_contains($bookingRequest->completion_notes ?? '', 'Auto-complété');

    $allowedStatuses = ['confirmed', 'deposit_paid', 'design_sent'];

    if ($isAutoCompleted) {
        // Contestation d'un auto-complété → autorisé
    } elseif (!in_array($bookingRequest->status, $allowedStatuses)) {
        return back()->with('error', 'Cette demande ne peut pas être signalée comme no-show.');
    }

    // Signaler le no-show
    $bookingRequest->update([
        'status' => 'no_show',
        'no_show_reported_at' => now(),
        'completion_notes' => ($bookingRequest->completion_notes ?? '')
            . "\nContestation no-show signalée le " . now()->format('d/m/Y à H:i')
            . " (dans la fenêtre de 7 jours).",
    ]);

    // Notifier le client + admin
    // ... (logique existante de notification no-show) ...

    Log::info('[NoShow] Contestation signalée', [
        'booking_id' => $bookingRequest->id,
        'was_auto_completed' => $isAutoCompleted,
    ]);

    return back()->with('success', 'No-show signalé. L\'administration va traiter votre contestation.');
}
```

### 2C. Afficher la fenêtre de contestation dans la vue

Dans la vue `request-show.blade.php`, ajouter un bandeau pour les bookings auto-complétés
avec le compte à rebours de contestation :

```blade
{{-- Dans request-show.blade.php, après le header --}}
@if ($bookingRequest->status === 'completed'
    && $bookingRequest->completed_at
    && str_contains($bookingRequest->completion_notes ?? '', 'Auto-complété'))

    @php
        $daysLeft = 7 - $bookingRequest->completed_at->diffInDays(now());
    @endphp

    @if ($daysLeft > 0)
        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl mb-4">
            <div class="flex items-start gap-3">
                <span class="text-xl">⏰</span>
                <div class="flex-1">
                    <p class="font-medium text-yellow-800 dark:text-yellow-200">
                        RDV auto-complété
                    </p>
                    <p class="text-sm text-yellow-600 dark:text-yellow-300 mt-1">
                        Ce RDV a été automatiquement marqué comme complété 24h après la date prévue.
                        Vous avez encore <strong>{{ $daysLeft }} jour{{ $daysLeft > 1 ? 's' : '' }}</strong>
                        pour contester (signaler un no-show).
                    </p>
                </div>

                {{-- Bouton contester --}}
                <form method="POST" action="{{ route($artisan->routePrefix() . '.requests.no-show', $bookingRequest) }}">
                    @csrf
                    <button type="submit"
                            onclick="return confirm('Signaler un no-show pour ce RDV ?')"
                            class="px-3 py-1.5 text-sm bg-rouge-alerte text-white rounded-lg hover:bg-red-700 transition">
                        Contester
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl mb-4">
            <p class="text-sm text-green-700 dark:text-green-300">
                ✅ RDV auto-complété — la fenêtre de contestation est terminée.
            </p>
        </div>
    @endif
@endif
```

```bash
git add -A && git commit -m "feat(auto-complete): fenêtre de contestation 7 jours post-auto-complétion + bandeau UI"
```

---

## PHASE 3 — EXÉCUTER MAINTENANT + VÉRIFIER

### 3A. Lancer la commande manuellement pour corriger les bookings en retard

```bash
php artisan app:check-completed-appointments
```

Cela devrait auto-compléter le booking #8 et tous les autres en retard.

### 3B. Vérifier

```bash
echo "=== VÉRIFICATION AUTO-COMPLÉTION ==="

# V1. Booking #8 corrigé
php artisan tinker --execute="
  \$br = \App\Models\BookingRequest::find(8);
  echo 'status: ' . \$br->status . PHP_EOL;
  echo 'completed_at: ' . (\$br->completed_at ?? 'NULL') . PHP_EOL;
  echo 'completion_notes: ' . (\$br->completion_notes ?? 'NULL') . PHP_EOL;
"

# V2. Plus de bookings en retard
php artisan tinker --execute="
  use App\Models\BookingRequest;
  \$cutoff = now()->subHours(24);
  \$overdue = BookingRequest::whereIn('status', ['confirmed', 'deposit_paid', 'design_sent', 'accepted'])
    ->where(function(\$q) use (\$cutoff) {
      \$q->where('appointment_date', '<', \$cutoff)
         ->orWhere(function(\$sub) use (\$cutoff) {
           \$sub->whereNull('appointment_date')->where('preferred_date', '<', \$cutoff);
         });
    })
    ->whereNull('completed_at')
    ->whereNull('no_show_reported_at')
    ->count();
  echo 'Bookings encore en retard : ' . \$overdue . ' (doit être 0)' . PHP_EOL;
"

# V3. Commande cron bien planifiée
php artisan schedule:list 2>&1 | grep "check-completed"

# V4. Compilation
php artisan route:cache 2>&1 | head -3

echo "=== AUTO-COMPLÉTION TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — les noms de colonnes date dans booking_requests varient
2. **24h après la date** — pas 24h après la création, après la DATE DU RDV
3. **appointment_date OU preferred_date** — vérifier lequel est utilisé (ou les deux)
4. **Fenêtre de contestation 7 jours** — l'artiste peut signaler un no-show après auto-complétion
5. **Pas de paiement automatique** — l'auto-complétion change seulement le statut
6. **Ne PAS toucher les bookings déjà cancelled, no_show, ou completed manuellement**
7. **Logger chaque auto-complétion** — traçabilité importante
8. **La commande tourne toutes les heures** — vérifier le schedule
9. **Commit après chaque phase** (3 commits)
