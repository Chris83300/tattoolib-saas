# 📜 P1.1 — PAGES LÉGALES COMPLÈTES INK&PIK
# Pour Claude Code — 6 documents juridiques + pages Blade + routes + footer

## CONTEXTE

Ink&Pik est une marketplace SaaS française de mise en relation entre :
- **Clients** (particuliers, consommateurs) cherchant des artistes tatoueurs/pierceurs
- **Artistes** (professionnels indépendants : tatoueurs, pierceurs, body modifiers)
- **Studios** (salons de tatouage/piercing gérant plusieurs artistes)

**Modèle économique** :
- Plan FREE artiste : 0€/mois, commission 7% via Stripe Application Fee sur chaque prestation
- Plan PRO artiste : 49,99€/mois, 0% commission
- Plan STUDIO : 79,99€/mois (1 artiste inclus) + 39,99€/artiste supplémentaire, trial 14j
- Les clients ne paient PAS d'abonnement — ils paient un acompte puis un solde pour chaque prestation

**Flux de paiement** :
- Le client paie un acompte (montant défini par l'artiste) via Stripe Checkout
- Le solde est payé avant le RDV via Stripe
- L'artiste reçoit les fonds sur son compte Stripe Connect (mode distribué) OU le studio encaisse (mode centralisé)
- Ink&Pik prélève 7% de commission (plan FREE) ou 0% (plan PRO/Studio) via Stripe Application Fee
- Ink&Pik n'encaisse JAMAIS les fonds pour le compte des artistes — Stripe Connect gère le flux

**Politique de remboursement** :
- Annulation par le CLIENT avant paiement acompte : gratuit
- Annulation par le CLIENT après acompte payé : remboursement partiel (% dégressif selon délai avant RDV, calculé par calculateRefundPercentage())
- Annulation par l'ARTISTE : remboursement intégral de l'acompte au client
- No-show client : pas de remboursement, l'artiste conserve l'acompte
- No-show artiste : remboursement intégral au client + signalement admin

**Données collectées** :
- Utilisateurs : nom, prénom, email, téléphone, adresse, avatar, date de naissance
- Artistes : + SIRET, déclaration ARS, certification hygiène, portfolio (photos), disponibilités, Stripe Connect account
- Clients : + historique de tatouages, allergies/conditions médicales (fiches clients), consentements signés, photos référence
- Studios : + raison sociale, SIRET studio, logo, photos, adresse(s), artistes affiliés
- Traçabilité : enregistrements des actes (encres, aiguilles, lots) conformément au Code de la Santé Publique
- Chat : messages et médias échangés entre clients et artistes

**Données sensibles** :
- Données de SANTÉ (allergies, conditions médicales, historique tatouages) → base légale : exécution du contrat + consentement explicite
- Données financières (transactions, factures) → via Stripe (PCI DSS compliant), Ink&Pik ne stocke PAS les numéros de carte
- Photos corporelles (zones tatouées/percées) → consentement explicite

**Réglementation sectorielle tatouage/piercing** :
- Code de la Santé Publique : articles R.1311-1 à R.1311-13
- Déclaration ARS obligatoire pour chaque professionnel
- Formation hygiène et salubrité 21h (certification ARS, valable 5 ans) — arrêté du 5 mars 2024
- Norme NF EN 17169 (bonnes pratiques hygiène)
- Traçabilité des actes obligatoire (encres, aiguilles, lots)
- Information préalable du client sur les risques (art. R.1311-12)
- Consentement parental écrit obligatoire pour les mineurs (art. R.1311-11)
- Réglementation REACH pour les encres

**Hébergement** : à préciser dans les mentions légales (serveur EU recommandé pour RGPD)
**Paiement** : Stripe (société irlandaise, données dans l'UE)
**DPO** : [à nommer — peut être le fondateur pour une startup]

---

## DOCUMENTS À GÉNÉRER

6 documents juridiques distincts, chacun dans sa propre vue Blade :

1. **Mentions Légales** (`mentions-legales.blade.php`) — obligatoire
2. **CGU** (`cgu.blade.php`) — Conditions Générales d'Utilisation
3. **CGV Artistes** (`cgv-artistes.blade.php`) — Conditions Générales de Vente pour les abonnements artistes/studios
4. **CGV Clients** (`cgv-clients.blade.php`) — Conditions Générales de Vente pour les prestations (acompte + solde)
5. **Politique de Confidentialité** (`politique-confidentialite.blade.php`) — RGPD
6. **Politique de Cookies** (`politique-cookies.blade.php`)

---

## PHASE 0 — AUDIT

```bash
echo "=== AUDIT PAGES LÉGALES ==="

# 0A. Pages légales existantes ?
find resources/views -name "*legal*" -o -name "*mention*" -o -name "*cgu*" -o -name "*cgv*" -o -name "*confidentialite*" -o -name "*cookie*" -o -name "*privacy*" -o -name "*terms*" 2>/dev/null

# 0B. Routes légales existantes ?
php artisan route:list 2>&1 | grep -i "legal\|mention\|cgu\|cgv\|privacy\|cookie\|terms\|confidentialite"

# 0C. Layout public (pour étendre les vues)
ls resources/views/layouts/ | head -10
# Identifier le layout utilisé par les pages publiques (welcome, marketplace)
head -3 resources/views/welcome.blade.php
head -3 resources/views/marketplace/index.blade.php 2>/dev/null

# 0D. Footer actuel
grep -rn "mention\|cgu\|cgv\|confidentialite\|cookie\|legal" resources/views/layouts/ --include="*.blade.php" | head -10
grep -rn "footer\|Footer" resources/views/layouts/ --include="*.blade.php" | head -5

# 0E. Nom de l'entreprise / éditeur
grep -n "APP_NAME\|app_name\|INK.*PIK\|ink.*pik" .env | head -5
grep -n "company\|société\|editeur\|siret" config/ -r 2>/dev/null | head -5

# 0F. Hébergeur
grep -n "host\|HOST\|hetzner\|ovh\|aws\|scaleway\|digitalocean" .env | head -5
```

**MONTRE-MOI TOUS les résultats avant de continuer.**

---

## PHASE 1 — ROUTES ET CONTROLLER

Créer un controller dédié aux pages légales :

```php
// app/Http/Controllers/LegalController.php
namespace App\Http\Controllers;

class LegalController extends Controller
{
    public function mentionsLegales()
    {
        return view('legal.mentions-legales');
    }

    public function cgu()
    {
        return view('legal.cgu');
    }

    public function cgvArtistes()
    {
        return view('legal.cgv-artistes');
    }

    public function cgvClients()
    {
        return view('legal.cgv-clients');
    }

    public function politiqueConfidentialite()
    {
        return view('legal.politique-confidentialite');
    }

    public function politiqueCookies()
    {
        return view('legal.politique-cookies');
    }
}
```

Ajouter les routes PUBLIQUES (pas d'auth requise) dans `routes/web.php` :

```php
// Pages légales — accessibles sans authentification
Route::prefix('legal')->name('legal.')->group(function () {
    Route::get('/mentions-legales', [LegalController::class, 'mentionsLegales'])->name('mentions-legales');
    Route::get('/cgu', [LegalController::class, 'cgu'])->name('cgu');
    Route::get('/cgv-artistes', [LegalController::class, 'cgvArtistes'])->name('cgv-artistes');
    Route::get('/cgv-clients', [LegalController::class, 'cgvClients'])->name('cgv-clients');
    Route::get('/politique-de-confidentialite', [LegalController::class, 'politiqueConfidentialite'])->name('politique-confidentialite');
    Route::get('/politique-de-cookies', [LegalController::class, 'politiqueCookies'])->name('politique-cookies');
});
```

```bash
git add -A && git commit -m "feat(legal): controller + routes pages légales"
```

---

## PHASE 2 — LAYOUT LÉGAL

Créer un layout minimal pour les pages légales (étendre le layout public existant) :

```bash
mkdir -p resources/views/legal
```

Identifier le layout utilisé par les pages publiques et l'étendre. Si le layout est `layouts.app` ou `layouts.guest` :

```blade
{{-- resources/views/legal/layout.blade.php --}}
@extends('layouts.app') {{-- OU le layout public existant --}}

@section('content')
<div class="min-h-screen bg-noir-profond">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 sm:py-16">
        {{-- Breadcrumb --}}
        <nav class="mb-8">
            <a href="{{ url('/') }}" class="text-sm text-titane hover:text-beige-peau transition-colors">
                ← Retour à l'accueil
            </a>
        </nav>

        {{-- En-tête --}}
        <header class="mb-10">
            <h1 class="text-2xl sm:text-3xl font-bold text-ivoire-text">@yield('legal-title')</h1>
            <p class="text-sm text-titane mt-2">
                Dernière mise à jour : @yield('legal-date', now()->format('d/m/Y'))
            </p>
        </header>

        {{-- Contenu juridique --}}
        <article class="prose prose-invert prose-sm max-w-none
            prose-headings:text-ivoire-text prose-headings:font-semibold
            prose-h2:text-xl prose-h2:mt-10 prose-h2:mb-4 prose-h2:border-b prose-h2:border-titane/20 prose-h2:pb-2
            prose-h3:text-lg prose-h3:mt-6 prose-h3:mb-3
            prose-p:text-titane prose-p:leading-relaxed
            prose-li:text-titane
            prose-strong:text-ivoire-text
            prose-a:text-beige-peau prose-a:no-underline hover:prose-a:underline">
            @yield('legal-content')
        </article>

        {{-- Navigation entre pages légales --}}
        <footer class="mt-16 pt-8 border-t border-titane/20">
            <h3 class="text-sm font-semibold text-ivoire-text/60 uppercase tracking-wider mb-4">Documents juridiques</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <a href="{{ route('legal.mentions-legales') }}" class="text-sm text-titane hover:text-beige-peau transition-colors">Mentions légales</a>
                <a href="{{ route('legal.cgu') }}" class="text-sm text-titane hover:text-beige-peau transition-colors">CGU</a>
                <a href="{{ route('legal.cgv-artistes') }}" class="text-sm text-titane hover:text-beige-peau transition-colors">CGV Artistes</a>
                <a href="{{ route('legal.cgv-clients') }}" class="text-sm text-titane hover:text-beige-peau transition-colors">CGV Clients</a>
                <a href="{{ route('legal.politique-confidentialite') }}" class="text-sm text-titane hover:text-beige-peau transition-colors">Politique de confidentialité</a>
                <a href="{{ route('legal.politique-cookies') }}" class="text-sm text-titane hover:text-beige-peau transition-colors">Politique de cookies</a>
            </div>
        </footer>
    </div>
</div>
@endsection
```

IMPORTANT : Adapter le `@extends` au layout public réel du projet. Vérifier les classes de couleurs du design system (noir-profond, ivoire-text, titane, beige-peau).

---

## PHASE 3 — MENTIONS LÉGALES

Obligatoire en France (loi LCEN du 21 juin 2004).

```blade
{{-- resources/views/legal/mentions-legales.blade.php --}}
@extends('legal.layout')

@section('legal-title', 'Mentions légales')
@section('legal-date', '01/03/2026')

@section('legal-content')

{{-- IMPORTANT : Remplacer TOUTES les valeurs entre [crochets] par les vraies informations --}}

<h2>1. Éditeur du site</h2>

<p>Le site <strong>Ink&Pik</strong>, accessible à l'adresse <strong>[www.inkpik.fr]</strong>, est édité par :</p>

<p>
    <strong>[Raison sociale / Nom de l'auto-entrepreneur]</strong><br>
    [Forme juridique : SAS / SARL / Auto-entrepreneur / etc.]<br>
    Capital social : [montant] € (si applicable)<br>
    Siège social : [Adresse complète]<br>
    Immatriculation : RCS [Ville] n° [numéro] / SIRET : [numéro SIRET]<br>
    N° TVA intracommunautaire : [FR + numéro] (si assujetti)<br>
    Directeur de la publication : [Prénom Nom], en qualité de [Gérant / Président]<br>
    Contact : <a href="mailto:contact@inkpik.fr">contact@inkpik.fr</a>
</p>

<h2>2. Hébergeur</h2>

<p>Le site est hébergé par :</p>
<p>
    <strong>[Nom de l'hébergeur]</strong><br>
    [Forme juridique]<br>
    Siège social : [Adresse complète]<br>
    Téléphone : [numéro]<br>
    Site web : [URL]
</p>

<h2>3. Prestataire de services de paiement</h2>

<p>Les services de paiement sont fournis par <strong>Stripe Payments Europe, Ltd.</strong>, établissement de paiement agréé (licence n° C187865) par la Banque centrale d'Irlande, dont le siège social est situé au 1 Grand Canal Street Lower, Grand Canal Dock, Dublin 2, Irlande.</p>

<p>Ink&Pik n'encaisse pas directement les fonds pour le compte des artistes. Les paiements transitent via Stripe Connect, conformément à la réglementation européenne sur les services de paiement (DSP2 — Directive 2015/2366/UE). Ink&Pik agit en qualité de plateforme de mise en relation et non d'intermédiaire financier.</p>

<h2>4. Activité de la plateforme</h2>

<p>Ink&Pik est une plateforme de mise en relation en ligne (marketplace) entre des professionnels de l'art corporel (tatoueurs, pierceurs, body modifiers) et des clients particuliers. Ink&Pik n'exerce pas elle-même d'activité de tatouage ou de piercing et n'est pas soumise aux obligations du Code de la Santé Publique applicables aux professionnels du secteur (articles R.1311-1 et suivants du CSP).</p>

<p>Ink&Pik vérifie, dans la mesure du possible, que les artistes référencés disposent d'un numéro SIRET valide et déclarent être en conformité avec les obligations réglementaires applicables (déclaration ARS, certification hygiène et salubrité). Toutefois, la plateforme ne saurait être tenue responsable du non-respect par un artiste de ses obligations légales et réglementaires.</p>

<h2>5. Propriété intellectuelle</h2>

<p>L'ensemble du contenu du site (textes, graphismes, logos, icônes, images, structure, logiciel, base de données) est la propriété exclusive d'Ink&Pik ou de ses partenaires et est protégé par les lois françaises et internationales relatives à la propriété intellectuelle.</p>

<p>Les portfolios et œuvres publiés par les artistes restent la propriété intellectuelle de leurs auteurs respectifs. En publiant leur contenu sur la plateforme, les artistes concèdent à Ink&Pik une licence d'utilisation non exclusive à des fins de promotion et d'affichage sur la marketplace.</p>

<h2>6. Données personnelles</h2>

<p>Le traitement des données personnelles est décrit dans notre <a href="{{ route('legal.politique-confidentialite') }}">Politique de confidentialité</a>, conformément au Règlement Général sur la Protection des Données (RGPD — Règlement UE 2016/679) et à la loi Informatique et Libertés du 6 janvier 1978 modifiée.</p>

<h2>7. Cookies</h2>

<p>Le site utilise des cookies. Pour en savoir plus, consultez notre <a href="{{ route('legal.politique-cookies') }}">Politique de cookies</a>.</p>

<h2>8. Médiation des litiges</h2>

<p>Conformément aux articles L.611-1 et suivants du Code de la consommation, tout consommateur a le droit de recourir gratuitement à un médiateur de la consommation en vue de la résolution amiable d'un litige.</p>

<p>
    Médiateur désigné : <strong>[Nom du médiateur ou organisme de médiation]</strong><br>
    Site web : [URL du médiateur]<br>
    Adresse : [adresse du médiateur]
</p>

<p>Le consommateur peut également déposer une réclamation sur la plateforme européenne de règlement en ligne des litiges : <a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noopener">https://ec.europa.eu/consumers/odr</a>.</p>

<h2>9. Droit applicable et juridiction compétente</h2>

<p>Les présentes mentions légales sont soumises au droit français. En cas de litige, et à défaut de résolution amiable, les tribunaux français compétents seront ceux du ressort du siège social d'Ink&Pik.</p>

@endsection
```

---

## PHASE 4 — CGU (Conditions Générales d'Utilisation)

```blade
{{-- resources/views/legal/cgu.blade.php --}}
@extends('legal.layout')

@section('legal-title', "Conditions Générales d'Utilisation (CGU)")
@section('legal-date', '01/03/2026')

@section('legal-content')

<p><strong>Les présentes Conditions Générales d'Utilisation régissent l'accès et l'utilisation de la plateforme Ink&Pik par l'ensemble des utilisateurs. En créant un compte ou en utilisant le site, vous acceptez sans réserve les présentes CGU.</strong></p>

<h2>1. Définitions</h2>

<p><strong>« Plateforme »</strong> : le site internet accessible à l'adresse [www.inkpik.fr] et ses applications mobiles, édités par [raison sociale].</p>
<p><strong>« Utilisateur »</strong> : toute personne physique ou morale accédant à la Plateforme, qu'elle soit Artiste, Client ou Studio.</p>
<p><strong>« Artiste »</strong> : tout professionnel indépendant de l'art corporel (tatoueur, pierceur, body modifier) inscrit sur la Plateforme, disposant d'un numéro SIRET et des autorisations réglementaires requises.</p>
<p><strong>« Studio »</strong> : tout salon professionnel de tatouage et/ou piercing gérant un ou plusieurs Artistes, inscrit sur la Plateforme.</p>
<p><strong>« Client »</strong> : toute personne physique (consommateur au sens du Code de la consommation) utilisant la Plateforme pour trouver un Artiste et réserver une prestation.</p>
<p><strong>« Prestation »</strong> : tout acte de tatouage, piercing ou modification corporelle réalisé par un Artiste au bénéfice d'un Client.</p>
<p><strong>« Demande de réservation »</strong> : la demande formulée par un Client auprès d'un Artiste via la Plateforme, décrivant le projet de Prestation souhaité.</p>
<p><strong>« Acompte »</strong> : le montant versé par le Client à l'Artiste via Stripe Checkout, préalablement à la réalisation de la Prestation, dont le montant est fixé par l'Artiste.</p>

<h2>2. Objet</h2>

<p>Ink&Pik est une plateforme de mise en relation entre des Artistes professionnels de l'art corporel et des Clients. La Plateforme fournit les outils techniques permettant la publication de profils, la recherche d'artistes, la gestion de réservations, la messagerie et le traitement des paiements.</p>

<p><strong>Ink&Pik n'est pas partie au contrat de prestation entre l'Artiste et le Client.</strong> La Plateforme agit exclusivement en qualité d'intermédiaire technique. La responsabilité de la réalisation de la Prestation, de sa qualité, du respect des normes d'hygiène et de sécurité incombe exclusivement à l'Artiste.</p>

<h2>3. Inscription et compte utilisateur</h2>

<h3>3.1 Conditions d'inscription</h3>

<p>L'inscription est gratuite et ouverte à toute personne physique majeure (18 ans révolus) ou, pour les mineurs de plus de 16 ans, avec le consentement écrit d'un titulaire de l'autorité parentale (conformément à l'article R.1311-11 du Code de la Santé Publique pour les prestations de tatouage).</p>

<p>L'inscription en tant qu'Artiste est réservée aux professionnels disposant :</p>
<ul>
    <li>D'un numéro SIRET valide attestant de leur immatriculation</li>
    <li>D'une déclaration d'activité auprès de l'Agence Régionale de Santé (ARS) en cours de validité</li>
    <li>D'une certification « Hygiène et salubrité » délivrée par l'ARS (arrêté du 5 mars 2024), valable 5 ans</li>
</ul>

<h3>3.2 Véracité des informations</h3>

<p>L'Utilisateur s'engage à fournir des informations exactes, complètes et à jour. Tout manquement à cette obligation pourra entraîner la suspension ou la suppression du compte. L'Artiste est seul responsable de la validité de ses certifications et autorisations réglementaires.</p>

<h3>3.3 Sécurité du compte</h3>

<p>L'Utilisateur est responsable de la confidentialité de ses identifiants. Toute utilisation frauduleuse doit être signalée immédiatement à l'adresse <a href="mailto:securite@inkpik.fr">securite@inkpik.fr</a>. Ink&Pik propose l'authentification à deux facteurs (2FA) et recommande fortement son activation.</p>

<h3>3.4 Suppression du compte</h3>

<p>L'Utilisateur peut demander la suppression de son compte à tout moment depuis ses paramètres ou en contactant le support. La suppression entraîne l'effacement des données personnelles dans un délai de 30 jours, sous réserve des obligations légales de conservation (voir Politique de confidentialité).</p>

<h2>4. Utilisation de la Plateforme</h2>

<h3>4.1 Obligations de tous les Utilisateurs</h3>
<ul>
    <li>Ne pas utiliser la Plateforme à des fins illicites ou contraires aux présentes CGU</li>
    <li>Ne pas publier de contenus injurieux, diffamatoires, discriminatoires, violents ou à caractère pornographique</li>
    <li>Ne pas tenter de contourner les mesures de sécurité de la Plateforme</li>
    <li>Ne pas collecter les données personnelles d'autres Utilisateurs sans leur consentement</li>
    <li>Ne pas contourner le système de paiement de la Plateforme pour réaliser des transactions directes</li>
</ul>

<h3>4.2 Obligations spécifiques des Artistes</h3>
<ul>
    <li>Maintenir à jour leurs informations professionnelles (SIRET, déclaration ARS, certification hygiène)</li>
    <li>Respecter l'ensemble de la réglementation applicable à leur activité (Code de la Santé Publique, norme NF EN 17169)</li>
    <li>Informer préalablement le Client des risques liés à la Prestation (art. R.1311-12 du CSP)</li>
    <li>Assurer la traçabilité des actes réalisés (encres, aiguilles, lots) conformément à la réglementation</li>
    <li>Refuser toute Prestation sur un mineur sans consentement parental écrit</li>
    <li>Répondre aux Demandes de réservation dans un délai raisonnable</li>
</ul>

<h3>4.3 Obligations spécifiques des Clients</h3>
<ul>
    <li>Fournir des informations exactes sur leur état de santé (allergies, traitements médicaux, conditions dermatologiques)</li>
    <li>Respecter les rendez-vous confirmés et informer l'Artiste en cas d'empêchement</li>
    <li>Ne pas formuler de Demande de réservation sans intention réelle de réaliser la Prestation</li>
</ul>

<h2>5. Messagerie et contenu</h2>

<p>La messagerie intégrée permet les échanges entre Clients et Artistes dans le cadre d'une Demande de réservation. Les conversations sont soumises à un système d'expiration automatique après la réalisation ou l'annulation de la Prestation.</p>

<p>Il est interdit de partager via la messagerie des informations bancaires, des contenus illicites, ou d'utiliser la messagerie à des fins de sollicitation commerciale non sollicitée. Les fichiers envoyés sont analysés par un système antivirus automatique.</p>

<h2>6. Contenus publiés par les Utilisateurs</h2>

<p>Les Artistes sont responsables des contenus qu'ils publient (portfolio, description, tarifs). En publiant du contenu sur la Plateforme, l'Artiste garantit qu'il en détient les droits de propriété intellectuelle et concède à Ink&Pik une licence mondiale, non exclusive, gratuite, pour la durée d'utilisation du compte, aux fins d'affichage, de promotion et de référencement sur la Plateforme.</p>

<p>Ink&Pik se réserve le droit de supprimer tout contenu contraire aux présentes CGU sans préavis.</p>

<h2>7. Système de badges et vérification</h2>

<p>Ink&Pik propose un système de badges de conformité (vérification SIRET, certification hygiène, déclaration ARS). Ces badges sont attribués sur la base des justificatifs fournis par l'Artiste et vérifiés par l'équipe Ink&Pik. Ils constituent une indication de conformité déclarative et ne sauraient engager la responsabilité d'Ink&Pik quant au respect effectif des normes par l'Artiste.</p>

<h2>8. Avis et évaluations</h2>

<p>Les Clients peuvent laisser un avis après la réalisation d'une Prestation. Les avis doivent être sincères, fondés sur une expérience réelle et respectueux. Ink&Pik se réserve le droit de modérer et supprimer les avis frauduleux, injurieux ou contraires aux bonnes mœurs.</p>

<h2>9. Réclamations et litiges</h2>

<p>Tout litige relatif à la qualité d'une Prestation doit être adressé directement à l'Artiste. Ink&Pik peut intervenir en qualité de médiateur si les parties le souhaitent, via le système de réclamation intégré à la Plateforme.</p>

<p>En cas de comportement frauduleux, de non-respect des normes d'hygiène ou de mise en danger, le Client peut signaler l'Artiste via la Plateforme. Ink&Pik se réserve le droit de suspendre ou supprimer le compte de tout Artiste faisant l'objet de signalements avérés.</p>

<h2>10. Responsabilité</h2>

<p>Ink&Pik s'engage à assurer la disponibilité de la Plateforme dans la mesure du raisonnable (obligation de moyens). La Plateforme peut faire l'objet d'interruptions pour maintenance.</p>

<p><strong>Ink&Pik décline toute responsabilité :</strong></p>
<ul>
    <li>Quant à la qualité, la sécurité ou la conformité des Prestations réalisées par les Artistes</li>
    <li>En cas de litige direct entre un Client et un Artiste</li>
    <li>En cas de dommage résultant d'une information inexacte fournie par un Utilisateur</li>
    <li>En cas d'indisponibilité temporaire de la Plateforme</li>
</ul>

<h2>11. Modification des CGU</h2>

<p>Ink&Pik se réserve le droit de modifier les présentes CGU. Les Utilisateurs seront informés par email et/ou notification dans l'application au moins 30 jours avant l'entrée en vigueur des modifications. L'utilisation continue de la Plateforme après cette date vaut acceptation des nouvelles CGU.</p>

<h2>12. Droit applicable</h2>

<p>Les présentes CGU sont régies par le droit français. En cas de litige, et après tentative de résolution amiable, les tribunaux compétents seront ceux du ressort du siège social d'Ink&Pik, sauf disposition légale impérative contraire (notamment en faveur du consommateur).</p>

@endsection
```

---

## PHASE 5 — CGV ARTISTES (Abonnements PRO + Studio)

```blade
{{-- resources/views/legal/cgv-artistes.blade.php --}}
@extends('legal.layout')

@section('legal-title', 'Conditions Générales de Vente — Artistes & Studios')
@section('legal-date', '01/03/2026')

@section('legal-content')

<p><strong>Les présentes CGV régissent la relation commerciale entre Ink&Pik et les Artistes/Studios pour les abonnements payants et la commission sur les prestations.</strong></p>

<h2>1. Offres et tarification</h2>

<h3>1.1 Plan FREE (Artiste indépendant)</h3>
<ul>
    <li>Prix : 0 €/mois</li>
    <li>Commission : 7% prélevée automatiquement via Stripe Application Fee sur chaque transaction (acompte + solde)</li>
    <li>Accès : profil marketplace, gestion des réservations, messagerie, calendrier</li>
</ul>

<h3>1.2 Plan PRO (Artiste indépendant)</h3>
<ul>
    <li>Prix : 49,99 € TTC/mois</li>
    <li>Commission : 0%</li>
    <li>Accès : toutes les fonctionnalités FREE + fiches clients avancées, traçabilité, analytics, badges prioritaires</li>
    <li>Paiement mensuel par carte bancaire via Stripe</li>
    <li>Sans engagement — résiliation possible à tout moment, effective à la fin de la période mensuelle en cours</li>
</ul>

<h3>1.3 Plan STUDIO</h3>
<ul>
    <li>Prix de base : 79,99 € TTC/mois (1 artiste inclus)</li>
    <li>Artiste supplémentaire : 39,99 € TTC/mois par artiste</li>
    <li>Essai gratuit : 14 jours sans engagement, sans carte bancaire requise, limité à 1 artiste</li>
    <li>Commission : 0% (les artistes du studio sont automatiquement en plan PRO)</li>
    <li>Paiement mensuel par carte bancaire via Stripe</li>
    <li>Sans engagement — résiliation possible à tout moment</li>
</ul>

<h2>2. Paiement et facturation</h2>

<p>Les paiements sont traités par Stripe. L'abonnement est renouvelé automatiquement chaque mois à la date anniversaire de la souscription. Une facture est émise à chaque renouvellement et accessible depuis le portail de facturation Stripe.</p>

<p>En cas d'échec du paiement, Ink&Pik se réserve le droit de suspendre l'accès aux fonctionnalités PRO après un délai de grâce de 7 jours et 2 tentatives de prélèvement.</p>

<h2>3. Commission (Plan FREE)</h2>

<p>La commission de 7% est prélevée automatiquement par Stripe sur chaque transaction entre le Client et l'Artiste. Ce prélèvement est effectué au moment du paiement (acompte et solde) via le mécanisme Stripe Application Fee. L'Artiste reçoit le montant net (93%) directement sur son compte Stripe Connect.</p>

<p>En passant au plan PRO, la commission est supprimée et l'Artiste reçoit 100% des montants versés par les Clients.</p>

<h2>4. Stripe Connect — Obligations de l'Artiste</h2>

<p>Pour recevoir des paiements, l'Artiste doit disposer d'un compte Stripe Connect actif. L'Artiste est seul responsable de la conformité de son compte Stripe avec les conditions d'utilisation de Stripe et la réglementation applicable.</p>

<p>Pour les Artistes d'un Studio en mode « centralisé », les paiements sont reçus sur le compte Stripe Connect du Studio. L'Artiste n'a pas besoin de son propre compte Stripe dans ce cas.</p>

<h2>5. Droit de rétractation</h2>

<p>Conformément à l'article L.221-28 du Code de la consommation, <strong>le droit de rétractation ne s'applique pas</strong> aux abonnements de fourniture de contenu numérique non fourni sur un support matériel dont l'exécution a commencé avec l'accord du consommateur. En souscrivant à un abonnement PRO ou Studio, l'Artiste/Studio accède immédiatement aux fonctionnalités et reconnaît renoncer expressément à son droit de rétractation.</p>

<p>Toutefois, l'Artiste/Studio peut résilier son abonnement à tout moment (sans engagement). La résiliation prend effet à la fin de la période mensuelle en cours — aucun remboursement au prorata n'est effectué.</p>

<h2>6. Résiliation</h2>

<h3>6.1 Résiliation par l'Artiste/Studio</h3>
<p>L'Artiste ou le Studio peut résilier son abonnement à tout moment depuis le portail de facturation Stripe ou en contactant le support. L'abonnement reste actif jusqu'à la fin de la période en cours.</p>

<h3>6.2 Résiliation par Ink&Pik</h3>
<p>Ink&Pik se réserve le droit de suspendre ou résilier un compte Artiste/Studio en cas de :</p>
<ul>
    <li>Non-respect des CGU ou des présentes CGV</li>
    <li>Fourniture d'informations frauduleuses (faux SIRET, fausse certification hygiène)</li>
    <li>Signalements multiples de Clients (manquement à l'hygiène, comportement inapproprié)</li>
    <li>Non-paiement de l'abonnement après les tentatives de relance</li>
    <li>Inactivité prolongée (aucune transaction depuis 12 mois pour un compte FREE)</li>
</ul>

<h2>7. Responsabilité</h2>

<p>Ink&Pik n'est pas responsable des pertes de revenus de l'Artiste liées à une indisponibilité temporaire de la Plateforme. La responsabilité d'Ink&Pik est limitée au montant total des abonnements versés par l'Artiste/Studio au cours des 12 derniers mois.</p>

<h2>8. Données et portabilité</h2>

<p>L'Artiste/Studio peut à tout moment exporter ses données (fiches clients, historique des prestations, traçabilité) depuis son espace personnel, conformément au droit à la portabilité des données (article 20 du RGPD).</p>

<h2>9. Droit applicable</h2>

<p>Les présentes CGV sont régies par le droit français. En cas de litige entre professionnels, les tribunaux de commerce du ressort du siège social d'Ink&Pik sont compétents.</p>

@endsection
```

---

## PHASE 6 — CGV CLIENTS (Prestations, acompte, remboursement)

```blade
{{-- resources/views/legal/cgv-clients.blade.php --}}
@extends('legal.layout')

@section('legal-title', 'Conditions Générales de Vente — Clients')
@section('legal-date', '01/03/2026')

@section('legal-content')

<p><strong>Les présentes CGV régissent les conditions de réservation et de paiement des prestations d'art corporel via la plateforme Ink&Pik.</strong></p>

<h2>1. Rôle d'Ink&Pik</h2>

<p>Ink&Pik agit exclusivement en qualité de <strong>plateforme de mise en relation</strong> entre les Clients et les Artistes. Ink&Pik n'est pas partie au contrat de prestation et n'est pas le prestataire de la prestation de tatouage, piercing ou modification corporelle. Le contrat de prestation est conclu directement entre le Client et l'Artiste.</p>

<p>Ink&Pik fournit les outils techniques de gestion des réservations et de traitement des paiements via Stripe Connect.</p>

<h2>2. Processus de réservation</h2>

<h3>2.1 Demande de réservation</h3>
<p>Le Client formule une Demande de réservation décrivant son projet (zone, taille, style, photos de référence). Cette demande n'engage ni le Client ni l'Artiste.</p>

<h3>2.2 Acceptation par l'Artiste</h3>
<p>L'Artiste peut accepter, refuser ou proposer des modifications à la Demande. L'acceptation déclenche l'ouverture de la messagerie et la proposition de créneaux.</p>

<h3>2.3 Sélection du créneau et acompte</h3>
<p>Le Client sélectionne un créneau parmi ceux proposés par l'Artiste. La confirmation de la réservation est effective au moment du paiement de l'acompte.</p>

<h2>3. Paiements</h2>

<h3>3.1 Acompte</h3>
<p>L'acompte est un montant fixé par l'Artiste, payable en ligne via Stripe Checkout. Le paiement de l'acompte confirme la réservation et engage le Client et l'Artiste.</p>

<p>L'acompte est versé directement à l'Artiste (ou au Studio en mode centralisé) via Stripe Connect. Ink&Pik ne détient pas les fonds.</p>

<h3>3.2 Solde</h3>
<p>Le solde de la prestation (prix total — acompte) est payable avant le rendez-vous via la Plateforme. Le montant exact est convenu entre le Client et l'Artiste.</p>

<h3>3.3 Moyens de paiement</h3>
<p>Les paiements sont traités par Stripe et acceptent les cartes bancaires (Visa, Mastercard, CB, American Express). Ink&Pik ne stocke aucune donnée de carte bancaire. Le traitement est conforme à la norme PCI DSS.</p>

<h3>3.4 Facturation</h3>
<p>Un reçu de paiement est envoyé par email après chaque transaction. La facture de la prestation est de la responsabilité de l'Artiste.</p>

<h2>4. Politique d'annulation et de remboursement</h2>

<h3>4.1 Annulation par le Client AVANT paiement de l'acompte</h3>
<p>Le Client peut annuler sa Demande de réservation à tout moment tant que l'acompte n'a pas été versé. Aucune pénalité n'est appliquée.</p>

<h3>4.2 Annulation par le Client APRÈS paiement de l'acompte</h3>
<p>Une fois l'acompte versé, les conditions de remboursement suivantes s'appliquent :</p>

<table>
    <thead>
        <tr>
            <th>Délai avant le rendez-vous</th>
            <th>Remboursement de l'acompte</th>
        </tr>
    </thead>
    <tbody>
        <tr><td>Plus de 30 jours</td><td>80% de l'acompte remboursé</td></tr>
        <tr><td>Entre 15 et 30 jours</td><td>50% de l'acompte remboursé</td></tr>
        <tr><td>Entre 7 et 14 jours</td><td>25% de l'acompte remboursé</td></tr>
        <tr><td>Moins de 7 jours</td><td>Aucun remboursement</td></tr>
    </tbody>
</table>

<p><em>Note : les pourcentages ci-dessus sont indicatifs et correspondent à la politique par défaut de la Plateforme. L'Artiste peut définir sa propre politique d'annulation lors de la configuration de son profil.</em></p>

<h3>4.3 Annulation par l'Artiste</h3>
<p>Si l'Artiste annule la prestation pour quelque raison que ce soit, <strong>l'intégralité de l'acompte est remboursée au Client</strong> dans un délai de 5 à 10 jours ouvrés via Stripe.</p>

<h3>4.4 Non-présentation (no-show)</h3>
<ul>
    <li><strong>No-show Client</strong> : si le Client ne se présente pas au rendez-vous sans avoir annulé au préalable, l'acompte est intégralement conservé par l'Artiste.</li>
    <li><strong>No-show Artiste</strong> : si l'Artiste ne se présente pas au rendez-vous, l'intégralité de l'acompte est remboursée au Client et un signalement est transmis à l'équipe Ink&Pik.</li>
</ul>

<h3>4.5 Modalités de remboursement</h3>
<p>Les remboursements sont effectués via Stripe, sur le moyen de paiement original du Client. Le délai de traitement est de 5 à 10 jours ouvrés selon l'établissement bancaire du Client.</p>

<h2>5. Droit de rétractation</h2>

<p>Conformément à l'article L.221-28 du Code de la consommation, <strong>le droit de rétractation ne s'applique pas</strong> aux prestations de services de loisir devant être fournies à une date déterminée. La réservation d'une prestation de tatouage ou piercing à une date précise entre dans le cadre de cette exception.</p>

<p>Néanmoins, la politique d'annulation décrite à l'article 4 ci-dessus offre au Client une souplesse de remboursement proportionnelle au délai d'annulation.</p>

<h2>6. Obligations du Client</h2>

<h3>6.1 Information médicale</h3>
<p>Le Client s'engage à communiquer sincèrement toute information relative à son état de santé pouvant avoir une incidence sur la réalisation de la Prestation (allergies, traitements anticoagulants, grossesse, maladies de peau, etc.). Le Client reconnaît que la dissimulation d'informations médicales pertinentes décharge l'Artiste de toute responsabilité en cas de complications.</p>

<h3>6.2 Mineurs</h3>
<p>Conformément à l'article R.1311-11 du Code de la Santé Publique, le tatouage d'une personne mineure est interdit sans le consentement écrit d'un titulaire de l'autorité parentale. Le formulaire de consentement parental doit être signé et remis à l'Artiste avant toute Prestation. La Plateforme met à disposition un modèle de formulaire de consentement parental conforme.</p>

<h3>6.3 Soins post-prestation</h3>
<p>Le Client s'engage à respecter les consignes de soins post-prestation fournies par l'Artiste. Le non-respect de ces consignes ne saurait engager la responsabilité de l'Artiste ou d'Ink&Pik.</p>

<h2>7. Responsabilité</h2>

<p>La responsabilité d'Ink&Pik est strictement limitée à la fourniture de l'outil technique de mise en relation et de traitement des paiements. Ink&Pik ne saurait être tenu responsable :</p>
<ul>
    <li>De la qualité de la Prestation réalisée par l'Artiste</li>
    <li>Des réactions allergiques, infections ou complications liées à la Prestation</li>
    <li>Du non-respect par l'Artiste de ses obligations réglementaires</li>
    <li>Des litiges entre le Client et l'Artiste sur le résultat esthétique de la Prestation</li>
</ul>

<h2>8. Garantie et réclamation</h2>

<p>Toute réclamation relative à la Prestation doit être adressée directement à l'Artiste. Si le Client considère que l'Artiste a manqué à ses obligations d'hygiène ou de sécurité, il peut déposer un signalement via la Plateforme et/ou contacter l'Agence Régionale de Santé (ARS) compétente.</p>

<p>Pour les litiges liés au paiement (double débit, montant erroné), le Client peut contacter le support Ink&Pik à l'adresse <a href="mailto:support@inkpik.fr">support@inkpik.fr</a>.</p>

<h2>9. Droit applicable et juridiction</h2>

<p>Les présentes CGV sont régies par le droit français. Pour tout litige, le consommateur peut saisir les juridictions compétentes de son domicile, conformément à l'article R.631-3 du Code de la consommation, ou recourir au médiateur de la consommation désigné dans les <a href="{{ route('legal.mentions-legales') }}">Mentions légales</a>.</p>

@endsection
```

---

## PHASE 7 — POLITIQUE DE CONFIDENTIALITÉ (RGPD)

```blade
{{-- resources/views/legal/politique-confidentialite.blade.php --}}
@extends('legal.layout')

@section('legal-title', 'Politique de Confidentialité')
@section('legal-date', '01/03/2026')

@section('legal-content')

<p><strong>La présente politique de confidentialité décrit comment Ink&Pik collecte, utilise, stocke et protège vos données personnelles, conformément au Règlement Général sur la Protection des Données (RGPD — Règlement UE 2016/679) et à la loi Informatique et Libertés du 6 janvier 1978 modifiée.</strong></p>

<h2>1. Responsable du traitement</h2>

<p>
    <strong>[Raison sociale]</strong><br>
    Siège social : [adresse complète]<br>
    SIRET : [numéro]<br>
    Email DPO : <a href="mailto:dpo@inkpik.fr">dpo@inkpik.fr</a>
</p>

<h2>2. Données collectées</h2>

<h3>2.1 Données communes à tous les Utilisateurs</h3>
<table>
    <thead><tr><th>Donnée</th><th>Finalité</th><th>Base légale</th></tr></thead>
    <tbody>
        <tr><td>Nom, prénom</td><td>Identification, gestion du compte</td><td>Exécution du contrat (art. 6.1.b RGPD)</td></tr>
        <tr><td>Adresse email</td><td>Création de compte, communications transactionnelles</td><td>Exécution du contrat</td></tr>
        <tr><td>Mot de passe (hashé)</td><td>Sécurité de l'authentification</td><td>Exécution du contrat</td></tr>
        <tr><td>Adresse IP, données de navigation</td><td>Sécurité, prévention des fraudes, statistiques anonymisées</td><td>Intérêt légitime (art. 6.1.f RGPD)</td></tr>
        <tr><td>Avatar (photo)</td><td>Personnalisation du profil</td><td>Consentement (art. 6.1.a RGPD)</td></tr>
    </tbody>
</table>

<h3>2.2 Données spécifiques aux Artistes</h3>
<table>
    <thead><tr><th>Donnée</th><th>Finalité</th><th>Base légale</th></tr></thead>
    <tbody>
        <tr><td>Numéro SIRET</td><td>Vérification de l'immatriculation professionnelle</td><td>Obligation légale (art. 6.1.c RGPD)</td></tr>
        <tr><td>Déclaration ARS</td><td>Vérification de la conformité réglementaire</td><td>Obligation légale</td></tr>
        <tr><td>Certification hygiène et salubrité</td><td>Vérification de la conformité réglementaire</td><td>Obligation légale</td></tr>
        <tr><td>Compte Stripe Connect (identifiant)</td><td>Versement des paiements</td><td>Exécution du contrat</td></tr>
        <tr><td>Portfolio (photos d'œuvres)</td><td>Présentation sur la marketplace</td><td>Exécution du contrat</td></tr>
        <tr><td>Adresse professionnelle</td><td>Géolocalisation dans la marketplace</td><td>Exécution du contrat</td></tr>
    </tbody>
</table>

<h3>2.3 Données spécifiques aux Clients</h3>
<table>
    <thead><tr><th>Donnée</th><th>Finalité</th><th>Base légale</th></tr></thead>
    <tbody>
        <tr><td>Date de naissance</td><td>Vérification de la majorité</td><td>Obligation légale (art. R.1311-11 CSP)</td></tr>
        <tr><td>Numéro de téléphone</td><td>Contact pour les rendez-vous</td><td>Exécution du contrat</td></tr>
        <tr><td>Photos de référence (projet)</td><td>Communication avec l'Artiste</td><td>Exécution du contrat</td></tr>
    </tbody>
</table>

<h3>2.4 Données sensibles (catégories particulières — art. 9 RGPD)</h3>
<table>
    <thead><tr><th>Donnée</th><th>Finalité</th><th>Base légale</th></tr></thead>
    <tbody>
        <tr><td>Allergies, conditions médicales</td><td>Sécurité de la prestation (contre-indications)</td><td>Consentement explicite (art. 9.2.a RGPD) + motifs d'intérêt public en matière de santé publique (art. 9.2.i RGPD)</td></tr>
        <tr><td>Historique de tatouages</td><td>Suivi médical, gestion des fiches clients</td><td>Consentement explicite</td></tr>
        <tr><td>Consentements signés (dont parental)</td><td>Obligation légale (Code de la Santé Publique)</td><td>Obligation légale (art. 6.1.c RGPD)</td></tr>
    </tbody>
</table>

<p><strong>Important :</strong> les données de santé sont collectées uniquement dans le cadre de la relation entre le Client et l'Artiste. Elles sont accessibles uniquement à l'Artiste concerné (et au Studio dont il dépend, le cas échéant). Ink&Pik en tant qu'éditeur de la Plateforme n'accède pas aux données de santé des Clients, sauf obligation légale ou judiciaire.</p>

<h3>2.5 Traçabilité des actes</h3>
<p>Conformément au Code de la Santé Publique, les enregistrements de traçabilité (encres, aiguilles, numéros de lots) sont conservés par l'Artiste via la Plateforme. Ces données constituent des données de santé et bénéficient des protections renforcées décrites dans la présente politique.</p>

<h2>3. Sous-traitants et destinataires des données</h2>

<table>
    <thead><tr><th>Sous-traitant</th><th>Finalité</th><th>Localisation des données</th></tr></thead>
    <tbody>
        <tr><td>Stripe Payments Europe, Ltd.</td><td>Traitement des paiements</td><td>Union européenne (Irlande)</td></tr>
        <tr><td>[Hébergeur — ex: Hetzner, OVH, Scaleway]</td><td>Hébergement de la Plateforme et des données</td><td>Union européenne ([pays])</td></tr>
        <tr><td>[Service email — ex: Mailgun, Postmark]</td><td>Envoi des emails transactionnels</td><td>[UE / à vérifier]</td></tr>
    </tbody>
</table>

<p><strong>Aucun transfert de données hors de l'Union européenne</strong> n'est effectué, sauf garantie appropriée (clauses contractuelles types de la Commission européenne ou décision d'adéquation). Si un tel transfert devait être nécessaire, les Utilisateurs en seraient informés.</p>

<h2>4. Durée de conservation</h2>

<table>
    <thead><tr><th>Type de données</th><th>Durée de conservation</th><th>Fondement</th></tr></thead>
    <tbody>
        <tr><td>Données de compte (identité, email)</td><td>Durée de la relation contractuelle + 3 ans</td><td>Prescription civile</td></tr>
        <tr><td>Données de facturation / transactions</td><td>10 ans</td><td>Obligation comptable (Code de commerce)</td></tr>
        <tr><td>Données de traçabilité des actes</td><td>10 ans minimum</td><td>Code de la Santé Publique</td></tr>
        <tr><td>Fiches clients (santé)</td><td>Durée de la relation + 5 ans</td><td>Recommandation santé publique</td></tr>
        <tr><td>Consentements (parentaux et généraux)</td><td>Durée de la relation + 5 ans</td><td>Preuve du consentement</td></tr>
        <tr><td>Messages (chat)</td><td>1 an après la dernière activité de la conversation</td><td>Intérêt légitime (gestion des litiges)</td></tr>
        <tr><td>Logs de connexion / IP</td><td>1 an</td><td>LCEN (art. 6-II)</td></tr>
        <tr><td>Cookies</td><td>13 mois maximum</td><td>Recommandation CNIL</td></tr>
    </tbody>
</table>

<p>À l'expiration de ces durées, les données sont supprimées ou anonymisées de manière irréversible.</p>

<h2>5. Droits des Utilisateurs</h2>

<p>Conformément au RGPD, vous disposez des droits suivants :</p>

<ul>
    <li><strong>Droit d'accès</strong> (art. 15) : obtenir une copie de vos données personnelles</li>
    <li><strong>Droit de rectification</strong> (art. 16) : corriger des données inexactes ou incomplètes</li>
    <li><strong>Droit à l'effacement</strong> (art. 17) : demander la suppression de vos données, sous réserve des obligations légales de conservation</li>
    <li><strong>Droit à la limitation</strong> (art. 18) : restreindre le traitement dans certains cas</li>
    <li><strong>Droit à la portabilité</strong> (art. 20) : recevoir vos données dans un format structuré et lisible par machine</li>
    <li><strong>Droit d'opposition</strong> (art. 21) : vous opposer au traitement fondé sur l'intérêt légitime</li>
    <li><strong>Droit de retirer votre consentement</strong> à tout moment (pour les traitements fondés sur le consentement)</li>
    <li><strong>Droit d'introduire une réclamation</strong> auprès de la CNIL (3 Place de Fontenoy, 75007 Paris — <a href="https://www.cnil.fr" target="_blank" rel="noopener">www.cnil.fr</a>)</li>
</ul>

<p>Pour exercer vos droits, contactez : <a href="mailto:dpo@inkpik.fr">dpo@inkpik.fr</a>. Ink&Pik s'engage à répondre dans un délai de 30 jours.</p>

<p><strong>Exceptions :</strong> Les données de traçabilité des actes (encres, aiguilles, lots) ne peuvent pas être supprimées, même sur demande, car leur conservation est une obligation légale du Code de la Santé Publique. De même, les données de facturation doivent être conservées 10 ans.</p>

<h2>6. Sécurité des données</h2>

<p>Ink&Pik met en œuvre des mesures techniques et organisationnelles appropriées pour protéger vos données :</p>
<ul>
    <li>Chiffrement des données en transit (TLS/HTTPS)</li>
    <li>Hashage des mots de passe (Bcrypt)</li>
    <li>Authentification à deux facteurs (2FA) disponible</li>
    <li>Contrôle d'accès basé sur les rôles (RBAC)</li>
    <li>Pare-feu applicatif et middleware de sécurité (CSP, rate limiting, protection CSRF)</li>
    <li>Analyse antivirus des fichiers uploadés</li>
    <li>Journalisation des accès et monitoring de sécurité</li>
    <li>Sauvegardes régulières et chiffrées</li>
    <li>Données bancaires jamais stockées par Ink&Pik (traitées exclusivement par Stripe, certifié PCI DSS)</li>
</ul>

<p>En cas de violation de données, Ink&Pik s'engage à notifier la CNIL dans les 72 heures et à informer les personnes concernées si la violation est susceptible d'engendrer un risque élevé pour leurs droits et libertés, conformément aux articles 33 et 34 du RGPD.</p>

<h2>7. Profilage et décision automatisée</h2>

<p>Ink&Pik n'effectue aucun profilage ni prise de décision automatisée au sens de l'article 22 du RGPD. L'algorithme de recherche de la marketplace classe les résultats en fonction de critères objectifs (localisation, disponibilité, évaluations) sans discrimination.</p>

<h2>8. Modifications</h2>

<p>Ink&Pik peut modifier la présente politique. Les Utilisateurs seront informés par email de toute modification substantielle au moins 30 jours avant son entrée en vigueur.</p>

@endsection
```

---

## PHASE 8 — POLITIQUE DE COOKIES

```blade
{{-- resources/views/legal/politique-cookies.blade.php --}}
@extends('legal.layout')

@section('legal-title', 'Politique de Cookies')
@section('legal-date', '01/03/2026')

@section('legal-content')

<h2>1. Qu'est-ce qu'un cookie ?</h2>

<p>Un cookie est un petit fichier texte déposé sur votre terminal (ordinateur, smartphone, tablette) lors de la visite d'un site web. Il permet au site de mémoriser certaines informations pour faciliter votre navigation.</p>

<h2>2. Cookies utilisés par Ink&Pik</h2>

<h3>2.1 Cookies strictement nécessaires (pas de consentement requis)</h3>
<table>
    <thead><tr><th>Nom</th><th>Finalité</th><th>Durée</th></tr></thead>
    <tbody>
        <tr><td>XSRF-TOKEN</td><td>Protection contre les attaques CSRF</td><td>Session</td></tr>
        <tr><td>inkpik_session</td><td>Maintien de la session utilisateur</td><td>2 heures</td></tr>
        <tr><td>remember_web_*</td><td>Mémorisation de la connexion (« Se souvenir de moi »)</td><td>30 jours</td></tr>
        <tr><td>cookie_consent</td><td>Enregistrement du choix cookies de l'utilisateur</td><td>13 mois</td></tr>
    </tbody>
</table>

<h3>2.2 Cookies de mesure d'audience (consentement requis)</h3>
<table>
    <thead><tr><th>Nom</th><th>Finalité</th><th>Durée</th><th>Fournisseur</th></tr></thead>
    <tbody>
        <tr><td>[à compléter si analytics]</td><td>Mesure d'audience anonymisée</td><td>13 mois max</td><td>[Google Analytics / Matomo / Plausible]</td></tr>
    </tbody>
</table>

<p><em>Note : Ink&Pik privilégie des solutions de mesure d'audience respectueuses de la vie privée. Si Matomo est utilisé en auto-hébergé avec anonymisation des IP, aucun consentement n'est requis selon les recommandations de la CNIL.</em></p>

<h3>2.3 Cookies tiers (consentement requis)</h3>
<table>
    <thead><tr><th>Nom</th><th>Finalité</th><th>Durée</th><th>Fournisseur</th></tr></thead>
    <tbody>
        <tr><td>__stripe_*</td><td>Prévention de la fraude lors des paiements</td><td>Session / 1 an</td><td>Stripe</td></tr>
    </tbody>
</table>

<p><strong>Ink&Pik n'utilise aucun cookie publicitaire ni de tracking marketing.</strong></p>

<h2>3. Gestion de vos préférences</h2>

<p>Lors de votre première visite, un bandeau cookies vous permet d'accepter ou de refuser les cookies non essentiels. Vous pouvez modifier vos préférences à tout moment en cliquant sur le lien « Gérer les cookies » disponible en pied de page.</p>

<p>Vous pouvez également configurer votre navigateur pour bloquer les cookies :</p>
<ul>
    <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener">Google Chrome</a></li>
    <li><a href="https://support.mozilla.org/fr/kb/protection-renforcee-contre-pistage-firefox-ordinateur" target="_blank" rel="noopener">Mozilla Firefox</a></li>
    <li><a href="https://support.apple.com/fr-fr/guide/safari/sfri11471/mac" target="_blank" rel="noopener">Safari</a></li>
    <li><a href="https://support.microsoft.com/fr-fr/microsoft-edge/supprimer-les-cookies-dans-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" target="_blank" rel="noopener">Microsoft Edge</a></li>
</ul>

<p><strong>Attention :</strong> la désactivation des cookies strictement nécessaires peut empêcher le bon fonctionnement de la Plateforme (authentification, sécurité).</p>

<h2>4. Mise à jour</h2>

<p>Cette politique peut être mise à jour. La date de dernière modification est indiquée en haut de cette page.</p>

@endsection
```

---

## PHASE 9 — FOOTER ET LIENS

Ajouter les liens vers les pages légales dans TOUS les layouts (public, tattooer, pierceur, studio, client) :

```bash
# Trouver le footer dans les layouts
grep -rn "footer\|Footer\|<footer" resources/views/layouts/ --include="*.blade.php" | head -10
```

Créer un partial réutilisable :

```blade
{{-- resources/views/partials/footer-legal.blade.php --}}
<div class="border-t border-titane/10 mt-auto">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex flex-wrap gap-x-6 gap-y-2 justify-center text-xs text-titane">
            <a href="{{ route('legal.mentions-legales') }}" class="hover:text-beige-peau transition-colors">Mentions légales</a>
            <a href="{{ route('legal.cgu') }}" class="hover:text-beige-peau transition-colors">CGU</a>
            <a href="{{ route('legal.cgv-clients') }}" class="hover:text-beige-peau transition-colors">CGV</a>
            <a href="{{ route('legal.politique-confidentialite') }}" class="hover:text-beige-peau transition-colors">Confidentialité</a>
            <a href="{{ route('legal.politique-cookies') }}" class="hover:text-beige-peau transition-colors">Cookies</a>
        </div>
        <p class="text-center text-xs text-titane/50 mt-3">© {{ date('Y') }} Ink&Pik — Tous droits réservés</p>
    </div>
</div>
```

Inclure ce partial dans CHAQUE layout :

```blade
{{-- Dans chaque layout, avant la fermeture du </body> ou du container principal --}}
@include('partials.footer-legal')
```

```bash
git add -A && git commit -m "feat(legal): footer légal dans tous les layouts"
```

---

## PHASE 10 — CASE À COCHER INSCRIPTION

Ajouter les cases à cocher obligatoires dans TOUS les formulaires d'inscription :

```bash
# Trouver les formulaires d'inscription
grep -rn "RegisterTattooer\|RegisterPierceur\|RegisterClient\|RegisterStudio" app/Livewire/ --include="*.php" | head -10
find resources/views -name "*register*" -o -name "*inscription*" | head -10
```

Pour CHAQUE formulaire d'inscription, ajouter avant le bouton de soumission :

```blade
<div class="space-y-3 mt-4">
    <label class="flex items-start gap-3 cursor-pointer">
        <input type="checkbox" name="accept_cgu" wire:model="acceptCgu" required
            class="mt-0.5 w-4 h-4 rounded border-titane/50 bg-noir-profond text-beige-peau focus:ring-beige-peau">
        <span class="text-xs text-titane leading-relaxed">
            J'ai lu et j'accepte les 
            <a href="{{ route('legal.cgu') }}" target="_blank" class="text-beige-peau hover:underline">Conditions Générales d'Utilisation</a>
            @if (/* artiste ou studio */)
                et les <a href="{{ route('legal.cgv-artistes') }}" target="_blank" class="text-beige-peau hover:underline">Conditions Générales de Vente</a>
            @endif
            <span class="text-rouge-alerte">*</span>
        </span>
    </label>

    <label class="flex items-start gap-3 cursor-pointer">
        <input type="checkbox" name="accept_privacy" wire:model="acceptPrivacy" required
            class="mt-0.5 w-4 h-4 rounded border-titane/50 bg-noir-profond text-beige-peau focus:ring-beige-peau">
        <span class="text-xs text-titane leading-relaxed">
            J'ai lu et j'accepte la 
            <a href="{{ route('legal.politique-confidentialite') }}" target="_blank" class="text-beige-peau hover:underline">Politique de confidentialité</a>
            <span class="text-rouge-alerte">*</span>
        </span>
    </label>
</div>
```

IMPORTANT : 
- Ajouter les propriétés `$acceptCgu` et `$acceptPrivacy` dans les composants Livewire
- Ajouter la validation `'acceptCgu' => 'accepted', 'acceptPrivacy' => 'accepted'`
- Stocker la date d'acceptation en base (colonnes `cgu_accepted_at` et `privacy_accepted_at` sur la table users)

Si ces colonnes n'existent pas :

```bash
php artisan make:migration add_legal_acceptance_to_users_table
```

```php
Schema::table('users', function (Blueprint $table) {
    $table->timestamp('cgu_accepted_at')->nullable();
    $table->timestamp('privacy_accepted_at')->nullable();
});
```

```bash
git add -A && git commit -m "feat(legal): cases à cocher CGU + confidentialité à l'inscription"
```

---

## VÉRIFICATION FINALE

```bash
echo "=== VÉRIFICATION PAGES LÉGALES ==="

# V1. Routes
php artisan route:list --name="legal" --columns=method,uri,name 2>&1

# V2. Vues
ls -la resources/views/legal/

# V3. Compilation
php artisan view:clear
php artisan route:list 2>&1 | head -3

# V4. Footer
grep -rn "footer-legal\|partials.footer" resources/views/layouts/ --include="*.blade.php" | wc -l
echo "Doit être > 0 dans chaque layout"

# V5. Cases inscription
grep -rn "accept_cgu\|acceptCgu\|cgu_accepted" app/Livewire/ --include="*.php" | head -5

# V6. Colonnes users
php artisan tinker --execute="
  echo 'cgu_accepted_at: ' . (Schema::hasColumn('users', 'cgu_accepted_at') ? 'OK' : 'ABSENT');
  echo PHP_EOL . 'privacy_accepted_at: ' . (Schema::hasColumn('users', 'privacy_accepted_at') ? 'OK' : 'ABSENT');
"

echo "=== PAGES LÉGALES TERMINÉES ==="
```

---

## ⚠️ RÈGLES CRITIQUES

1. **Tout le texte juridique doit être EN FRANÇAIS** — obligation légale (Code de la consommation)
2. **Les valeurs entre [crochets] doivent être remplacées** par les vraies informations du fondateur
3. **Ne JAMAIS copier-coller des CGU d'un autre site** — ce prompt génère du contenu original adapté à Ink&Pik
4. **Adapter le layout** au layout public réel du projet (vérifier Phase 0)
5. **Les couleurs** (noir-profond, ivoire-text, titane, beige-peau, rouge-alerte) doivent correspondre au design system existant
6. **Commit après chaque phase** (8-10 commits)
7. **Cases à cocher = preuve légale** — la date d'acceptation doit être stockée en DB
8. **Stripe est PCI DSS compliant** — Ink&Pik ne stocke JAMAIS les données de carte bancaire

## ⚠️ AVERTISSEMENT JURIDIQUE

Ce prompt génère des documents juridiques basés sur les recherches et les meilleures pratiques identifiées. Ces documents constituent une base solide mais **ne remplacent pas un avis juridique professionnel**. Il est FORTEMENT recommandé de faire relire ces documents par un avocat spécialisé en droit du numérique avant le lancement commercial. Les points sensibles :
- Les pourcentages de remboursement dans les CGV Clients (à valider)
- Les clauses de limitation de responsabilité
- La conformité RGPD pour les données de santé (potentiellement soumis à AIPD — Analyse d'Impact)
- Le statut d'intermédiaire technique (vs. éditeur de contenu)
- Le médiateur de la consommation (à désigner officiellement)
