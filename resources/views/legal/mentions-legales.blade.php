@extends('legal.layout')

@section('legal-title', 'Mentions légales')
@section('legal-date', '01/03/2026')

@section('legal-content')

{{-- IMPORTANT : Remplacer TOUTES les valeurs entre [crochets] par les vraies informations --}}

<h2>1. Éditeur du site</h2>

<p>Le site <strong>Ink&amp;Pik</strong>, accessible à l'adresse <strong>[www.inkpik.fr]</strong>, est édité par :</p>

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

<p>Ink&amp;Pik n'encaisse pas directement les fonds pour le compte des artistes. Les paiements transitent via Stripe Connect, conformément à la réglementation européenne sur les services de paiement (DSP2 — Directive 2015/2366/UE). Ink&amp;Pik agit en qualité de plateforme de mise en relation et non d'intermédiaire financier.</p>

<h2>4. Activité de la plateforme</h2>

<p>Ink&amp;Pik est une plateforme de mise en relation en ligne (marketplace) entre des professionnels de l'art corporel (tatoueurs, pierceurs, body modifiers) et des clients particuliers. Ink&amp;Pik n'exerce pas elle-même d'activité de tatouage ou de piercing et n'est pas soumise aux obligations du Code de la Santé Publique applicables aux professionnels du secteur (articles R.1311-1 et suivants du CSP).</p>

<p>Ink&amp;Pik vérifie, dans la mesure du possible, que les artistes référencés disposent d'un numéro SIRET valide et déclarent être en conformité avec les obligations réglementaires applicables (déclaration ARS, certification hygiène et salubrité). Toutefois, la plateforme ne saurait être tenue responsable du non-respect par un artiste de ses obligations légales et réglementaires.</p>

<h2>5. Propriété intellectuelle</h2>

<p>L'ensemble du contenu du site (textes, graphismes, logos, icônes, images, structure, logiciel, base de données) est la propriété exclusive d'Ink&amp;Pik ou de ses partenaires et est protégé par les lois françaises et internationales relatives à la propriété intellectuelle.</p>

<p>Les portfolios et œuvres publiés par les artistes restent la propriété intellectuelle de leurs auteurs respectifs. En publiant leur contenu sur la plateforme, les artistes concèdent à Ink&amp;Pik une licence d'utilisation non exclusive à des fins de promotion et d'affichage sur la marketplace.</p>

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

<p>Les présentes mentions légales sont soumises au droit français. En cas de litige, et à défaut de résolution amiable, les tribunaux français compétents seront ceux du ressort du siège social d'Ink&amp;Pik.</p>

@endsection
