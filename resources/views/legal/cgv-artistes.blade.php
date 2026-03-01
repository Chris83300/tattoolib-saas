@extends('legal.layout')

@section('legal-title', 'Conditions Générales de Vente — Artistes & Studios')
@section('legal-date', '01/03/2026')

@section('legal-content')

<p><strong>Les présentes CGV régissent la relation commerciale entre Ink&amp;Pik et les Artistes/Studios pour les abonnements payants et la commission sur les prestations.</strong></p>

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

<p>En cas d'échec du paiement, Ink&amp;Pik se réserve le droit de suspendre l'accès aux fonctionnalités PRO après un délai de grâce de 7 jours et 2 tentatives de prélèvement.</p>

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

<h3>6.2 Résiliation par Ink&amp;Pik</h3>
<p>Ink&amp;Pik se réserve le droit de suspendre ou résilier un compte Artiste/Studio en cas de :</p>
<ul>
    <li>Non-respect des CGU ou des présentes CGV</li>
    <li>Fourniture d'informations frauduleuses (faux SIRET, fausse certification hygiène)</li>
    <li>Signalements multiples de Clients (manquement à l'hygiène, comportement inapproprié)</li>
    <li>Non-paiement de l'abonnement après les tentatives de relance</li>
    <li>Inactivité prolongée (aucune transaction depuis 12 mois pour un compte FREE)</li>
</ul>

<h2>7. Responsabilité</h2>

<p>Ink&amp;Pik n'est pas responsable des pertes de revenus de l'Artiste liées à une indisponibilité temporaire de la Plateforme. La responsabilité d'Ink&amp;Pik est limitée au montant total des abonnements versés par l'Artiste/Studio au cours des 12 derniers mois.</p>

<h2>8. Données et portabilité</h2>

<p>L'Artiste/Studio peut à tout moment exporter ses données (fiches clients, historique des prestations, traçabilité) depuis son espace personnel, conformément au droit à la portabilité des données (article 20 du RGPD).</p>

<h2>9. Droit applicable</h2>

<p>Les présentes CGV sont régies par le droit français. En cas de litige entre professionnels, les tribunaux de commerce du ressort du siège social d'Ink&amp;Pik sont compétents.</p>

@endsection
