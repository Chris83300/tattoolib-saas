# STRIPE — Configuration manuelle requise

## Coupons à créer dans le Dashboard Stripe

### BETA-LAUNCH-30
- **ID** : `BETA-LAUNCH-30`
- **Réduction** : 30%
- **Durée** : `forever` (tant que l'abonnement reste actif)
- **Usage** : Appliqué automatiquement aux bêta-testeurs lors de la souscription
- **Créer dans** : Stripe Dashboard → Products → Coupons → Create coupon

## Price IDs à créer et renseigner dans .env

| Variable | Description | Montant |
|----------|-------------|---------|
| `STRIPE_PRICE_ID_STARTER` | Plan Starter mensuel | 9,99€/mois |
| `STRIPE_PRICE_ID_PRO` | Plan Pro mensuel | 29,99€/mois |
| `STRIPE_PRICE_ID_STUDIO` | Plan Studio mensuel (1 artiste) | 59,99€/mois |
| `STRIPE_PRICE_ID_STUDIO_EXTRA` | Artiste supplémentaire Studio | 24,99€/mois |

**Créer dans** : Stripe Dashboard → Products → Add product
