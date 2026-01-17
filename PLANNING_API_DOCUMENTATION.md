# 🗓️ Système de Planning Complet TattooLib - Documentation API

## 📋 Vue d'ensemble

Cette documentation présente les nouvelles fonctionnalités du système de planning complet implémenté selon le cahier des charges validé.

## 🚀 Nouvelles Routes API

### Planning Tatoueur

#### Dashboard Planning
```http
GET /api/planning/dashboard
```
**Description**: Récupère le planning complet du tatoueur (jour/semaine/mois)
**Paramètres**:
- `start_date` (optional): Date de début
- `end_date` (optional): Date de fin  
- `view` (optional): `day|week|month` (défaut: `week`)

**Réponse**:
```json
{
  "period": {
    "start": "2026-01-16",
    "end": "2026-01-23",
    "view": "week"
  },
  "availabilities_by_date": {
    "2026-01-16": [
      {
        "id": 1,
        "date": "2026-01-16",
        "start_time": "09:00",
        "end_time": "12:00",
        "type": "available",
        "source": "working_hours"
      }
    ]
  },
  "appointments": [...],
  "statistics": {
    "total_appointments": 5,
    "total_hours_booked": 12.5
  }
}
```

#### Bloquer un Créneau Manuellement
```http
POST /api/planning/block-slot
```
**Corps**:
```json
{
  "date": "2026-01-16",
  "start_time": "14:00",
  "end_time": "16:00",
  "type": "blocked",
  "notes": "Rendez-vous personnel"
}
```

#### Créer un RDV Externe
```http
POST /api/planning/create-external-appointment
```
**Corps**:
```json
{
  "date": "2026-01-16",
  "start_time": "10:00",
  "end_time": "13:00",
  "source": "external_walk_in",
  "client_name": "Marie Dupont",
  "notes": "Pris en boutique"
}
```

#### Libérer un Créneau
```http
DELETE /api/planning/release-slot/{availability}
```

### Consultation Disponibilités (Clients)

#### Dates Disponibles
```http
GET /api/planning/tattooers/{tattooerId}/available-dates
```
**Paramètres**:
- `start_date` (optional): Date de début (défaut: aujourd'hui)
- `months` (optional): Nombre de mois (défaut: 6, max: 12)

**Réponse**:
```json
{
  "period": {
    "start": "2026-01-16",
    "end": "2026-07-16"
  },
  "available_dates": [
    {
      "date": "2026-01-20",
      "day_name": "lundi",
      "is_today": false,
      "is_weekend": false,
      "available_slots_count": 3,
      "total_available_minutes": 480
    }
  ],
  "total_dates": 45
}
```

#### Créneaux pour une Date
```http
GET /api/planning/tattooers/{tattooerId}/slots-for-date?date=2026-01-20
```

**Réponse**:
```json
{
  "date": "2026-01-20",
  "available_slots": [
    {
      "date": "2026-01-20",
      "start_time": "09:00",
      "end_time": "12:00",
      "duration_minutes": 180
    },
    {
      "date": "2026-01-20", 
      "start_time": "14:00",
      "end_time": "18:00",
      "duration_minutes": 240
    }
  ],
  "total_slots": 2
}
```

### Booking Requests Amélioré

#### Créer une Demande (avec préférences)
```http
POST /api/booking-requests
```
**Corps**:
```json
{
  "tattooer_id": 1,
  "tattoo_size": "moyen",
  "body_zone": "bras",
  "description": "Tatouage dragon japonais",
  "preferred_date": "2026-01-20",
  "preferred_time_slot": "morning",
  "preferred_time_notes": "De préférence vers 10h si possible",
  "estimated_budget": 300
}
```

#### Accepter + Fixer Heure Exacte
```http
POST /api/booking-requests/{bookingRequest}/accept
```
**Corps**:
```json
{
  "scheduled_date": "2026-01-20",
  "scheduled_start_time": "10:00",
  "scheduled_duration_minutes": 180,
  "total_price": 350,
  "deposit_rate": 30,
  "deposit_deadline_hours": 72
}
```

#### Payer l'Acompte
```http
POST /api/booking-requests/{bookingRequest}/confirm-deposit
```

#### Vérifier Demandes Expirées (Cron)
```http
GET /api/booking-requests/check-expired
```

## 📊 Modèles de Données

### Availability
**Nouveaux types**:
- `external_booking`: RDV pris hors plateforme
- `blocked`: Bloqué manuellement par tatoueur

**Nouveau champ `source`**:
- `working_hours`: Généré depuis horaires de travail
- `manual`: Créé manuellement
- `appointment`: Lié à un RDV confirmé
- `external`: RDV externe

### BookingRequest
**Nouveaux champs**:
- `preferred_date`: Date spécifique souhaitée
- `preferred_time_slot`: `morning|afternoon|evening|anytime`
- `preferred_time_notes`: Notes sur préférences horaires
- `scheduled_start_time/end_time`: Heure exacte fixée par tatoueur
- `scheduled_duration_minutes`: Durée en minutes
- `total_price`: Prix total (remplace estimated_total_price)
- `deposit_deadline`: Délai de paiement acompte
- `accepted_at/deposit_paid_at/expired_at`: Timestamps workflow

### Appointment
**Nouveaux champs**:
- `appointment_date`: Date du RDV (ajoutée)
- `source`: `platform|external_walk_in|external_phone|external_social`
- `external_source_notes`: Notes sur source externe

## 🔄 Workflow Complet

1. **Client demande RDV**
   - Spécifie date préférée et créneau horaire
   - Vérification automatique disponibilité

2. **Tatoueur accepte**
   - Fixe heure exacte et durée
   - Définit prix et taux d'acompte
   - Définit délai de paiement

3. **Client paie acompte**
   - Dans le délai imparti
   - Sinon demande expire automatiquement

4. **RDV confirmé**
   - Créneau bloqué dans planning
   - Passage au workflow design

## ⚙️ Tâches Automatisées

### Job CheckExpiredBookingRequests
**Exécution**: Toutes les heures (cron)
**Action**: Marque les demandes expirées

### Commande Manuelle
```bash
php artisan booking-requests:check-expired
```

## 🛠️ Migration Database

Les migrations suivantes ont été créées:
- `2026_01_21_100000_update_availabilities_table.php`
- `2026_01_21_110000_update_booking_requests_table.php` 
- `2026_01_21_120000_update_appointments_table.php`

## 📝 Notes d'Implémentation

- **Performance**: Utilisation d'insertions groupées pour les availabilities
- **Sécurité**: Vérification des autorisations sur toutes les routes
- **Logging**: Traçabilité des actions importantes
- **Extensibilité**: Architecture modulaire pour futures évolutions

## 🎯 Prochaines Étapes

1. **Intégration Stripe**: Finaliser les paiements d'acompte
2. **Notifications**: Emails/Notifications push pour les événements
3. **Frontend**: Intégration avec l'interface utilisateur
4. **Tests**: Suite de tests complète
5. **Monitoring**: Métriques et alertes de performance
