<x-mail::message>
# Bienvenue sur Ink&Pik, {{ $name }} !

**{{ $studio->name }}** a créé votre compte professionnel en tant que **{{ $artisanType }}**.

Voici vos identifiants de connexion :

- **Email** : {{ $email }}
- **Mot de passe temporaire** : {{ $tempPassword }}

**⚠️ Pensez à changer votre mot de passe dès votre première connexion.**

<x-mail::button :url="$loginUrl">
Se connecter
</x-mail::button>

Cordialement,
L'équipe Ink&Pik
</x-mail::message>
