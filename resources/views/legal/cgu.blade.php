@extends('legal.layout')

@section('legal-title', "Conditions Générales d'Utilisation (CGU)")
@section('legal-date', '01/03/2026')

@section('legal-content')

<p><strong>Les présentes Conditions Générales d'Utilisation régissent l'accès et l'utilisation de la plateforme Ink&amp;Pik par l'ensemble des utilisateurs. En créant un compte ou en utilisant le site, vous acceptez sans réserve les présentes CGU.</strong></p>

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

<p>Ink&amp;Pik est une plateforme de mise en relation entre des Artistes professionnels de l'art corporel et des Clients. La Plateforme fournit les outils techniques permettant la publication de profils, la recherche d'artistes, la gestion de réservations, la messagerie et le traitement des paiements.</p>

<p><strong>Ink&amp;Pik n'est pas partie au contrat de prestation entre l'Artiste et le Client.</strong> La Plateforme agit exclusivement en qualité d'intermédiaire technique. La responsabilité de la réalisation de la Prestation, de sa qualité, du respect des normes d'hygiène et de sécurité incombe exclusivement à l'Artiste.</p>

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

<p>L'Utilisateur est responsable de la confidentialité de ses identifiants. Toute utilisation frauduleuse doit être signalée immédiatement à l'adresse <a href="mailto:securite@inkpik.fr">securite@inkpik.fr</a>. Ink&amp;Pik propose l'authentification à deux facteurs (2FA) et recommande fortement son activation.</p>

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

<p>Les Artistes sont responsables des contenus qu'ils publient (portfolio, description, tarifs). En publiant du contenu sur la Plateforme, l'Artiste garantit qu'il en détient les droits de propriété intellectuelle et concède à Ink&amp;Pik une licence mondiale, non exclusive, gratuite, pour la durée d'utilisation du compte, aux fins d'affichage, de promotion et de référencement sur la Plateforme.</p>

<p>Ink&amp;Pik se réserve le droit de supprimer tout contenu contraire aux présentes CGU sans préavis.</p>

<h2>7. Système de badges et vérification</h2>

<p>Ink&amp;Pik propose un système de badges de conformité (vérification SIRET, certification hygiène, déclaration ARS). Ces badges sont attribués sur la base des justificatifs fournis par l'Artiste et vérifiés par l'équipe Ink&amp;Pik. Ils constituent une indication de conformité déclarative et ne sauraient engager la responsabilité d'Ink&amp;Pik quant au respect effectif des normes par l'Artiste.</p>

<h2>8. Avis et évaluations</h2>

<p>Les Clients peuvent laisser un avis après la réalisation d'une Prestation. Les avis doivent être sincères, fondés sur une expérience réelle et respectueux. Ink&amp;Pik se réserve le droit de modérer et supprimer les avis frauduleux, injurieux ou contraires aux bonnes mœurs.</p>

<h2>9. Réclamations et litiges</h2>

<p>Tout litige relatif à la qualité d'une Prestation doit être adressé directement à l'Artiste. Ink&amp;Pik peut intervenir en qualité de médiateur si les parties le souhaitent, via le système de réclamation intégré à la Plateforme.</p>

<p>En cas de comportement frauduleux, de non-respect des normes d'hygiène ou de mise en danger, le Client peut signaler l'Artiste via la Plateforme. Ink&amp;Pik se réserve le droit de suspendre ou supprimer le compte de tout Artiste faisant l'objet de signalements avérés.</p>

<h2>10. Responsabilité</h2>

<p>Ink&amp;Pik s'engage à assurer la disponibilité de la Plateforme dans la mesure du raisonnable (obligation de moyens). La Plateforme peut faire l'objet d'interruptions pour maintenance.</p>

<p><strong>Ink&amp;Pik décline toute responsabilité :</strong></p>
<ul>
    <li>Quant à la qualité, la sécurité ou la conformité des Prestations réalisées par les Artistes</li>
    <li>En cas de litige direct entre un Client et un Artiste</li>
    <li>En cas de dommage résultant d'une information inexacte fournie par un Utilisateur</li>
    <li>En cas d'indisponibilité temporaire de la Plateforme</li>
</ul>

<h2>11. Modification des CGU</h2>

<p>Ink&amp;Pik se réserve le droit de modifier les présentes CGU. Les Utilisateurs seront informés par email et/ou notification dans l'application au moins 30 jours avant l'entrée en vigueur des modifications. L'utilisation continue de la Plateforme après cette date vaut acceptation des nouvelles CGU.</p>

<h2>12. Droit applicable</h2>

<p>Les présentes CGU sont régies par le droit français. En cas de litige, et après tentative de résolution amiable, les tribunaux compétents seront ceux du ressort du siège social d'Ink&amp;Pik, sauf disposition légale impérative contraire (notamment en faveur du consommateur).</p>

@endsection
