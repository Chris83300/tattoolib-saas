@extends('legal.layout')

@section('legal-title', 'Conditions Générales de Vente — Clients')
@section('legal-date', '01/03/2026')

@section('legal-content')

<p><strong>Les présentes CGV régissent les conditions de réservation et de paiement des prestations d'art corporel via la plateforme Ink&amp;Pik.</strong></p>

<h2>1. Rôle d'Ink&amp;Pik</h2>

<p>Ink&amp;Pik agit exclusivement en qualité de <strong>plateforme de mise en relation</strong> entre les Clients et les Artistes. Ink&amp;Pik n'est pas partie au contrat de prestation et n'est pas le prestataire de la prestation de tatouage, piercing ou modification corporelle. Le contrat de prestation est conclu directement entre le Client et l'Artiste.</p>

<p>Ink&amp;Pik fournit les outils techniques de gestion des réservations et de traitement des paiements via Stripe Connect.</p>

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

<p>L'acompte est versé directement à l'Artiste (ou au Studio en mode centralisé) via Stripe Connect. Ink&amp;Pik ne détient pas les fonds.</p>

<h3>3.2 Solde</h3>
<p>Le solde de la prestation (prix total — acompte) est payable avant le rendez-vous via la Plateforme. Le montant exact est convenu entre le Client et l'Artiste.</p>

<h3>3.3 Moyens de paiement</h3>
<p>Les paiements sont traités par Stripe et acceptent les cartes bancaires (Visa, Mastercard, CB, American Express). Ink&amp;Pik ne stocke aucune donnée de carte bancaire. Le traitement est conforme à la norme PCI DSS.</p>

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
        <tr><td>Plus de 30 jours</td><td>100% de l'acompte remboursé</td></tr>
        <tr><td>Si au minimum 1 dessin est envoyé</td><td>80% de l'acompte remboursé</td></tr>
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
    <li><strong>No-show Artiste</strong> : si l'Artiste ne se présente pas au rendez-vous, l'intégralité de l'acompte est remboursée au Client et un signalement est transmis à l'équipe Ink&amp;Pik.</li>
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
<p>Le Client s'engage à respecter les consignes de soins post-prestation fournies par l'Artiste. Le non-respect de ces consignes ne saurait engager la responsabilité de l'Artiste ou d'Ink&amp;Pik.</p>

<h2>7. Responsabilité</h2>

<p>La responsabilité d'Ink&amp;Pik est strictement limitée à la fourniture de l'outil technique de mise en relation et de traitement des paiements. Ink&amp;Pik ne saurait être tenu responsable :</p>
<ul>
    <li>De la qualité de la Prestation réalisée par l'Artiste</li>
    <li>Du respect des normes d'hygiène et de sécurité par l'Artiste ou le salon</li>
    <li>Des réactions allergiques, infections ou complications liées à la Prestation</li>
    <li>Du non-respect par l'Artiste de ses obligations réglementaires</li>
    <li>Des litiges entre le Client et l'Artiste sur le résultat esthétique de la Prestation</li>
</ul>

<h2>8. Garantie et réclamation</h2>

<p>Toute réclamation relative à la Prestation doit être adressée directement à l'Artiste. Si le Client considère que l'Artiste a manqué à ses obligations d'hygiène ou de sécurité, il peut déposer un signalement via la Plateforme et/ou contacter l'Agence Régionale de Santé (ARS) compétente.</p>

<p>Pour les litiges liés au paiement (double débit, montant erroné), le Client peut contacter le support Ink&amp;Pik à l'adresse <a href="mailto:support@inkpik.fr">support@inkpik.fr</a>.</p>

<h2>9. Droit applicable et juridiction</h2>

<p>Les présentes CGV sont régies par le droit français. Pour tout litige, le consommateur peut saisir les juridictions compétentes de son domicile, conformément à l'article R.631-3 du Code de la consommation, ou recourir au médiateur de la consommation désigné dans les <a href="{{ route('legal.mentions-legales') }}">Mentions légales</a>.</p>

@endsection
