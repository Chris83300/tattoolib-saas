@extends('legal.layout')

@section('legal-title', 'Politique de Cookies')
@section('legal-date', '01/03/2026')

@section('legal-content')

<h2>1. Qu'est-ce qu'un cookie ?</h2>

<p>Un cookie est un petit fichier texte déposé sur votre terminal (ordinateur, smartphone, tablette) lors de la visite d'un site web. Il permet au site de mémoriser certaines informations pour faciliter votre navigation.</p>

<h2>2. Cookies utilisés par Ink&amp;Pik</h2>

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

<p><em>Note : Ink&amp;Pik privilégie des solutions de mesure d'audience respectueuses de la vie privée. Si Matomo est utilisé en auto-hébergé avec anonymisation des IP, aucun consentement n'est requis selon les recommandations de la CNIL.</em></p>

<h3>2.3 Cookies tiers (consentement requis)</h3>
<table>
    <thead><tr><th>Nom</th><th>Finalité</th><th>Durée</th><th>Fournisseur</th></tr></thead>
    <tbody>
        <tr><td>__stripe_*</td><td>Prévention de la fraude lors des paiements</td><td>Session / 1 an</td><td>Stripe</td></tr>
    </tbody>
</table>

<p><strong>Ink&amp;Pik n'utilise aucun cookie publicitaire ni de tracking marketing.</strong></p>

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
