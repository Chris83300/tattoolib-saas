# 📄 P1.2 — EXPORT PDF (dompdf)
# Pour Claude Code — Fiches clients, consentements, traçabilité, factures
# Commit après chaque phase

## CONTEXTE

L'audit global a identifié l'absence totale de génération PDF comme bloquant P1 :
- dompdf/snappy absent de composer.json
- Les artistes tatoueurs/pierceurs ont l'**obligation légale** de fournir des documents papier (fiches soins, traçabilité, consentements)
- Les artistes indépendants ont besoin de factures/reçus pour leur comptabilité

Stack : Laravel 12, Livewire 3.7, TailwindCSS v4, Spatie Media Library.

### DOCUMENTS PDF À GÉNÉRER

| # | Document | Qui le génère | Qui le reçoit | Obligation légale |
|---|----------|---------------|---------------|-------------------|
| 1 | **Fiche de soins post-tatouage** | Artiste | Client | Oui (art. R.1311-12 CSP) |
| 2 | **Formulaire de consentement** | Artiste | Client (à signer) | Oui (art. R.1311-12 CSP) |
| 3 | **Consentement parental** | Artiste | Parent/tuteur (à signer) | Oui (art. R.1311-11 CSP) |
| 4 | **Fiche de traçabilité** | Artiste | Archivage (contrôle ARS) | Oui (traçabilité encres/aiguilles) |
| 5 | **Récapitulatif fiche client** | Artiste/Studio | Archivage interne | Recommandé |
| 6 | **Reçu de prestation** | Artiste | Client | Oui (obligation facturation) |

---

## PHASE 0 — AUDIT

```bash
echo "=== AUDIT PDF ==="

# 0A. dompdf dans composer
grep -n "dompdf\|snappy\|wkhtmltopdf\|barryvdh.*pdf\|tcpdf" composer.json | head -5

# 0B. Models concernés — structure
echo "--- ClientCareSheet ---"
grep -n "fillable\|function \|class " app/Models/ClientCareSheet.php | head -20

echo "--- ClientConsentForm ---"
grep -n "fillable\|function \|class " app/Models/ClientConsentForm.php | head -20

echo "--- ParentalConsentForm ---"
grep -n "fillable\|function \|class " app/Models/ParentalConsentForm.php | head -20

echo "--- TraceabilityRecord ---"
grep -n "fillable\|function \|class " app/Models/TraceabilityRecord.php | head -20

# 0C. Colonnes des tables
php artisan tinker --execute="
  \$tables = ['client_care_sheets', 'client_consent_forms', 'parental_consent_forms', 'traceability_records', 'traceability_needles', 'traceability_inks'];
  foreach(\$tables as \$t) {
    if (Schema::hasTable(\$t)) {
      echo \$t . ': ' . implode(', ', Schema::getColumnListing(\$t)) . PHP_EOL;
    } else {
      echo \$t . ': TABLE ABSENTE' . PHP_EOL;
    }
  }
"

# 0D. Routes existantes pour ces features
php artisan route:list 2>&1 | grep -i "care\|consent\|trace\|tracab\|invoice\|facture\|receipt\|pdf\|export" | head -20

# 0E. Controllers qui gèrent ces features
grep -rn "CareSheet\|ConsentForm\|TraceabilityRecord\|ParentalConsent" app/Http/Controllers/ app/Livewire/ --include="*.php" | head -20

# 0F. Vues existantes pour fiches clients
find resources/views -name "*care*" -o -name "*consent*" -o -name "*trace*" -o -name "*tracab*" 2>/dev/null | sort

# 0G. Config inkpik (pour les infos de la plateforme dans les PDFs)
cat config/inkpik.php 2>/dev/null | head -30

# 0H. App name et locale
grep "APP_NAME\|APP_URL\|APP_LOCALE" .env | head -5
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## PHASE 1 — INSTALLER DOMPDF

```bash
composer require barryvdh/laravel-dompdf
```

Publier la config :
```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

Vérifier `config/dompdf.php` et ajuster :
```php
// config/dompdf.php — ajustements recommandés
'default_paper_size' => 'a4',
'default_font' => 'sans-serif',
'enable_remote' => true, // Pour charger les images (logos, etc.)
```

```bash
git add -A && git commit -m "feat(pdf): installer barryvdh/laravel-dompdf"
```

---

## PHASE 2 — SERVICE PDF

Créer un service centralisé qui gère la génération de tous les PDFs :

```php
// app/Services/PdfExportService.php
namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ClientCareSheet;
use App\Models\ClientConsentForm;
use App\Models\ParentalConsentForm;
use App\Models\TraceabilityRecord;
use App\Models\BookingRequest;
use Illuminate\Support\Facades\Storage;

class PdfExportService
{
    /**
     * Fiche de soins post-tatouage/piercing.
     * Obligation légale : art. R.1311-12 du Code de la Santé Publique.
     */
    public function generateCareSheet(ClientCareSheet $careSheet): \Barryvdh\DomPDF\PDF
    {
        $careSheet->load(['client.user', 'bookable.user']);
        
        return Pdf::loadView('pdf.care-sheet', [
            'careSheet' => $careSheet,
            'artisan' => $careSheet->bookable,
            'client' => $careSheet->client,
            'generatedAt' => now(),
        ])->setPaper('a4');
    }

    /**
     * Formulaire de consentement client.
     * Obligation légale : information préalable du futur tatoué.
     */
    public function generateConsentForm(ClientConsentForm $consentForm): \Barryvdh\DomPDF\PDF
    {
        $consentForm->load(['client.user', 'bookable.user']);

        return Pdf::loadView('pdf.consent-form', [
            'consentForm' => $consentForm,
            'artisan' => $consentForm->bookable,
            'client' => $consentForm->client,
            'generatedAt' => now(),
        ])->setPaper('a4');
    }

    /**
     * Formulaire de consentement parental (mineurs).
     * Obligation légale : art. R.1311-11 du CSP.
     */
    public function generateParentalConsent(ParentalConsentForm $parentalConsent): \Barryvdh\DomPDF\PDF
    {
        $parentalConsent->load(['client.user', 'bookable.user']);

        return Pdf::loadView('pdf.parental-consent', [
            'parentalConsent' => $parentalConsent,
            'artisan' => $parentalConsent->bookable,
            'client' => $parentalConsent->client,
            'generatedAt' => now(),
        ])->setPaper('a4');
    }

    /**
     * Fiche de traçabilité (encres, aiguilles, lots).
     * Obligation légale : traçabilité des produits et matériels.
     */
    public function generateTraceabilityRecord(TraceabilityRecord $record): \Barryvdh\DomPDF\PDF
    {
        $record->load(['client.user', 'bookable.user', 'needles', 'inks']);

        return Pdf::loadView('pdf.traceability-record', [
            'record' => $record,
            'artisan' => $record->bookable,
            'client' => $record->client,
            'needles' => $record->needles,
            'inks' => $record->inks,
            'generatedAt' => now(),
        ])->setPaper('a4');
    }

    /**
     * Récapitulatif fiche client complète (care sheet + consent + traçabilité).
     */
    public function generateClientSummary($client, $artisan): \Barryvdh\DomPDF\PDF
    {
        // Adapter les queries selon la structure réelle
        // Si artiste studio → scope par studio_id, sinon par bookable
        $careSheets = ClientCareSheet::forArtisan($artisan)
            ->where('client_id', $client->id)
            ->latest()
            ->get();

        $consentForms = ClientConsentForm::forArtisan($artisan)
            ->where('client_id', $client->id)
            ->latest()
            ->get();

        $traceRecords = TraceabilityRecord::forArtisan($artisan)
            ->where('client_id', $client->id)
            ->with(['needles', 'inks'])
            ->latest()
            ->get();

        return Pdf::loadView('pdf.client-summary', [
            'client' => $client,
            'artisan' => $artisan,
            'careSheets' => $careSheets,
            'consentForms' => $consentForms,
            'traceRecords' => $traceRecords,
            'generatedAt' => now(),
        ])->setPaper('a4');
    }

    /**
     * Reçu de prestation (mini-facture).
     */
    public function generateReceipt(BookingRequest $booking): \Barryvdh\DomPDF\PDF
    {
        $booking->load(['client.user', 'bookable.user', 'bookable.studio']);

        return Pdf::loadView('pdf.receipt', [
            'booking' => $booking,
            'artisan' => $booking->bookable,
            'client' => $booking->client,
            'generatedAt' => now(),
        ])->setPaper('a4');
    }
}
```

IMPORTANT : 
- Les noms de relations (`bookable`, `client`, `needles`, `inks`) doivent être adaptés aux vrais noms trouvés lors de l'audit Phase 0
- Le scope `forArtisan()` a été créé lors du fix P0.6 / Fix Studio 3 — vérifier qu'il existe
- Si les models utilisent des relations polymorphiques (bookable_type/bookable_id), adapter en conséquence

```bash
git add -A && git commit -m "feat(pdf): PdfExportService — 6 types de documents"
```

---

## PHASE 3 — TEMPLATES PDF

Créer un layout de base pour tous les PDFs, puis chaque template.

IMPORTANT pour dompdf :
- Pas de TailwindCSS (pas compilé dans le contexte PDF) → utiliser du CSS inline ou un `<style>` classique
- Pas de composants Livewire/Alpine
- Images en base64 ou URL absolue (enable_remote = true)
- Police par défaut (pas de @font-face complexe)

### 3A. Layout PDF de base

```bash
mkdir -p resources/views/pdf
```

```blade
{{-- resources/views/pdf/layout.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title') — Ink&Pik</title>
    <style>
        /* Reset & base */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1a1a1a;
            padding: 30px 40px;
        }

        /* Header */
        .pdf-header {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 2px solid #C97435;
            padding-bottom: 15px;
        }
        .pdf-header-left {
            display: table-cell;
            vertical-align: middle;
            width: 60%;
        }
        .pdf-header-right {
            display: table-cell;
            vertical-align: middle;
            width: 40%;
            text-align: right;
        }
        .pdf-logo {
            font-size: 22px;
            font-weight: bold;
            color: #C97435;
            letter-spacing: 1px;
        }
        .pdf-logo span { color: #1a1a1a; }
        .pdf-subtitle {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }
        .pdf-doc-type {
            font-size: 14px;
            font-weight: bold;
            color: #1a1a1a;
        }
        .pdf-doc-date {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }
        .pdf-doc-ref {
            font-size: 9px;
            color: #999;
        }

        /* Sections */
        h2 {
            font-size: 13px;
            color: #C97435;
            border-bottom: 1px solid #e0d5c8;
            padding-bottom: 5px;
            margin: 20px 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        h3 {
            font-size: 11px;
            color: #333;
            margin: 12px 0 6px 0;
        }

        /* Info blocks */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }
        .info-label {
            font-size: 9px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .info-value {
            font-size: 11px;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th {
            background-color: #f5f0eb;
            color: #333;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #d4c9bc;
        }
        td {
            padding: 7px 10px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }
        tr:nth-child(even) td { background-color: #fafaf8; }

        /* Signature block */
        .signature-block {
            margin-top: 30px;
            display: table;
            width: 100%;
        }
        .signature-col {
            display: table-cell;
            width: 45%;
            vertical-align: top;
        }
        .signature-spacer {
            display: table-cell;
            width: 10%;
        }
        .signature-line {
            border-bottom: 1px solid #999;
            height: 60px;
            margin-top: 10px;
        }
        .signature-label {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }

        /* Footer */
        .pdf-footer {
            position: fixed;
            bottom: 20px;
            left: 40px;
            right: 40px;
            border-top: 1px solid #e0d5c8;
            padding-top: 8px;
            font-size: 8px;
            color: #999;
            text-align: center;
        }

        /* Alert box */
        .alert-box {
            background-color: #fff8f0;
            border: 1px solid #C97435;
            border-radius: 4px;
            padding: 10px 12px;
            margin: 10px 0;
            font-size: 10px;
        }
        .alert-box strong { color: #C97435; }

        /* Checklist */
        .checklist { list-style: none; }
        .checklist li {
            padding: 4px 0;
            padding-left: 18px;
            position: relative;
            font-size: 10px;
        }
        .checklist li::before {
            content: "☐";
            position: absolute;
            left: 0;
            color: #C97435;
        }

        /* Utils */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-small { font-size: 9px; }
        .text-muted { color: #888; }
        .mt-10 { margin-top: 10px; }
        .mt-20 { margin-top: 20px; }
        .mb-10 { margin-bottom: 10px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="pdf-header">
        <div class="pdf-header-left">
            <div class="pdf-logo">Ink<span>&</span>Pik</div>
            <div class="pdf-subtitle">Plateforme de mise en relation — Art corporel professionnel</div>
        </div>
        <div class="pdf-header-right">
            <div class="pdf-doc-type">@yield('doc-type')</div>
            <div class="pdf-doc-date">@yield('doc-date', now()->format('d/m/Y à H:i'))</div>
            <div class="pdf-doc-ref">@yield('doc-ref', '')</div>
        </div>
    </div>

    {{-- Content --}}
    @yield('content')

    {{-- Footer --}}
    <div class="pdf-footer">
        Ink&Pik — [Raison sociale] — SIRET : [SIRET] — {{ config('app.url') }}
        <br>
        Document généré le {{ now()->format('d/m/Y à H:i') }} — Ce document ne constitue pas une facture au sens fiscal du terme.
    </div>
</body>
</html>
```

### 3B. Fiche de soins post-tatouage

```blade
{{-- resources/views/pdf/care-sheet.blade.php --}}
@extends('pdf.layout')

@section('title', 'Fiche de soins')
@section('doc-type', 'Fiche de soins post-prestation')
@section('doc-date', $generatedAt->format('d/m/Y'))
@section('doc-ref', 'REF-CS-' . str_pad($careSheet->id ?? '0', 6, '0', STR_PAD_LEFT))

@section('content')

<div class="alert-box">
    <strong>⚕ Obligation légale :</strong> Ce document est remis conformément à l'article R.1311-12 du Code de la Santé Publique.
    Il détaille les précautions à respecter après la réalisation de la prestation.
</div>

<h2>Informations du client</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Nom complet</div>
        <div class="info-value">{{ $client?->user?->name ?? '—' }}</div>
        <div class="info-label">Date de naissance</div>
        <div class="info-value">{{ $client?->date_of_birth?->format('d/m/Y') ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Email</div>
        <div class="info-value">{{ $client?->user?->email ?? '—' }}</div>
        <div class="info-label">Téléphone</div>
        <div class="info-value">{{ $client?->user?->phone ?? $client?->phone ?? '—' }}</div>
    </div>
</div>

<h2>Informations de l'artiste</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Artiste</div>
        <div class="info-value">{{ $artisan?->user?->name ?? '—' }}</div>
        <div class="info-label">SIRET</div>
        <div class="info-value">{{ $artisan?->siret ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Studio</div>
        <div class="info-value">{{ $artisan?->studio?->name ?? 'Indépendant' }}</div>
        <div class="info-label">N° déclaration ARS</div>
        <div class="info-value">{{ $artisan?->ars_declaration_number ?? '—' }}</div>
    </div>
</div>

<h2>Détail de la prestation</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Date de la prestation</div>
        <div class="info-value">{{ $careSheet->procedure_date?->format('d/m/Y') ?? $careSheet->created_at?->format('d/m/Y') ?? '—' }}</div>
        <div class="info-label">Zone corporelle</div>
        <div class="info-value">{{ $careSheet->body_area ?? $careSheet->zone ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Type de prestation</div>
        <div class="info-value">{{ $careSheet->procedure_type ?? ($artisan?->isPiercer() ? 'Piercing' : 'Tatouage') }}</div>
        <div class="info-label">Description</div>
        <div class="info-value">{{ $careSheet->description ?? '—' }}</div>
    </div>
</div>

@if ($careSheet->allergies || $careSheet->medical_notes)
<h2>Informations médicales déclarées</h2>
<div class="alert-box">
    @if ($careSheet->allergies)
        <strong>Allergies :</strong> {{ $careSheet->allergies }}<br>
    @endif
    @if ($careSheet->medical_notes)
        <strong>Notes médicales :</strong> {{ $careSheet->medical_notes }}
    @endif
</div>
@endif

<h2>Consignes de soins post-prestation</h2>

<h3>Tatouage — Soins à respecter</h3>
<ul class="checklist">
    <li>Garder le pansement initial pendant 2 à 4 heures minimum</li>
    <li>Laver délicatement la zone à l'eau tiède et au savon doux (pH neutre, sans parfum)</li>
    <li>Sécher en tamponnant avec un papier absorbant propre (ne pas frotter)</li>
    <li>Appliquer une fine couche de crème cicatrisante (type Bepanthen, Cicaplast ou équivalent) 2 à 3 fois par jour</li>
    <li>Ne pas gratter, ne pas arracher les croûtes ou peaux mortes</li>
    <li>Éviter les bains (piscine, mer, baignoire) pendant 3 à 4 semaines</li>
    <li>Éviter l'exposition directe au soleil pendant au moins 4 semaines</li>
    <li>Porter des vêtements amples et en coton sur la zone tatouée</li>
    <li>Ne pas appliquer d'alcool, de peroxyde ou de produits agressifs sur la zone</li>
    <li>Surveiller tout signe d'infection (rougeur excessive, gonflement, pus, fièvre) et consulter un médecin si nécessaire</li>
</ul>

<h3>Piercing — Soins à respecter</h3>
<ul class="checklist">
    <li>Nettoyer le piercing 2 fois par jour avec une solution saline stérile</li>
    <li>Ne pas toucher le bijou avec les mains sales</li>
    <li>Ne pas retirer ni changer le bijou pendant la période de cicatrisation</li>
    <li>Éviter les produits alcoolisés ou antiseptiques agressifs</li>
    <li>Surveiller tout signe d'infection et consulter un médecin si nécessaire</li>
</ul>

<div class="alert-box mt-20">
    <strong>⚠ Important :</strong> En cas de réaction anormale (douleur persistante, gonflement excessif, écoulement purulent, fièvre),
    consultez immédiatement un médecin ou rendez-vous aux urgences. Vous pouvez également signaler tout effet indésirable
    sur le portail national des signalements : <strong>signalement.social-sante.gouv.fr</strong>
</div>

<div class="signature-block mt-20">
    <div class="signature-col">
        <strong>L'artiste :</strong>
        <div class="signature-line"></div>
        <div class="signature-label">Signature</div>
    </div>
    <div class="signature-spacer"></div>
    <div class="signature-col">
        <strong>Le client :</strong>
        <div class="signature-line"></div>
        <div class="signature-label">Signature (lu et approuvé)</div>
    </div>
</div>

@endsection
```

IMPORTANT : Les noms de colonnes (`body_area`, `zone`, `procedure_type`, `allergies`, `medical_notes`, etc.) sont indicatifs. Adapter aux vrais noms trouvés lors de l'audit Phase 0.

### 3C. Formulaire de consentement

```blade
{{-- resources/views/pdf/consent-form.blade.php --}}
@extends('pdf.layout')

@section('title', 'Consentement éclairé')
@section('doc-type', 'Formulaire de consentement éclairé')
@section('doc-date', $generatedAt->format('d/m/Y'))
@section('doc-ref', 'REF-CF-' . str_pad($consentForm->id ?? '0', 6, '0', STR_PAD_LEFT))

@section('content')

<div class="alert-box">
    <strong>⚕ Obligation légale :</strong> Conformément à l'article R.1311-12 du Code de la Santé Publique,
    le client doit être informé des risques auxquels il s'expose avant la réalisation de l'acte.
</div>

<h2>Identité du client</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Nom complet</div>
        <div class="info-value">{{ $client?->user?->name ?? '—' }}</div>
        <div class="info-label">Date de naissance</div>
        <div class="info-value">{{ $client?->date_of_birth?->format('d/m/Y') ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Adresse</div>
        <div class="info-value">{{ $client?->address ?? $client?->user?->address ?? '—' }}</div>
        <div class="info-label">Téléphone</div>
        <div class="info-value">{{ $client?->user?->phone ?? $client?->phone ?? '—' }}</div>
    </div>
</div>

<h2>Identité du professionnel</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Artiste</div>
        <div class="info-value">{{ $artisan?->user?->name ?? '—' }}</div>
        <div class="info-label">SIRET</div>
        <div class="info-value">{{ $artisan?->siret ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Studio</div>
        <div class="info-value">{{ $artisan?->studio?->name ?? 'Indépendant' }}</div>
        <div class="info-label">Certification hygiène ARS</div>
        <div class="info-value">{{ $artisan?->hygiene_certificate_number ?? '—' }} (valide jusqu'au {{ $artisan?->hygiene_certificate_expires_at?->format('d/m/Y') ?? '—' }})</div>
    </div>
</div>

<h2>Information sur les risques</h2>

<p>Je soussigné(e) <strong>{{ $client?->user?->name ?? '________________' }}</strong> déclare avoir été informé(e) par le professionnel des éléments suivants :</p>

<h3>Risques liés au tatouage par effraction cutanée</h3>
<ul>
    <li>Risque d'infection bactérienne locale ou généralisée (en cas de non-respect des consignes d'hygiène)</li>
    <li>Risque de transmission d'agents infectieux (hépatites B et C, VIH) en cas de défaut de stérilisation du matériel</li>
    <li>Risque de réaction allergique aux encres de tatouage</li>
    <li>Risque de cicatrisation hypertrophique ou chéloïdienne</li>
    <li>Le tatouage est un acte permanent dont le retrait nécessite des traitements coûteux (laser) sans garantie de résultat total</li>
</ul>

<h3>Risques liés au piercing</h3>
<ul>
    <li>Risque d'infection locale</li>
    <li>Risque de rejet du bijou par l'organisme</li>
    <li>Risque de cicatrisation hypertrophique ou chéloïdienne</li>
    <li>Risque de réaction allergique au métal du bijou</li>
</ul>

<h3>Contre-indications déclarées</h3>

<p>Le client déclare :</p>
<ul class="checklist">
    <li>Ne pas être sous traitement anticoagulant</li>
    <li>Ne pas présenter de maladie de peau sur la zone concernée</li>
    <li>Ne pas être atteint d'une maladie immunodépressive</li>
    <li>Ne pas être enceinte ou en période d'allaitement (pour le tatouage)</li>
    <li>Ne pas avoir d'allergie connue aux encres ou métaux utilisés</li>
</ul>

@if ($consentForm->declared_conditions)
<div class="alert-box">
    <strong>Conditions déclarées par le client :</strong> {{ $consentForm->declared_conditions }}
</div>
@endif

<h2>Consentement</h2>

<p>Je soussigné(e) <strong>{{ $client?->user?->name ?? '________________' }}</strong>,</p>
<ul>
    <li>Déclare avoir été informé(e) des risques ci-dessus et avoir pu poser toutes mes questions</li>
    <li>Déclare avoir répondu sincèrement aux questions relatives à mon état de santé</li>
    <li>Consens librement à la réalisation de la prestation décrite</li>
    <li>M'engage à respecter les consignes de soins post-prestation qui me seront remises</li>
</ul>

<div class="info-grid mt-20">
    <div class="info-col">
        <div class="info-label">Prestation prévue</div>
        <div class="info-value">{{ $consentForm->procedure_description ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Zone corporelle</div>
        <div class="info-value">{{ $consentForm->body_area ?? '—' }}</div>
    </div>
</div>

<div class="signature-block mt-20">
    <div class="signature-col">
        <strong>Le professionnel :</strong>
        <div class="signature-line"></div>
        <div class="signature-label">Date et signature</div>
    </div>
    <div class="signature-spacer"></div>
    <div class="signature-col">
        <strong>Le client :</strong>
        <div class="signature-line"></div>
        <div class="signature-label">Date, signature et mention « lu et approuvé »</div>
    </div>
</div>

@endsection
```

### 3D. Consentement parental

```blade
{{-- resources/views/pdf/parental-consent.blade.php --}}
@extends('pdf.layout')

@section('title', 'Consentement parental')
@section('doc-type', 'Autorisation parentale — Mineur')
@section('doc-date', $generatedAt->format('d/m/Y'))
@section('doc-ref', 'REF-PC-' . str_pad($parentalConsent->id ?? '0', 6, '0', STR_PAD_LEFT))

@section('content')

<div class="alert-box">
    <strong>⚕ Obligation légale :</strong> Conformément à l'article R.1311-11 du Code de la Santé Publique,
    il est interdit de réaliser un tatouage sur une personne mineure sans le consentement écrit
    d'une personne titulaire de l'autorité parentale.
</div>

<h2>Identité du mineur</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Nom complet du mineur</div>
        <div class="info-value">{{ $parentalConsent->minor_name ?? $client?->user?->name ?? '—' }}</div>
        <div class="info-label">Date de naissance</div>
        <div class="info-value">{{ $parentalConsent->minor_birth_date?->format('d/m/Y') ?? $client?->date_of_birth?->format('d/m/Y') ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Âge</div>
        <div class="info-value">{{ $parentalConsent->minor_birth_date ? $parentalConsent->minor_birth_date->age . ' ans' : '—' }}</div>
    </div>
</div>

<h2>Identité du représentant légal</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Nom complet</div>
        <div class="info-value">{{ $parentalConsent->parent_name ?? '________________' }}</div>
        <div class="info-label">Lien de parenté</div>
        <div class="info-value">{{ $parentalConsent->relationship ?? '________________' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Adresse</div>
        <div class="info-value">{{ $parentalConsent->parent_address ?? '________________' }}</div>
        <div class="info-label">Téléphone</div>
        <div class="info-value">{{ $parentalConsent->parent_phone ?? '________________' }}</div>
    </div>
</div>

<h2>Identité du professionnel</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Artiste</div>
        <div class="info-value">{{ $artisan?->user?->name ?? '—' }}</div>
        <div class="info-label">SIRET</div>
        <div class="info-value">{{ $artisan?->siret ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Studio</div>
        <div class="info-value">{{ $artisan?->studio?->name ?? 'Indépendant' }}</div>
    </div>
</div>

<h2>Prestation autorisée</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Type</div>
        <div class="info-value">{{ $parentalConsent->procedure_type ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Zone corporelle</div>
        <div class="info-value">{{ $parentalConsent->body_area ?? '—' }}</div>
    </div>
</div>

<h2>Déclaration et autorisation</h2>

<p>Je soussigné(e) <strong>{{ $parentalConsent->parent_name ?? '________________' }}</strong>,
agissant en qualité de {{ $parentalConsent->relationship ?? 'représentant légal' }}
du mineur <strong>{{ $parentalConsent->minor_name ?? '________________' }}</strong> :</p>

<ul>
    <li>Déclare avoir été informé(e) des risques liés à la prestation envisagée</li>
    <li>Déclare avoir lu le formulaire de consentement éclairé associé</li>
    <li>Autorise expressément le professionnel ci-dessus désigné à réaliser la prestation décrite sur mon enfant mineur</li>
    <li>M'engage à veiller au respect des consignes de soins post-prestation</li>
</ul>

<p class="text-small text-muted mt-10">
    Pièce d'identité du représentant légal présentée : ☐ CNI ☐ Passeport ☐ Autre : ________
</p>

<div class="signature-block mt-20">
    <div class="signature-col">
        <strong>Le représentant légal :</strong>
        <div class="signature-line"></div>
        <div class="signature-label">Date, signature et mention « lu et approuvé »</div>
    </div>
    <div class="signature-spacer"></div>
    <div class="signature-col">
        <strong>Le professionnel :</strong>
        <div class="signature-line"></div>
        <div class="signature-label">Date et signature</div>
    </div>
</div>

@endsection
```

### 3E. Fiche de traçabilité

```blade
{{-- resources/views/pdf/traceability-record.blade.php --}}
@extends('pdf.layout')

@section('title', 'Traçabilité')
@section('doc-type', 'Fiche de traçabilité')
@section('doc-date', $generatedAt->format('d/m/Y'))
@section('doc-ref', 'REF-TR-' . str_pad($record->id ?? '0', 6, '0', STR_PAD_LEFT))

@section('content')

<div class="alert-box">
    <strong>⚕ Obligation légale :</strong> La traçabilité des produits et matériels utilisés est obligatoire
    conformément au Code de la Santé Publique et à la norme NF EN 17169. Ce document doit être conservé
    <strong>10 ans minimum</strong>.
</div>

<h2>Client</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Nom</div>
        <div class="info-value">{{ $client?->user?->name ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Date de naissance</div>
        <div class="info-value">{{ $client?->date_of_birth?->format('d/m/Y') ?? '—' }}</div>
    </div>
</div>

<h2>Professionnel</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Artiste</div>
        <div class="info-value">{{ $artisan?->user?->name ?? '—' }}</div>
        <div class="info-label">SIRET</div>
        <div class="info-value">{{ $artisan?->siret ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Studio</div>
        <div class="info-value">{{ $artisan?->studio?->name ?? 'Indépendant' }}</div>
        <div class="info-label">Certification hygiène</div>
        <div class="info-value">{{ $artisan?->hygiene_certificate_number ?? '—' }}</div>
    </div>
</div>

<h2>Acte réalisé</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Date de l'acte</div>
        <div class="info-value">{{ $record->performed_at?->format('d/m/Y H:i') ?? $record->created_at?->format('d/m/Y H:i') ?? '—' }}</div>
        <div class="info-label">Zone corporelle</div>
        <div class="info-value">{{ $record->body_area ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Type d'acte</div>
        <div class="info-value">{{ $record->procedure_type ?? '—' }}</div>
        <div class="info-label">Durée</div>
        <div class="info-value">{{ $record->duration ?? '—' }}</div>
    </div>
</div>

@if ($record->notes)
<div class="info-label">Notes</div>
<div class="info-value">{{ $record->notes }}</div>
@endif

<h2>Aiguilles / Cartouches utilisées</h2>

@if ($needles && $needles->count() > 0)
<table>
    <thead>
        <tr>
            <th>Marque</th>
            <th>Référence</th>
            <th>N° de lot</th>
            <th>Date péremption</th>
            <th>Quantité</th>
            <th>Stérilisation</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($needles as $needle)
        <tr>
            <td>{{ $needle->brand ?? '—' }}</td>
            <td>{{ $needle->reference ?? '—' }}</td>
            <td>{{ $needle->batch_number ?? $needle->lot_number ?? '—' }}</td>
            <td>{{ $needle->expiry_date?->format('d/m/Y') ?? '—' }}</td>
            <td>{{ $needle->quantity ?? '—' }}</td>
            <td>{{ $needle->sterilization_method ?? 'Usage unique' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="text-muted">Aucune aiguille enregistrée.</p>
@endif

<h2>Encres utilisées</h2>

@if ($inks && $inks->count() > 0)
<table>
    <thead>
        <tr>
            <th>Marque</th>
            <th>Couleur</th>
            <th>Référence</th>
            <th>N° de lot</th>
            <th>Date péremption</th>
            <th>Conforme REACH</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($inks as $ink)
        <tr>
            <td>{{ $ink->brand ?? '—' }}</td>
            <td>{{ $ink->color ?? '—' }}</td>
            <td>{{ $ink->reference ?? '—' }}</td>
            <td>{{ $ink->batch_number ?? $ink->lot_number ?? '—' }}</td>
            <td>{{ $ink->expiry_date?->format('d/m/Y') ?? '—' }}</td>
            <td>{{ $ink->reach_compliant ? '✓ Oui' : '✗ Non' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="text-muted">Aucune encre enregistrée.</p>
@endif

<div class="signature-block mt-20">
    <div class="signature-col">
        <strong>Le professionnel :</strong>
        <div class="signature-line"></div>
        <div class="signature-label">Date et signature</div>
    </div>
    <div class="signature-spacer"></div>
    <div class="signature-col">
        <strong>Le client :</strong>
        <div class="signature-line"></div>
        <div class="signature-label">Date et signature</div>
    </div>
</div>

@endsection
```

### 3F. Reçu de prestation

```blade
{{-- resources/views/pdf/receipt.blade.php --}}
@extends('pdf.layout')

@section('title', 'Reçu de prestation')
@section('doc-type', 'Reçu de prestation')
@section('doc-date', $generatedAt->format('d/m/Y'))
@section('doc-ref', 'REF-RC-' . str_pad($booking->id ?? '0', 6, '0', STR_PAD_LEFT))

@section('content')

<h2>Professionnel</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Artiste</div>
        <div class="info-value">{{ $artisan?->user?->name ?? '—' }}</div>
        <div class="info-label">SIRET</div>
        <div class="info-value">{{ $artisan?->siret ?? '—' }}</div>
        <div class="info-label">Adresse</div>
        <div class="info-value">{{ $artisan?->address ?? $artisan?->studio?->address ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Studio</div>
        <div class="info-value">{{ $artisan?->studio?->name ?? 'Indépendant' }}</div>
        @if ($artisan?->studio)
            <div class="info-label">SIRET Studio</div>
            <div class="info-value">{{ $artisan->studio->siret ?? '—' }}</div>
        @endif
    </div>
</div>

<h2>Client</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Nom</div>
        <div class="info-value">{{ $client?->user?->name ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Email</div>
        <div class="info-value">{{ $client?->user?->email ?? '—' }}</div>
    </div>
</div>

<h2>Détail de la prestation</h2>

<table>
    <thead>
        <tr>
            <th>Description</th>
            <th class="text-right">Montant</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                {{ $booking->description ?? 'Prestation de ' . ($artisan?->isPiercer() ? 'piercing' : 'tatouage') }}<br>
                <span class="text-small text-muted">
                    Date du RDV : {{ $booking->appointment_date?->format('d/m/Y à H:i') ?? '—' }}
                    @if ($booking->body_area)
                        — Zone : {{ $booking->body_area }}
                    @endif
                </span>
            </td>
            <td class="text-right">{{ number_format(($booking->total_price ?? $booking->price ?? 0) / 100, 2, ',', ' ') }} €</td>
        </tr>
    </tbody>
</table>

<table>
    <tbody>
        <tr>
            <td><strong>Total de la prestation</strong></td>
            <td class="text-right"><strong>{{ number_format(($booking->total_price ?? $booking->price ?? 0) / 100, 2, ',', ' ') }} €</strong></td>
        </tr>
        <tr>
            <td>Acompte versé le {{ $booking->deposit_paid_at?->format('d/m/Y') ?? '—' }}</td>
            <td class="text-right">- {{ number_format(($booking->total_deposit_amount ?? $booking->deposit_amount ?? 0) / 100, 2, ',', ' ') }} €</td>
        </tr>
        <tr>
            <td>Solde versé le {{ $booking->balance_paid_at?->format('d/m/Y') ?? '—' }}</td>
            <td class="text-right">- {{ number_format((($booking->total_price ?? 0) - ($booking->total_deposit_amount ?? 0)) / 100, 2, ',', ' ') }} €</td>
        </tr>
        <tr>
            <td><strong>Reste dû</strong></td>
            <td class="text-right"><strong>0,00 €</strong></td>
        </tr>
    </tbody>
</table>

<p class="text-small text-muted mt-20">
    TVA non applicable — article 293 B du CGI (si auto-entrepreneur).<br>
    Ce reçu est établi par le professionnel via la plateforme Ink&Pik et ne constitue pas une facture au sens du Code général des impôts.
    L'artiste est seul responsable de l'émission de ses factures conformément à la réglementation applicable.
</p>

<p class="text-small text-muted mt-10">
    Paiements traités par Stripe Payments Europe, Ltd. — Transaction sécurisée.
</p>

@endsection
```

IMPORTANT : Adapter les noms de colonnes (`total_price`, `price`, `deposit_amount`, `total_deposit_amount`, `appointment_date`, `body_area`, etc.) aux vrais noms trouvés lors de l'audit Phase 0. Les montants sont probablement en centimes — diviser par 100 pour l'affichage.

```bash
git add -A && git commit -m "feat(pdf): 6 templates PDF (care-sheet, consent, parental, traceability, client-summary, receipt)"
```

---

## PHASE 4 — CONTROLLER ET ROUTES

Créer un controller dédié aux exports PDF :

```php
// app/Http/Controllers/PdfExportController.php
namespace App\Http\Controllers;

use App\Services\PdfExportService;
use App\Models\ClientCareSheet;
use App\Models\ClientConsentForm;
use App\Models\ParentalConsentForm;
use App\Models\TraceabilityRecord;
use App\Models\BookingRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class PdfExportController extends Controller
{
    public function __construct(private PdfExportService $pdfService) {}

    /**
     * Télécharger la fiche de soins.
     */
    public function careSheet(ClientCareSheet $careSheet)
    {
        $this->authorizeAccess($careSheet);

        $pdf = $this->pdfService->generateCareSheet($careSheet);
        $filename = 'fiche-soins-' . $careSheet->id . '-' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Afficher la fiche de soins dans le navigateur (stream).
     */
    public function careSheetPreview(ClientCareSheet $careSheet)
    {
        $this->authorizeAccess($careSheet);

        $pdf = $this->pdfService->generateCareSheet($careSheet);
        return $pdf->stream('fiche-soins-' . $careSheet->id . '.pdf');
    }

    /**
     * Télécharger le formulaire de consentement.
     */
    public function consentForm(ClientConsentForm $consentForm)
    {
        $this->authorizeAccess($consentForm);

        $pdf = $this->pdfService->generateConsentForm($consentForm);
        return $pdf->download('consentement-' . $consentForm->id . '-' . now()->format('Ymd') . '.pdf');
    }

    /**
     * Télécharger le consentement parental.
     */
    public function parentalConsent(ParentalConsentForm $parentalConsent)
    {
        $this->authorizeAccess($parentalConsent);

        $pdf = $this->pdfService->generateParentalConsent($parentalConsent);
        return $pdf->download('consentement-parental-' . $parentalConsent->id . '-' . now()->format('Ymd') . '.pdf');
    }

    /**
     * Télécharger la fiche de traçabilité.
     */
    public function traceabilityRecord(TraceabilityRecord $record)
    {
        $this->authorizeAccess($record);

        $pdf = $this->pdfService->generateTraceabilityRecord($record);
        return $pdf->download('tracabilite-' . $record->id . '-' . now()->format('Ymd') . '.pdf');
    }

    /**
     * Télécharger le récapitulatif complet d'un client.
     */
    public function clientSummary(Client $client)
    {
        $artisan = auth()->user()->artisan();
        abort_unless($artisan, 403);

        // Vérifier que l'artiste a accès à ce client
        // (via studio_id ou via relation directe)
        $pdf = $this->pdfService->generateClientSummary($client, $artisan);
        return $pdf->download('client-' . $client->id . '-recap-' . now()->format('Ymd') . '.pdf');
    }

    /**
     * Télécharger le reçu de prestation.
     */
    public function receipt(BookingRequest $booking)
    {
        // Le client ET l'artiste peuvent télécharger le reçu
        $user = auth()->user();
        $isClient = $booking->client && $booking->client->user_id === $user->id;
        $isArtisan = $booking->bookable && $booking->bookable->user_id === $user->id;
        abort_unless($isClient || $isArtisan || $user->hasRole('admin'), 403);

        $pdf = $this->pdfService->generateReceipt($booking);
        return $pdf->download('recu-' . $booking->id . '-' . now()->format('Ymd') . '.pdf');
    }

    /**
     * Vérifier que l'utilisateur a accès au document.
     */
    private function authorizeAccess($model): void
    {
        $user = auth()->user();
        $artisan = $user->artisan();

        // Admin a accès à tout
        if ($user->hasRole('admin')) return;

        // L'artiste qui a créé le document
        if ($artisan) {
            // Check via bookable polymorphique
            if (method_exists($model, 'bookable') && $model->bookable_id === $artisan->id) return;
            // Check via tattooer_id/piercer_id direct
            if (isset($model->tattooer_id) && $model->tattooer_id === $artisan->id) return;
            if (isset($model->piercer_id) && $model->piercer_id === $artisan->id) return;
            // Check via studio
            if ($artisan->studio_id && isset($model->studio_id) && $model->studio_id === $artisan->studio_id) return;
        }

        // Le client du document
        $client = $user->client ?? null;
        if ($client && isset($model->client_id) && $model->client_id === $client->id) return;

        abort(403, 'Accès non autorisé à ce document.');
    }
}
```

Ajouter les routes (auth requise) :

```php
// Dans routes/web.php — accessible pour artistes et clients authentifiés
Route::middleware(['auth'])->prefix('pdf')->name('pdf.')->group(function () {
    Route::get('/fiche-soins/{careSheet}', [PdfExportController::class, 'careSheet'])->name('care-sheet');
    Route::get('/fiche-soins/{careSheet}/apercu', [PdfExportController::class, 'careSheetPreview'])->name('care-sheet.preview');
    Route::get('/consentement/{consentForm}', [PdfExportController::class, 'consentForm'])->name('consent-form');
    Route::get('/consentement-parental/{parentalConsent}', [PdfExportController::class, 'parentalConsent'])->name('parental-consent');
    Route::get('/tracabilite/{record}', [PdfExportController::class, 'traceabilityRecord'])->name('traceability');
    Route::get('/client/{client}/recap', [PdfExportController::class, 'clientSummary'])->name('client-summary');
    Route::get('/recu/{booking}', [PdfExportController::class, 'receipt'])->name('receipt');
});
```

```bash
git add -A && git commit -m "feat(pdf): PdfExportController + routes /pdf/*"
```

---

## PHASE 5 — BOUTONS PDF DANS LES VUES EXISTANTES

Ajouter les boutons de téléchargement PDF dans les vues artiste existantes.

### 5A. Créer un partial bouton PDF réutilisable

```blade
{{-- resources/views/partials/pdf-download-button.blade.php --}}
@props(['route', 'label' => 'Télécharger PDF', 'small' => false])

<a href="{{ $route }}" target="_blank"
    class="{{ $small ? 'text-xs px-3 py-1.5' : 'text-sm px-4 py-2' }} inline-flex items-center gap-1.5 bg-gris-fonde text-titane hover:text-beige-peau border border-titane/20 hover:border-beige-peau/30 rounded-lg transition-colors">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
    </svg>
    {{ $label }}
</a>
```

### 5B. Identifier les vues à modifier

```bash
# Trouver les vues de fiches clients, traçabilité, consentement
grep -rn "care.sheet\|careSheet\|client-show\|client_show\|consent\|tracabilite\|traceability" resources/views/ --include="*.blade.php" | grep -v "pdf/" | head -20

# Vue détail booking (pour le reçu)
grep -rn "booking.*show\|booking.*detail\|booking-request.*show" resources/views/ --include="*.blade.php" | head -10
```

Pour CHAQUE vue identifiée, ajouter le bouton PDF au bon endroit :

```blade
{{-- Exemple dans la vue de fiche client --}}
@if ($careSheet->id)
    @include('partials.pdf-download-button', [
        'route' => route('pdf.care-sheet', $careSheet),
        'label' => 'Fiche de soins PDF',
        'small' => true,
    ])
@endif

{{-- Exemple pour le consentement --}}
@if ($consentForm->id)
    @include('partials.pdf-download-button', [
        'route' => route('pdf.consent-form', $consentForm),
        'label' => 'Consentement PDF',
        'small' => true,
    ])
@endif

{{-- Exemple pour la traçabilité --}}
@if ($record->id)
    @include('partials.pdf-download-button', [
        'route' => route('pdf.traceability', $record),
        'label' => 'Traçabilité PDF',
        'small' => true,
    ])
@endif

{{-- Exemple pour le reçu dans la vue booking --}}
@if (in_array($booking->status->value, ['completed', 'confirmed']))
    @include('partials.pdf-download-button', [
        'route' => route('pdf.receipt', $booking),
        'label' => 'Reçu PDF',
        'small' => true,
    ])
@endif
```

Pour la vue récapitulatif client (bouton "Exporter tout en PDF") :

```blade
{{-- Dans la vue client-show de l'artiste --}}
@include('partials.pdf-download-button', [
    'route' => route('pdf.client-summary', $client),
    'label' => 'Exporter la fiche complète PDF',
])
```

IMPORTANT : Les noms de vues et variables sont indicatifs — adapter aux noms réels trouvés lors de l'audit.

```bash
git add -A && git commit -m "feat(pdf): boutons téléchargement PDF dans les vues artiste et client"
```

---

## PHASE 6 — TEMPLATE CLIENT SUMMARY (récapitulatif complet)

```blade
{{-- resources/views/pdf/client-summary.blade.php --}}
@extends('pdf.layout')

@section('title', 'Récapitulatif client')
@section('doc-type', 'Récapitulatif client complet')
@section('doc-date', $generatedAt->format('d/m/Y'))

@section('content')

<h2>Client</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Nom</div>
        <div class="info-value">{{ $client?->user?->name ?? '—' }}</div>
        <div class="info-label">Email</div>
        <div class="info-value">{{ $client?->user?->email ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Téléphone</div>
        <div class="info-value">{{ $client?->user?->phone ?? '—' }}</div>
        <div class="info-label">Date de naissance</div>
        <div class="info-value">{{ $client?->date_of_birth?->format('d/m/Y') ?? '—' }}</div>
    </div>
</div>

<h2>Artiste / Studio</h2>
<div class="info-grid">
    <div class="info-col">
        <div class="info-label">Artiste</div>
        <div class="info-value">{{ $artisan?->user?->name ?? '—' }}</div>
    </div>
    <div class="info-col">
        <div class="info-label">Studio</div>
        <div class="info-value">{{ $artisan?->studio?->name ?? 'Indépendant' }}</div>
    </div>
</div>

{{-- Fiches de soins --}}
@if ($careSheets->count() > 0)
<h2>Fiches de soins ({{ $careSheets->count() }})</h2>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Zone</th>
            <th>Type</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($careSheets as $cs)
        <tr>
            <td>{{ $cs->created_at?->format('d/m/Y') }}</td>
            <td>{{ $cs->body_area ?? $cs->zone ?? '—' }}</td>
            <td>{{ $cs->procedure_type ?? '—' }}</td>
            <td class="text-small">{{ Str::limit($cs->notes ?? $cs->description ?? '—', 60) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Consentements --}}
@if ($consentForms->count() > 0)
<h2>Consentements signés ({{ $consentForms->count() }})</h2>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($consentForms as $cf)
        <tr>
            <td>{{ $cf->created_at?->format('d/m/Y') }}</td>
            <td>{{ $cf->type ?? 'Consentement éclairé' }}</td>
            <td>{{ $cf->signed_at ? '✓ Signé le ' . $cf->signed_at->format('d/m/Y') : '✗ Non signé' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Traçabilité --}}
@if ($traceRecords->count() > 0)
<h2>Traçabilité ({{ $traceRecords->count() }} actes)</h2>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Zone</th>
            <th>Aiguilles</th>
            <th>Encres</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($traceRecords as $tr)
        <tr>
            <td>{{ $tr->performed_at?->format('d/m/Y') ?? $tr->created_at?->format('d/m/Y') }}</td>
            <td>{{ $tr->body_area ?? '—' }}</td>
            <td class="text-small">{{ $tr->needles->pluck('brand')->join(', ') ?: '—' }}</td>
            <td class="text-small">{{ $tr->inks->pluck('color')->join(', ') ?: '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<p class="text-small text-muted mt-20">
    Ce récapitulatif est généré automatiquement par Ink&Pik. Il regroupe l'ensemble des fiches de soins,
    consentements et enregistrements de traçabilité pour ce client. Pour les versions complètes de chaque document,
    utilisez les exports individuels depuis la plateforme.
</p>

@endsection
```

```bash
git add -A && git commit -m "feat(pdf): template client-summary récapitulatif complet"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PDF ==="

# V1. dompdf installé
composer show barryvdh/laravel-dompdf 2>&1 | head -3

# V2. Config
ls config/dompdf.php 2>/dev/null && echo "Config OK" || echo "Config ABSENTE"

# V3. Service
ls app/Services/PdfExportService.php && echo "Service OK" || echo "Service ABSENT"

# V4. Controller
ls app/Http/Controllers/PdfExportController.php && echo "Controller OK" || echo "Controller ABSENT"

# V5. Templates
echo "--- Templates PDF ---"
ls resources/views/pdf/ 2>/dev/null

# V6. Routes
php artisan route:list --name="pdf" --columns=method,uri,name 2>&1

# V7. Compilation
php artisan route:clear
php artisan view:clear
php artisan route:list 2>&1 | head -3
echo "Routes OK si pas d'erreur"

# V8. Boutons dans les vues
grep -rn "pdf\.\|pdf-download" resources/views/ --include="*.blade.php" | grep -v "resources/views/pdf/" | head -10
echo "Boutons PDF branchés dans les vues"

echo "=== PDF TERMINÉ ==="
```

---

## ⚠️ RÈGLES

1. **AUDIT AVANT TOUT** — Phase 0 obligatoire, adapter les noms de colonnes aux vrais noms
2. **CSS inline pour dompdf** — pas de TailwindCSS dans les templates PDF
3. **Montants en centimes** — vérifier si les montants sont stockés en centimes et diviser par 100
4. **Relations polymorphiques** — `bookable_type/bookable_id` : s'assurer que les loads fonctionnent
5. **Autorisation** — authorizeAccess() vérifie artiste, client, studio, admin
6. **Noms de fichiers français** — fiche-soins, consentement, tracabilite, recu
7. **Commit après chaque phase** (5-6 commits)
8. **Le scope forArtisan()** existe déjà sur les models (créé fix Studio 3) — le réutiliser
