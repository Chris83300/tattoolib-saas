# 🔧 FIX FORMULAIRE DEMANDE CLIENT → PIERCER

Le formulaire de demande de réservation côté client est identique pour Tattooer et Piercer.
Il faut le rendre CONDITIONNEL selon le type d'artisan ciblé.

## AUDIT

```bash
# Trouver le formulaire de demande client
grep -rn "booking.*request\|demande\|book.*form" resources/views/client/ resources/views/livewire/client/ resources/views/marketplace/ --include="*.blade.php" -l | head -10

# Trouver le controller/composant qui gère la création
grep -rn "function.*store.*booking\|function.*create.*request\|function.*book" app/Http/Controllers/Client/ app/Http/Controllers/ClientController.php app/Livewire/Client/ --include="*.php" | head -10

# Trouver comment l'artisan est passé au formulaire
grep -rn "bookable_type\|artisan.*type\|tattooer_id\|piercer" resources/views/marketplace/show.blade.php | head -10
```

## FIX 1 — CHAMPS CONDITIONNELS : masquer tattoo, afficher piercing

Trouver le formulaire de demande et identifier les champs suivants à MASQUER pour un pierceur :

### CHAMPS À MASQUER si artisan est Piercer :
- **Taille du tattoo** (small/medium/large/full-sleeve etc.)
- **Emplacement/zone** du tatouage (bras, dos, jambe...)
- **Style** (blackwork, réaliste, japonais, etc.)
- **Budget** (fourchette de prix)

### CHAMPS À AJOUTER si artisan est Piercer :

```blade
@php
    $isPiercerBooking = ($artist ?? null) instanceof \App\Models\Piercer;
    $pricingGrid = $isPiercerBooking ? ($artist->pricing_grid ?? []) : [];
@endphp

@if ($isPiercerBooking)
    {{-- ═══ CHAMPS SPÉCIFIQUES PIERCING ═══ --}}
    
    {{-- Type de piercing avec tarif lié --}}
    <div>
        <label class="block text-sm font-semibold text-ivoire-text mb-2">Type de piercing *</label>
        <select name="piercing_type" required
            x-data="{ selected: '' }"
            x-on:change="selected = $event.target.value"
            class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau transition-colors">
            <option value="">-- Choisir le type de piercing --</option>
            @foreach ($pricingGrid as $pricing)
                @if (!empty($pricing['type']))
                    <option value="{{ $pricing['type'] }}">
                        {{ $pricing['type'] }}
                        @if (!empty($pricing['price']))
                            — {{ number_format($pricing['price'], 0) }}€
                        @endif
                    </option>
                @endif
            @endforeach
            <option value="autre">Autre (préciser ci-dessous)</option>
        </select>
    </div>

    {{-- Précision (optionnel) --}}
    <div>
        <label class="block text-sm font-semibold text-ivoire-text mb-2">Précisions (optionnel)</label>
        <input type="text" name="piercing_precision"
            placeholder="Ex : côté gauche, deuxième trou, bijou spécifique souhaité..."
            class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau transition-colors">
    </div>

    {{-- Demande spéciale --}}
    <div>
        <label class="block text-sm font-semibold text-ivoire-text mb-2">Demande spécifique (optionnel)</label>
        <textarea name="special_request" rows="3"
            placeholder="Ex : piercing intime/génital, piercing surface, projet multi-piercings, allergie aux métaux, première fois..."
            class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau transition-colors resize-none"></textarea>
        <p class="text-xs text-titane mt-1">Ces informations restent confidentielles entre vous et le pierceur.</p>
    </div>

@else
    {{-- ═══ CHAMPS TATTOO EXISTANTS (inchangés) ═══ --}}
    {{-- Garder ICI les champs actuels : taille, emplacement, style, budget --}}
@endif
```

Le principe : wrapper les champs tattoo dans un `@else` et les champs piercing dans le `@if ($isPiercerBooking)`.

NE PAS supprimer les champs tattoo. Les ENCAPSULER dans le @else pour qu'ils restent fonctionnels pour les tatoueurs.

## FIX 2 — CALENDRIER : dates non visibles + autoriser RDV le jour même

```bash
# Trouver le composant calendrier
grep -rn "calendar\|datepicker\|flatpickr\|date.*select\|preferred_date\|appointment.*date" resources/views/marketplace/show.blade.php resources/views/client/ resources/views/livewire/client/ --include="*.blade.php" | head -20

# Chercher la config du calendrier (min date)
grep -rn "minDate\|min_date\|disable.*past\|today\|startDate" resources/views/ --include="*.blade.php" --include="*.js" | head -10
grep -rn "minDate\|min_date\|disable.*past\|today" public/js/ resources/js/ --include="*.js" 2>/dev/null | head -10
```

### Problème A : Dates non visibles
Le calendrier est probablement là mais les dates ne sont pas visibles (problème CSS : texte blanc sur fond blanc, ou opacity 0, ou z-index caché). 

CHERCHER le CSS du calendrier et vérifier :
```bash
grep -rn "flatpickr\|calendar\|datepicker\|date-picker" resources/css/ resources/views/ --include="*.css" --include="*.blade.php" | grep -i "color\|bg\|opacity\|display\|visibility" | head -10
```

FIX probable : ajouter un thème sombre pour le datepicker ou forcer les couleurs :
```css
/* Si flatpickr */
.flatpickr-calendar {
    background: #1a1a2e !important; /* noir-profond */
    color: #f5f0eb !important; /* ivoire-text */
}
.flatpickr-day {
    color: #f5f0eb !important;
}
.flatpickr-day.selected {
    background: #c4956a !important; /* beige-peau */
    color: #0a0a14 !important;
}
.flatpickr-day:hover {
    background: #c4956a33 !important;
}
.flatpickr-months .flatpickr-month,
.flatpickr-current-month .flatpickr-monthDropdown-months,
.flatpickr-weekdays,
span.flatpickr-weekday {
    color: #f5f0eb !important;
    background: transparent !important;
}
```

Si ce n'est PAS flatpickr mais un autre composant, adapter les styles au composant trouvé.

### Problème B : Autoriser RDV le jour même

Trouver la configuration `minDate` et la changer :

```javascript
// AVANT (interdit aujourd'hui)
minDate: "tomorrow"
// ou
minDate: new Date().fp_incr(1)

// APRÈS (autorise aujourd'hui)
minDate: "today"
// ou
minDate: new Date()
```

Si c'est dans un composant Livewire :
```php
// AVANT
'minDate' => now()->addDay()->format('Y-m-d')

// APRÈS
'minDate' => now()->format('Y-m-d')
```

## FIX 3 — SAUVEGARDER les champs piercing dans BookingRequest

```bash
# Vérifier les colonnes de booking_requests
php artisan tinker --execute="echo implode(', ', Schema::getColumnListing('booking_requests'));"

# Chercher comment la demande est créée
grep -rn "BookingRequest::create\|->bookingRequests()->create\|->create.*booking" app/Http/Controllers/ app/Livewire/ --include="*.php" | head -10
```

Si les colonnes `piercing_type`, `piercing_precision`, `special_request` n'existent PAS dans `booking_requests` :

Vérifier d'abord s'il y a une colonne JSON pour les détails (ex: `details`, `metadata`, `extra_data`) :
```bash
grep -n "details\|metadata\|extra\|json\|additional" database/migrations/*booking_request* | head -10
```

**Option A** — S'il y a une colonne JSON `details` ou `metadata` : stocker dedans
```php
'details' => json_encode([
    'piercing_type' => $request->piercing_type,
    'piercing_precision' => $request->piercing_precision,
    'special_request' => $request->special_request,
])
```

**Option B** — S'il n'y a pas de colonne JSON : en créer une
```bash
php artisan make:migration add_piercing_fields_to_booking_requests --table=booking_requests
```
```php
$table->string('piercing_type')->nullable();
$table->text('piercing_precision')->nullable();
$table->text('special_request')->nullable();
```
Et ajouter dans `BookingRequest::$fillable`.

**Option C** (recommandée si la colonne `description` ou `message` existe) : stocker dans le champ existant
```php
// Construire le message avec les infos piercing
$description = "Type : {$request->piercing_type}";
if ($request->piercing_precision) $description .= "\nPrécisions : {$request->piercing_precision}";
if ($request->special_request) $description .= "\nDemande spécifique : {$request->special_request}";
```

Adapter selon la structure exacte de la table booking_requests.

```bash
git add -A && git commit -m "feat(booking): formulaire demande conditionnel piercer + calendrier fix + sauvegarde champs piercing"
```

## VÉRIFICATION

```bash
# 1. Le formulaire a les conditionnels ?
grep -c "isPiercerBooking\|isPiercer\|instanceof.*Piercer" resources/views/marketplace/show.blade.php
# Doit être > 0

# 2. Le calendrier autorise aujourd'hui ?
grep -rn "minDate\|min_date" resources/views/marketplace/ resources/views/client/ resources/js/ public/js/ --include="*.blade.php" --include="*.js" 2>/dev/null | head -5

# 3. Les champs piercing sont sauvegardés ?
grep -rn "piercing_type\|piercing_precision\|special_request" app/Http/Controllers/ app/Livewire/ app/Models/BookingRequest.php --include="*.php" | head -10

echo "=== FIX FORMULAIRE DEMANDE PIERCER TERMINÉ ==="
```
