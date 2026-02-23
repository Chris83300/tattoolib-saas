# 📊 ANALYSE D'ÉCART — Spec complète vs État réel Ink&Pik
# Date : 23/02/2026

Croisement de ton document de réflexion avec l'état réel du projet après tous les prompts exécutés.

---

## LÉGENDE
- ✅ **FAIT** — Implémenté et fonctionnel
- ⚠️ **PARTIEL** — Existe mais incomplet
- ❌ **MANQUANT** — Pas encore implémenté
- 🔜 **POST-LAUNCH** — Pas critique pour le lancement

---

## 1. GESTION DES UTILISATEURS

| Feature | Status | Détail |
|---------|--------|--------|
| Client : inscription | ✅ | Fonctionne |
| Client : âge minimum 16 ans | ⚠️ | **À vérifier** — y a-t-il une validation date de naissance ≥ 16 ans ? |
| Client : profil + historique demandes | ✅ | Dashboard client, réservations, messages |
| Client : notifications email | ✅ | 23 classes Notification |
| Client : push mobile | ⚠️ | **À vérifier** — configuration service push Firebase (FCM déjà en place dans la base du back mais à vérifier) |
| Tattooer : inscription SIRET | ✅ | Validation format SIRET |
| Tattooer : upload attestations Hygiène + ARS | ✅ | Champs + upload dans settings |
| Tattooer : badge vérification (3 états) | ⚠️ | **À vérifier** — le système de badge 🔘🟡🟢 existe dans Filament admin ? R : Non juste atribution du badge mais qand Siret valider badge vérifier apparaît (donc à modifier pour addapter à badge attestations) |
| Tattooer : profil complet | ✅ | Portfolio, styles, bio, stats dashboard |
| Tattooer : plan FREE (7% commission) | ✅ | application_fee confirmé |
| Tattooer : plan PRO (abonnement, 0%) | ✅ | Stripe Cashier + subscription |
| Pierceur : polymorphique | ✅ | Vient d'être refait proprement |
| Studio : inscription + dashboard | ⚠️ | Routes existent, **non testé** |
| Studio Artiste : relié au studio | ⚠️ | Relations en place, **non testé** |
| Studio : pricing 39.99€/artiste | ⚠️ | **À vérifier** - si référence 39.99/3999 dans le code |

---

## 2. WORKFLOW DE RÉSERVATION

| Feature | Status | Détail |
|---------|--------|--------|
| Client soumet demande (tattoo) | ✅ | Type, taille, budget, images |
| Client soumet demande (piercing) | ✅ | Formulaire conditionnel avec choix de type de piercing et autres champs spécifiques (a vérifier la modal (accept-booking-modal) pour les champs en accord avec demand Piercer) |
| Calendrier dispo/indispo | ✅ | Existe |
| Calendrier : RDV jour même | ⚠️ | **non testé** — minDate tomorrow → today |
| Chat auto-ouvert après demande | ✅ | Conversation créée automatiquement |
| Chat : pas de fichiers avant acompte | ✅ | restriction upload avant deposit_paid |
| Notifications RDV (7j, 2j, jour même) | ✅ | Scheduler + notifications |
| Notification 30j post-RDV (retouches) | ⚠️ | **À vérifier** si cette notification spécifique existe |
| Tattooer accepte/refuse | ✅ | Fonctionne |
| Refus avec message optionnel | ⚠️ | **non testé** |
| Acceptation : estimation prix min/max | ✅ | Fourchette dans BookingRequest |
| Acceptation : montant acompte + délai | ✅ | deposit_amount + deposit_deadline |
| Dessins : compteur envoyés | ✅ | drawings_sent, modifications_count |
| Dessins : limite + surplus payant | ✅ | max_drawings, max_modifications (implémenté mais sans le système de paiement pour les surplus, à mettre en place pour la modification de demande initiale si projet plus en raccord avec la demande initiale) |
| Calendrier : ajout RDV auto depuis chat | ✅ | 

---

## 3. PAIEMENT ET REMBOURSEMENT

| Feature | Status | Détail |
|---------|--------|--------|
| Acompte via Stripe Connect | ✅ | ACCEPTED → DEPOSIT_REQUESTED → DEPOSIT_PAID |
| Auto-expiration si non payé | ✅ | Scheduler ExpireUnpaidDeposits |
| Paiement solde sur plateforme | ⚠️ | **À vérifier** — modal montant final avec acompte déduit ? |
| Remboursement basé sur dessins | ⚠️ | **À vérifier** — logique 0/80%/50%/0% |
| Remboursement : tattooer ajuste % | ❌ | **Pas implémenté** |
| Annulation >7j avant RDV : règles | ⚠️ | **À vérifier** |
| Contestation avec historique | ❌ | Complaints existe mais pas le workflow complet |
| Modification de projet : nouveau tarif | ❌ | **Pas implémenté** — amendment workflow |
| Modification : client accepte explicitement | ❌ | **Pas implémenté** |

---

## 4. STRIPE : BETA-TESTEURS ET INACTIVITÉ

| Feature | Status | Détail |
|---------|--------|--------|
| Colonnes users : is_beta_tester, beta_coupon | ❌ | **Pas implémenté** |
| Colonnes users : last_transaction_at | ❌ | **Pas implémenté** |
| Colonnes users : account_status | ❌ | **Pas implémenté** |
| subscribeBeta() : trial 90j sans CB | ❌ | **Pas implémenté** |
| Coupon BETA_FREE_100 | ❌ | **Pas créé dans Stripe** |
| Email fin trial + coupon BETA_LOYALTY_40 | ❌ | **Pas implémenté** |
| Coupon BETA_LOYALTY_40 (-40% permanent) | ❌ | **Pas créé dans Stripe** |
| Job SuspendInactiveFreeAccounts | ❌ | **Pas implémenté** |
| Webhook customer.subscription.trial_ending | ❌ | **Pas implémenté** |
| Webhook charge.succeeded → last_transaction_at | ❌ | **Pas implémenté** |
| Webhook account.updated → sync status | ❌ | **Pas implémenté** |
| Réactivation compte après transaction | ❌ | **Pas implémenté** |

---

## 5. FICHES CLIENTS ET TRAÇABILITÉ

| Feature | Status | Détail |
|---------|--------|--------|
| Fiches clients (Pro) | ✅ | Fonctionne |
| Consentement (adulte) | ✅ | Formulaire digital + upload |
| Consentement mineur : parental + pièce ID | ✅ |
| Traçabilité : aiguilles + encres (tattoo) | ✅ | 3 tables normalisées |
| Traçabilité : bijoux + canules (piercing) | ✅ | Vues conditionnelles ajoutées |
| Export PDF | ⚠️ | Code prêt, en attente conformité SNAT 2026 |
| Chat ouvert 30j post-RDV | ✅ | 3-phase expiration (permanent après paiement) |

---

## 6. VÉRIFICATION LÉGALE ET BADGES

| Feature | Status | Détail |
|---------|--------|--------|
| SIRET obligatoire à l'inscription | ✅ | Validation regex |
| Upload attestation hygiène | ✅ | Dans settings |
| Upload déclaration ARS | ✅ | Dans settings |
| Non bloquant à l'inscription | ✅ | Le pro peut s'inscrire sans docs |
| Badge 3 états (🔘🟡🟢) | ⚠️ | **À vérifier dans Filament + profil public** |
| Workflow validation admin | ⚠️ | **Filament existe mais workflow complet ?** R : Non à adapter |
| Notification admin quand upload | ⚠️ | **À vérifier** |
| Badge visible sur profil public + marketplace | ⚠️ | **À vérifier** |
| Alerte expiration documents | ❌ | **Pas implémenté** |
| Historique versions documents | ❌ | **Pas implémenté** |
| Journalisation validations admin | ⚠️ | **À vérifier dans Filament** |

---

## 7. NOTIFICATIONS

| Feature | Status | Détail |
|---------|--------|--------|
| Email notifications | ✅ | 23 classes |
| Push mobile | ⚠️ | **À vérifier** Firebase (FCM) déjà implémenter mais peut-être pas activé ou non connecter |
| Demande reçue/acceptée/refusée | ✅ | |
| Paiement acompte | ✅ | |
| Paiement solde | ⚠️ | **À vérifier** |
| Rappel RDV (7j, 2j, jour) | ✅ | |
| Soins post-tattoo (2h, J+7, J+14) | ✅ | Aftercare notifications |
| Demande d'avis | ⚠️ | Review system existe mais notification auto ? R : bouton laisser un avis apparâit après la demande passer en terminé (complétée) |
| Alerte contestation | ❌ | Complaints existe mais pas de notification |
| Alerte modification projet | ❌ | Pas implémenté |

---

## 8. SÉCURITÉ

| Feature | Status | Détail |
|---------|--------|--------|
| Input sanitization | ✅ | Laravel natif |
| CSP (Content Security Policy) | ✅ | SecurityHeaders middleware |
| IP blocking | ✅ | BlockSuspiciousIps middleware |
| CSRF protection | ✅ | Laravel natif |
| Upload validation (mimes, max) | ⚠️ | **Partiel** — audit a trouvé des controllers sans validation |
| Antivirus scan uploads | ❌ | **Pas implémenté** |
| Logs actions critiques | ⚠️ | **Partiel** |

---

# 🎯 PRIORISATION POUR LE LANCEMENT

## BLOC A — Obligatoire avant lancement (P0)

1. **Formulaire demande piercer** (en cours)
2. **Studio : test complet + pricing 39.99€** 
3. **Badge vérification : valider que ça fonctionne end-to-end**
4. **Paiement solde** : modal montant final avec acompte déduit
5. **Chat : restriction upload avant acompte**

## BLOC B — Important pour le lancement (P1)

6. **Système Bêta-testeurs** : migration + subscribeBeta() + trial 90j + coupons
7. **Webhook Stripe complets** : trial_ending, charge.succeeded, account.updated
8. **Job SuspendInactiveFreeAccounts** : scheduler quotidien
9. **Remboursement basé sur dessins** : logique 0/80%/50%/0% + ajustement tattooer
10. **Compteur dessins/modifications** : incrément auto + limite + surplus

## BLOC C — Peut attendre post-lancement (P2)

11. **Push mobile** (Firebase/OneSignal)
12. **Modification de projet** : amendment workflow complet
13. **Antivirus scan uploads**
14. **Alerte expiration documents**
15. **Historique versions documents**
16. **Export PDF SNAT** (en attente conformité)
17. **Consentement mineur spécifique** (parental + pièce ID)
18. **Contestation avancée** avec workflow complet

---

# 📝 RECOMMANDATION

**Pour le lancement**, concentre-toi sur le **Bloc A** (5 items) et le **système bêta-testeurs** du Bloc B (item 6), car c'est ton acquisition initiale.

L'ordre que je recommande :
1. Finir le piercer (formulaire demande + derniers ajustements)
2. Tester/fixer le Studio end-to-end
3. Système bêta-testeurs + coupons Stripe
4. Webhooks Stripe complets
5. Remboursement basé sur dessins
6. Job suspension inactivité

Le reste peut venir par itérations post-lancement.
