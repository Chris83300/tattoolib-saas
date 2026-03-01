# Auto-complétion des Rendez-vous

## 📋 Processus automatique

### 🕐 Fréquence
- **Toutes les heures** : Vérification des rendez-vous
- **Tous les jours à minuit** : Vérification complète

### 🔄 Logique de traitement

#### 1. Rendez-vous avec Appointment (système moderne)
```php
Appointment::where('status', 'scheduled')
    ->where('end_datetime', '<', now()->subDay())
```
- **Condition** : RDV terminé depuis plus de 24h
- **Action** : Passe en statut `completed`
- **Résultat** : RDV marqué comme terminé (neutre)

#### 2. Booking Requests sans Appointment (système legacy)
```php
BookingRequest::where('status', 'date_confirmed')
    ->whereNotNull('appointment_datetime')
    ->where('appointment_datetime', '<', now()->subHours(24))
```
- **Condition** : RDV confirmé depuis plus de 24h
- **Action** : Passe en statut `completed`
- **Résultat** : Demande marquée comme terminée (neutre)

### 📊 Statuts gérés

| Statut initial | Condition | Statut final | Description |
|---------------|-----------|---------------|-------------|
| `scheduled` | RDV terminé depuis > 24h | `completed` | RDV auto-complété |
| `date_confirmed` | RDV passé depuis > 24h | `completed` | Demande auto-complétée |

### 🎯 Comportement attendu

- **J+1 après le RDV** : Si l'artiste n'a pas cliqué sur "Validé", "No-show" ou autre, le système passe automatiquement le RDV en statut `completed`
- **Neutre** : Le système ne présuppose pas si le client est venu ou non
- **Final** : Le statut `completed` est terminal pour permettre le nettoyage

### 🔧 Commandes disponibles

```bash
# Exécuter manuellement
php artisan appointments:auto-complete

# Vérifier les statistiques
php artisan tinker --execute="(new App\Console\Commands\AutoCompleteAppointments())->getAutoCompletionStats()"

# Voir les complétions à venir
php artisan tinker --execute="(new App\Console\Commands\AutoCompleteAppointments())->getUpcomingAutoCompletions()"
```

### 📝 Logs

Chaque auto-complétion est loggée avec :
- ID du rendez-vous/demande
- Date/heure du RDV
- Raison de l'auto-complétion
- Timestamp de la complétion

### ⚡ Performance

- Exécution en arrière-plan (`runInBackground`)
- Protection contre les exécutions multiples (`withoutOverlapping`)
- Transaction DB pour la cohérence des données
