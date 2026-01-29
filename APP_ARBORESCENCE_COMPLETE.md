# 📁 Arborescence Complète du Dossier `/app` - TattooLib SaaS

> **Généré le** : 28 janvier 2026  
> **Projet** : TattooLib SaaS - Marketplace professionnelle pour l'industrie du tatouage  
> **Framework** : Laravel 12 avec Livewire 3

---

## 📋 Vue d'ensemble

```
app/
├── Actions/                    # Actions métier (Fortify, etc.)
├── Console/                    # Commandes Artisan & Tasks
├── Events/                     # Événements du système
├── Filament/                   # Panels d'administration Filament
├── Http/                       # Couche HTTP (Controllers, Middleware, Requests)
├── Jobs/                       # Jobs pour les queues
├── Listeners/                  # Listeners d'événements
├── Livewire/                   # Composants Livewire
├── Models/                     # Modèles Eloquent
├── Notifications/              # Notifications système
├── Observers/                  # Observers de modèles
├── Policies/                   # Policies d'autorisation
├── Providers/                  # Service Providers
├── Services/                   # Services métier
└── Traits/                     # Traits réutilisables
```

---

## 🎯 Actions Fortify

```
Actions/
└── Fortify/
    ├── CreateNewUser.php       # Création utilisateur multi-rôles
    ├── PasswordValidationRules.php  # Règles validation mots de passe
    └── ResetUserPassword.php   # Réinitialisation mots de passe
```

**Fonctionnalités** : Gestion authentification Laravel Fortify avec rôles multiples (client, tattooer, studio, pierceur).

---

## ⚡ Console & Commands

```
Console/
├── Kernel.php                  # Enregistrement commands & scheduling
└── Commands/
    ├── CheckComplianceExpirations.php      # Vérifications conformité ARS
    ├── CheckExpiredBookingRequestsCommand.php  # Nettoyage demandes expirées
    ├── CleanupExpiredConversations.php     # Nettoyage conversations
    ├── DeactivateInactiveStripeAccounts.php  # Gestion comptes Stripe inactifs
    └── GenerateAvailabilities.php         # Génération disponibilités
```

**Fonctionnalités** : Automatisation des tâches système, maintenance, conformité réglementaire.

---

## 📡 Événements Système

```
Events/
├── ConversationExpired.php     # Conversation expirée
├── ConversationExpiring.php    # Conversation sur le point d'expirer
├── MessageCreated.php          # Nouveau message créé
└── MessageDeleted.php          # Message supprimé
```

**Fonctionnalités** : Gestion événements messagerie temps réel avec notifications.

---

## 🎛️ Panels Filament

```
Filament/
└── Studio/
    └── Pages/
        └── Dashboard.php       # Dashboard administration studio
```

**Fonctionnalités** : Interface d'administration pour les gestionnaires de studio.

---

## 🌐 Couche HTTP

### Controllers Principaux
```
Http/
├── Controllers/
│   ├── ArtistController.php    # Gestion artistes
│   ├── Controller.php         # Controller de base
│   ├── RegisterController.php # Inscription multi-rôles ✨
│   └── StripeWebhookController.php  # Webhooks Stripe
│
├── Controllers/Api/            # API RESTful
│   ├── AccountingController.php      # Comptabilité studio
│   ├── AppointmentController.php     # Gestion rendez-vous
│   ├── AuthController.php            # Authentification API
│   ├── AvailabilityController.php   # Disponibilités
│   ├── BookingRequestController.php  # Demandes de réservation
│   ├── ClientCareSheetController.php # Fiches soins clients
│   ├── ConversationController.php   # Messagerie
│   ├── FCMController.php             # Notifications push Firebase
│   ├── InventoryController.php      # Gestion stock
│   ├── MessageController.php        # Messages
│   ├── PaymentController.php        # Paiements Stripe
│   ├── TattooerController.php       # Artistes
│   ├── TattooerPlanningController.php # Planning artistes
│   ├── TraceabilityController.php   # Traçabilité réglementaire
│   └── WorkingHourController.php    # Horaires travail
│
├── Controllers/Auth/           # Authentification web
│   ├── LoginController.php     # Connexion
│   └── LogoutController.php    # Déconnexion
│
├── Kernel.php                  # HTTP Kernel avec middleware
├── Middleware/                 # Middleware personnalisés
│   ├── EnsureSanctumAuthentication.php  # Auth API Sanctum
│   ├── EnsureUserHasRole.php   # Vérification rôle utilisateur
│   ├── EnsureUserHasStatus.php # Vérification statut
│   ├── EnsureUserIsStudio.php  # Vérification studio
│   └── RedirectIfAuthenticated.php  # Redirection si connecté
│
└── Requests/                   # Form Requests
    ├── StoreBookingRequestRequest.php  # Validation demande RDV
    ├── StoreFcmTokenRequest.php        # Token FCM
    ├── StoreMessageRequest.php         # Message
    └── UpdateBookingRequestRequest.php # Mise à jour demande
```

**Fonctionnalités** : API complète RESTful, authentification multi-rôles, validation, middleware de sécurité.

---

## 🔄 Jobs Queue

```
Jobs/
├── CheckExpiredBookingRequests.php  # Vérification demandes expirées
├── CheckOverdueDesigns.php          # Vérification designs en retard
├── CheckOverduePayments.php         # Vérification paiements en retard
├── ProcessStripeRefund.php          # Traitement remboursements Stripe
└── SendDesignDeadlineReminder.php    # Rappels deadlines designs
```

**Fonctionnalités** : Traitement asynchrone des tâches métier, notifications automatiques.

---

## 👂 Listeners Événements

```
Listeners/
├── CleanupMessageMedia.php    # Nettoyage médias messages
├── SendNewMessageNotification.php  # Notifications nouveaux messages
└── UpdateUnreadCounts.php    # Mise à jour comptes non lus
```

**Fonctionnalités** : Réactions aux événements système, notifications temps réel.

---

## ⚡ Composants Livewire

### Authentification
```
Livewire/
├── Actions/
│   └── Logout.php             # Déconnexion Livewire
│
├── Auth/
│   ├── AuthLayoutComponent.php  # Layout auth
│   ├── Login.php              # Connexion
│   ├── Register.php            # Inscription générique
│   ├── RegisterClient.php     # Inscription client
│   ├── RegisterPierceur.php   # Inscription pierceur
│   ├── RegisterStudio.php     # Inscription studio
│   └── RegisterTattooer.php   # Inscription tattooer ✨
│
├── Client/                     # Espace client
│   ├── Bookings.php           # Gestion RDV
│   ├── Messages.php           # Messagerie
│   ├── Profile.php            # Profil client
│   └── Settings.php           # Paramètres
│
├── Settings/                   # Paramètres utilisateur
│   ├── Appearance.php         # Apparence
│   ├── DeleteUserForm.php     # Suppression compte
│   ├── Password.php            # Mot de passe
│   ├── Profile.php             # Profil
│   ├── TwoFactor.php          # 2FA
│   └── TwoFactor/
│       └── RecoveryCodes.php  # Codes récupération 2FA
│
└── Tattooer/                   # Espace tattooer ✨
    ├── BookingRequests.php    # Demandes RDV
    ├── Dashboard.php          # Dashboard tattooer
    ├── PendingVerification.php # Page attente validation ✨
    ├── Profile.php            # Profil tattooer ✨
    └── Settings.php           # Paramètres tattooer
```

**Fonctionnalités** : Interface complète réactive avec Livewire 3, gestion multi-rôles, profils spécialisés.

---

## 🗄️ Modèles de Données

### Modèles Principaux
```
Models/
├── User.php                    # Utilisateur multi-rôles
├── Client.php                  # Profil client
├── Tattooer.php                # Profil tattooer ✨
├── Studio.php                  # Studio de tatouage
├── StudioArtist.php            # Artiste employé par studio
├── StudioAccountingEntry.php   # Écritures comptables studio
├── StudioSubscription.php     # Abonnements studio
│
├── BookingRequest.php          # Demandes de réservation
├── Appointment.php             # Rendez-vous confirmés
├── Availability.php            # Disponibilités (polymorphic)
├── WorkingHour.php             # Horaires travail (polymorphic)
│
├── Conversation.php            # Conversations messagerie
├── ConversationUser.php        # Participants conversations
├── Message.php                 # Messages
│
├── Payment.php                 # Paiements Stripe
├── Invoice.php                 # Factures
├── Subscription.php            # Abonnements (polymorphic)
│
├── ClientCareSheet.php         # Fiches soins post-tatouage
├── ClientConsentForm.php       # Formulaires consentement
├── ParentalConsentForm.php     # Consentements mineurs
├── ComplianceRecord.php        # Dossiers conformité
├── TraceabilityRecord.php     # Traçabilité réglementaire
│
├── InventoryItem.php           # Articles stock
├── InventoryMovement.php      # Mouvements stock
├── PurchaseOrder.php           # Commandes fournisseurs
├── PurchaseOrderItem.php       # Lignes commandes
└── AccountingTransaction.php   # Transactions comptables
```

**Fonctionnalités** : Architecture polymorphique complète, relations complexes, gestion multi-tenant.

---

## 📬 Notifications Système

```
Notifications/
├── CareSheetReminder.php       # Rappels fiches soins
└── NewMessageNotification.php # Notifications nouveaux messages
```

**Fonctionnalités** : Notifications push FCM, emails, notifications in-app.

---

## 👁️ Observers de Modèles

```
Observers/
├── ConversationObserver.php   # Observer conversations
└── ...
```

**Fonctionnalités** : Automatisation des réactions aux changements de modèles.

---

## 🛡️ Policies d'Autorisation

```
Policies/
├── AccountingPolicy.php        # Accès comptabilité
├── AppointmentPolicy.php      # Gestion RDV
├── AvailabilityPolicy.php     # Disponibilités
├── BookingRequestPolicy.php   # Demandes RDV
├── ClientCareSheetPolicy.php  # Fiches soins
├── ConversationPolicy.php     # Messagerie
├── InventoryPolicy.php        # Stock
├── MessagePolicy.php          # Messages
├── PaymentPolicy.php          # Paiements
├── TattooerPolicy.php        # Artistes
└── TraceabilityPolicy.php     # Traçabilité
```

**Fonctionnalités** : Contrôle d'accès granulaire par rôle et ressource.

---

## 🔧 Service Providers

```
Providers/
├── AppServiceProvider.php     # Provider principal
├── AuthServiceProvider.php    # Authentification & Policies
├── EventServiceProvider.php   # Événements & Listeners
├── FortifyServiceProvider.php # Configuration Fortify
│
└── Filament/                   # Panels Filament
    ├── AdminPanelProvider.php  # Panel admin
    ├── ShopPanelProvider.php   # Panel shop
    └── StudioPanelProvider.php # Panel studio
```

**Fonctionnalités** : Configuration Laravel, enregistrement services, panels d'administration.

---

## 🛠️ Services Métier

```
Services/
├── StripeService.php                   # Service Stripe principal
├── StripeStudioSubscriptionService.php # Abonnements studios Stripe
└── StudioArtistService.php             # Gestion artistes studio
```

**Fonctionnalités** : Intégrations externes, logique métier complexe.

---

## 🔀 Traits Réutilisables

```
Traits/
├── BookableArtist.php          # Interface commune artistes bookables
├── HasCompliance.php           # Conformité réglementaire
├── HasStripeConnect.php        # Integration Stripe Connect
└── HasSubscription.php        # Gestion abonnements
```

**Fonctionnalités** : Code réutilisable, interfaces communes, fonctionnalités partagées.

---

## 🎯 Points Clés de l'Architecture

### **🏗️ Architecture Multi-Tenant**
- **Rôles** : Client, Tattooer, Studio, Studio Artist, Pierceur
- **Relations polymorphiques** : WorkingHours, Availabilities, Bookings, Subscriptions
- **Sécurité** : Middleware spécialisés, policies granulaires

### **💰 Système Économique Complet**
- **Abonnements** : Free (7% commission), Pro (49.99€/mois), Studio (79.99€/mois)
- **Paiements** : Stripe Connect pour artistes, Stripe Cashier pour abonnements
- **Commission** : Automatique sur plan Free

### **🔄 Flux Métier Optimisés**
- **Inscription** : Multi-étapes avec validation SIRET
- **Booking** : Request → Accept → Payment → Appointment
- **Messagerie** : Temps réel avec notifications FCM

### **📊 Conformité Réglementaire**
- **Traçabilité** : Records complets pour chaque RDV
- **Consentements** : Formulaires adultes et mineurs
- **Soins** : Fiches post-tatouage avec rappels

### **⚡ Performance & Scalabilité**
- **Livewire 3** : Interface réactive sans JavaScript lourd
- **Queue System** : Jobs asynchrones pour les tâches lourdes
- **Cache** : Optimisations des requêtes fréquentes

---

## 📈 Statistiques de l'Application

### **Modèles** : 28 modèles Eloquent
### **Controllers** : 15 controllers (8 API + 7 Web)
### **Livewire** : 17 composants réactifs
### **Jobs** : 5 jobs queue
### **Policies** : 10 policies d'autorisation
### **Services** : 3 services métier
### **Traits** : 4 traits réutilisables

---

## 🎉 Conclusion

Cette architecture `/app` représente une **application SaaS complète et professionnelle** pour l'industrie du tatouage, avec :

- ✅ **Multi-tenant avancé** avec rôles complexes
- ✅ **API RESTful complète** avec authentification Sanctum
- ✅ **Interface réactive** avec Livewire 3
- ✅ **Système économique** avec Stripe intégré
- ✅ **Conformité réglementaire** complète
- ✅ **Architecture scalable** et maintenable

**Prête pour la production et l'évolution !** 🚀
