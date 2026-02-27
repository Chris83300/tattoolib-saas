<x-mail::message>
# Votre essai se termine bientôt

Bonjour,

L'essai gratuit de **{{ $studio->name }}** se termine dans **{{ $daysLeft }} jour{{ $daysLeft > 1 ? 's' : '' }}**.

@if ($progress < 100)
Vous avez complété **{{ $progress }}%** de la configuration. Terminez la mise en place pour tirer le maximum de votre essai !
@else
Vous avez complété toute la configuration — votre studio est prêt ! 🎉
@endif

Pour continuer à utiliser toutes les fonctionnalités Studio après la fin de l'essai, activez votre abonnement :

<x-mail::button :url="$billingUrl">
Activer l'abonnement — 79,99€/mois
</x-mail::button>

**Ce qui est inclus :**
- Gestion complète de votre studio
- 1 artiste inclus
- Dashboard avancé et traçabilité
- Visibilité sur la marketplace
- Artistes supplémentaires à 39,99€/mois
- Sans engagement, résiliable à tout moment

Cordialement,
L'équipe Ink&Pik
</x-mail::message>
