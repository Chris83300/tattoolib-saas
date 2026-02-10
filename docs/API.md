# 📡 Ink&Pik API Documentation

Documentation complète de l'API REST Ink&Pik (Sanctum authentication).

## Base URL
```
Production: https://api.inkpik.fr/api
Staging: https://staging-api.inkpik.fr/api
Development: http://localhost:8000/api
```

## Authentication

Toutes les routes protégées nécessitent un token Sanctum dans le header :
```
Authorization: Bearer {token}
```

### Obtenir un token

**POST** `/login`

```json
{
  "email": "client@inkpik.fr",
  "password": "password"
}
```

**Response 200**:
```json
{
  "user": {
    "id": 1,
    "email": "client@inkpik.fr",
    "role": "client"
  },
  "token": "1|abc123def456..."
}
```

## Endpoints

### 🔹 Booking Requests

#### Créer une demande

**POST** `/booking-requests`

**Headers**:
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body**:
```json
{
  "bookable_type": "App\\Models\\Tattooer",
  "bookable_id": 5,
  "tattoo_size": "medium",
  "body_zone": "bras",
  "description": "Dragon japonais style traditionnel",
  "preferred_timeframe": "dans_1_mois",
  "preferred_days": ["lundi", "mardi"],
  "budget_range": "200-400"
}
```

**Response 201**:
```json
{
  "message": "Demande de réservation créée avec succès",
  "data": {
    "id": 42,
    "status": "pending",
    "client_id": 8,
    "bookable_id": 5,
    "bookable_type": "App\\Models\\Tattooer",
    "tattoo_size": "medium",
    "body_zone": "bras",
    "description": "Dragon japonais style traditionnel",
    "created_at": "2025-02-05T10:30:00Z"
  }
}
```

**Errors**:
- 401 : Non authentifié
- 403 : Utilisateur n'est pas client
- 422 : Validation échouée

#### Accepter une demande (Artiste)

**POST** `/booking-requests/{id}/accept`

**Body**:
```json
{
  "estimated_total_price": 300,
  "deposit_rate": 30,
  "price_range_min": 250,
  "price_range_max": 350,
  "design_versions": 3,
  "modifications_per_version": 2,
  "design_deadline": "2025-03-15"
}
```

**Response 200**:
```json
{
  "message": "Demande acceptée avec succès",
  "data": {
    "id": 42,
    "status": "accepted",
    "total_deposit_amount": 90.00,
    "client_payment_deadline": "2025-02-12T10:30:00Z",
    "conversation": {
      "id": 15,
      "expiry_type": "deposit_pending",
      "deposit_deadline_at": "2025-02-12T10:30:00Z"
    }
  }
}
```

#### Confirmer paiement acompte (Client)

**POST** `/booking-requests/{id}/confirm-deposit`

**Body**:
```json
{
  "payment_intent_id": "pi_3AbCdEf123456"
}
```

**Response 200**:
```json
{
  "message": "Acompte confirmé avec succès",
  "data": {
    "id": 42,
    "status": "deposit_paid",
    "deposit_paid_at": "2025-02-05T14:20:00Z",
    "conversation": {
      "expiry_type": "permanent"
    }
  }
}
```

---

#### Envoyer design (Artiste)

**POST** `/booking-requests/{id}/send-design`

**Headers**:
```
Content-Type: multipart/form-data
```

**Body** (form-data):
```
images[]: [file1.jpg]
images[]: [file2.jpg]
message: "Voici ma première proposition de design"
```

**Response 200**:
```json
{
  "message": "Design envoyé avec succès",
  "data": {
    "id": 42,
    "status": "design_sent",
    "design_versions_used": 1,
    "design_sent_at": "2025-02-06T09:15:00Z"
  }
}
```

**Errors**:
- 422 : Nombre maximum de versions atteint (FREE plan)

#### Confirmer RDV (Artiste)

**POST** `/booking-requests/{id}/confirm-appointment`

**Body**:
```json
{
  "start_time": "2025-03-20T14:00:00Z",
  "duration_minutes": 180
}
```

**Response 200**:
```json
{
  "message": "Rendez-vous confirmé",
  "data": {
    "booking_request": {
      "id": 42,
      "status": "confirmed"
    },
    "appointment": {
      "id": 28,
      "start_time": "2025-03-20T14:00:00Z",
      "end_time": "2025-03-20T17:00:00Z",
      "remaining_amount": 210.00
    }
  }
}
```

### 🔹 Conversations

#### Lister conversations

**GET** `/conversations`

**Query params**:
- `status` : active, archived, expired
- `per_page` : Nombre par page (défaut: 15)

**Response 200**:
```json
{
  "data": [
    {
      "id": 15,
      "subject": "Demande tatouage - medium",
      "status": "active",
      "unread_count": 3,
      "last_message": {
        "content": "D'accord, merci !",
        "created_at": "2025-02-05T16:45:00Z"
      },
      "participants": [
        {
          "id": 8,
          "name": "Jean Dupont",
          "role": "client"
        },
        {
          "id": 12,
          "name": "Marie Tattooist",
          "role": "tattooer"
        }
      ]
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 8
  }
}
```

### 🔹 Messages

#### Envoyer message

**POST** `/messages`

**Body**:
```json
{
  "conversation_id": 15,
  "content": "Bonjour, j'ai une question sur le design",
  "attachment": null
}
```

**Avec pièce jointe**:
```
Content-Type: multipart/form-data

conversation_id: 15
content: "Voici une image de référence"
attachment: [file.jpg]
```

**Response 201**:
```json
{
  "message": "Message envoyé",
  "data": {
    "id": 254,
    "conversation_id": 15,
    "sender_id": 8,
    "sender_type": "client",
    "content": "Bonjour, j'ai une question sur le design",
    "created_at": "2025-02-05T17:00:00Z",
    "attachments": []
  }
}
```

### 🔹 Tattooers

#### Lister tatoueurs (Public)

**GET** `/tattooers`

**Query params**:
- `city` : Filtrer par ville
- `styles` : Filtrer par styles (ex: "japonais,realiste")
- `per_page` : Pagination (défaut: 20)

**Response 200**:
```json
{
  "data": [
    {
      "id": 5,
      "name": "Marie Ink Studio",
      "slug": "marie-ink-studio",
      "bio": "Spécialisée dans le style japonais traditionnel...",
      "styles": "japonais,traditionnel,irezumi",
      "city": "Paris",
      "avatar_url": "https://cdn.inkpik.fr/avatars/marie-5.jpg",
      "average_rating": 4.8,
      "is_subscribed": true,
      "portfolio_count": 24
    }
  ],
  "meta": {
    "total": 156
  }
}
```

#### Profil tatoueur détaillé

**GET** `/tattooers/{slug}`

**Response 200**:
```json
{
  "data": {
    "id": 5,
    "name": "Marie Ink Studio",
    "slug": "marie-ink-studio",
    "bio": "Spécialisée dans le style japonais...",
    "styles": "japonais,traditionnel",
    "city": "Paris",
    "postal_code": "75010",
    "address": "12 rue de la Grange aux Belles",
    "phone": "+33612345678",
    "instagram": "marie_ink_studio",
    "website": "https://marieinkstudio.fr",
    "avatar_url": "...",
    "banner_url": "...",
    "portfolio": [
      {
        "id": 42,
        "url": "https://cdn.inkpik.fr/portfolio/5/image1.jpg",
        "thumb": "https://cdn.inkpik.fr/portfolio/5/image1-thumb.jpg"
      }
    ],
    "working_hours": {
      "Lundi": "09:00:00 - 18:00:00",
      "Mardi": "09:00:00 - 18:00:00",
      "Dimanche": "Fermé"
    },
    "stats": {
      "completed_projects": 87,
      "average_rating": 4.8,
      "total_reviews": 34
    }
  }
}
```

### 🔹 Paiements

#### Créer Payment Intent

**POST** `/payments/create-intent`

**Body**:
```json
{
  "booking_request_id": 42
}
```

**Response 200**:
```json
{
  "client_secret": "pi_3AbC...secret",
  "payment_intent_id": "pi_3AbCdEf123456",
  "amount": 9000,
  "currency": "eur"
}
```

## Rate Limits

| Endpoint | Type | Authenticated | Unauthenticated |
|----------|------|---------------|------------------|
| GET (lecture) | 60/min | 10/min |
| POST/PUT/DELETE | 30/min | 5/min |
| Uploads | 10/hour | N/A |
| Paiements | 3/hour | N/A |
| Login | 5/15min | 5/15min |

## Codes d'Erreur

| Code | Description |
|------|-------------|
| 200 | Succès |
| 201 | Créé |
| 400 | Requête invalide |
| 401 | Non authentifié |
| 403 | Non autorisé |
| 404 | Ressource non trouvée |
| 422 | Validation échouée |
| 429 | Rate limit dépassé |
| 500 | Erreur serveur |

## Webhooks Stripe

### Configuration
URL webhook : https://api.inkpik.fr/api/stripe/webhook

Événements écoutés :
- `payment_intent.succeeded`
- `payment_intent.payment_failed`
- `account.updated`

### Payload Exemple
```json
{
  "type": "payment_intent.succeeded",
  "data": {
    "object": {
      "id": "pi_3AbCdEf123456",
      "amount": 9000,
      "metadata": {
        "booking_request_id": "42"
      }
    }
  }
}
```

## Postman Collection

Importer la collection complète : [inkpik-api.postman_collection.json](postman/inkpik-api.postman_collection.json)

## Changelog API

| Version | Date | Changements |
|---------|------|-------------|
| v1.2 | 2025-02-05 | Ajout endpoint `/tattooers/{slug}/availability` |
| v1.1 | 2025-01-20 | Rate limiting implémenté |
| v1.0 | 2025-01-01 | Release initiale |
