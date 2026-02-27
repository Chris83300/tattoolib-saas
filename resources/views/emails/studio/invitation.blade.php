<x-mail::message>
# Vous êtes invité !

**{{ $studio->name }}** vous invite à rejoindre son studio sur Ink&Pik en tant que **{{ $artisanType }}**.

En acceptant cette invitation, vous bénéficierez de :
- Un profil professionnel complet
- La gestion de vos réservations et clients
- La visibilité sur la marketplace Ink&Pik

<x-mail::button :url="$invitationUrl">
Accepter l'invitation
</x-mail::button>

Ce lien est valable 7 jours.

Cordialement,
L'équipe Ink&Pik
</x-mail::message>
