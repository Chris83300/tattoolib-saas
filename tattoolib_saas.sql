-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 22 fév. 2026 à 13:55
-- Version du serveur : 8.4.3
-- Version de PHP : 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tattoolib_saas`
--

-- --------------------------------------------------------

--
-- Structure de la table `accounting_transactions`
--

CREATE TABLE `accounting_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `tattooer_id` bigint UNSIGNED DEFAULT NULL,
  `studio_id` bigint UNSIGNED DEFAULT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('income','expense','tax_payment','transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('appointment','product_sale','equipment','rent','utility','marketing','tax','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EUR',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `transaction_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `status` enum('draft','pending','paid','overdue','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `appointment_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` bigint UNSIGNED DEFAULT NULL,
  `purchase_order_id` bigint UNSIGNED DEFAULT NULL,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(8,2) NOT NULL DEFAULT '0.00',
  `amount_with_tax` decimal(10,2) GENERATED ALWAYS AS ((`amount` + `tax_amount`)) STORED,
  `attachments` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `booking_request_id` bigint UNSIGNED NOT NULL,
  `stripe_charge_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_request_id` bigint UNSIGNED NOT NULL,
  `bookable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bookable_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `duration_minutes` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deposit_amount` decimal(8,2) NOT NULL,
  `total_price` decimal(8,2) DEFAULT NULL,
  `remaining_amount` decimal(8,2) DEFAULT NULL,
  `status` enum('scheduled','confirmed','completed','cancelled','client_no_show','tattooer_no_show','disputed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'confirmed',
  `completed_at` timestamp NULL DEFAULT NULL,
  `completed_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completion_notes` text COLLATE utf8mb4_unicode_ci,
  `no_show_reported_at` timestamp NULL DEFAULT NULL,
  `no_show_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tattooer_absence_reported_at` timestamp NULL DEFAULT NULL,
  `tattooer_absence_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actual_end_time` timestamp NULL DEFAULT NULL,
  `cancelled_by` enum('client','tattooer','admin') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text COLLATE utf8mb4_unicode_ci,
  `days_before_appointment` int DEFAULT NULL,
  `refunded` tinyint(1) NOT NULL DEFAULT '0',
  `refund_amount` decimal(8,2) DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `stripe_refund_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tattooer_confirmation_status` enum('pending','completed','client_no_show','client_late','other_issue') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tattooer_confirmation_note` text COLLATE utf8mb4_unicode_ci,
  `tattooer_confirmed_at` timestamp NULL DEFAULT NULL,
  `client_reported_issue` tinyint(1) NOT NULL DEFAULT '0',
  `client_issue_description` text COLLATE utf8mb4_unicode_ci,
  `client_reported_at` timestamp NULL DEFAULT NULL,
  `client_dispute_refund` tinyint(1) NOT NULL DEFAULT '0',
  `client_dispute_reason` text COLLATE utf8mb4_unicode_ci,
  `client_dispute_at` timestamp NULL DEFAULT NULL,
  `dispute_resolution` enum('pending','approved','rejected','partial') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dispute_refund_amount` decimal(8,2) DEFAULT NULL,
  `dispute_resolution_note` text COLLATE utf8mb4_unicode_ci,
  `dispute_resolved_at` timestamp NULL DEFAULT NULL,
  `requires_manual_review` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `dispute_resolved_by` bigint UNSIGNED DEFAULT NULL,
  `care_notification_sent_at` timestamp NULL DEFAULT NULL,
  `healing_notification_sent_at` timestamp NULL DEFAULT NULL,
  `review_notification_sent_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `availabilities`
--

CREATE TABLE `availabilities` (
  `id` bigint UNSIGNED NOT NULL,
  `owner_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `type` enum('available','busy','break','holiday','sick_leave','external_booking','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `source` enum('manual','working_hours','booking','external') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_recurring` tinyint(1) NOT NULL DEFAULT '0',
  `recurring_pattern` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recurring_end_date` date DEFAULT NULL,
  `appointment_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `booking_requests`
--

CREATE TABLE `booking_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `bookable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bookable_id` bigint UNSIGNED NOT NULL,
  `tattoo_size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body_zone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tattooer_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Message personnalisé du tattooer au client',
  `estimated_price` decimal(10,2) DEFAULT NULL,
  `estimated_budget` decimal(8,2) DEFAULT NULL,
  `preferred_timeframe` enum('asap','3-4months','5-6months','6plus') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_days` json DEFAULT NULL,
  `date_notes` text COLLATE utf8mb4_unicode_ci,
  `preferred_date` date DEFAULT NULL,
  `preferred_time_slot` enum('morning','afternoon','evening','anytime') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_time_notes` text COLLATE utf8mb4_unicode_ci,
  `proposed_dates` json DEFAULT NULL,
  `client_selected_dates` json DEFAULT NULL,
  `date_selection_deadline` timestamp NULL DEFAULT NULL,
  `client_dates_selected_at` timestamp NULL DEFAULT NULL,
  `confirmed_date` date DEFAULT NULL,
  `confirmed_period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tattooer_acceptance_message` text COLLATE utf8mb4_unicode_ci,
  `total_deposit_amount` decimal(8,2) DEFAULT NULL,
  `deposit_amount` decimal(10,2) DEFAULT NULL,
  `estimated_total_price` decimal(8,2) DEFAULT NULL,
  `price_estimate_min` decimal(10,2) DEFAULT NULL,
  `price_estimate_max` decimal(10,2) DEFAULT NULL,
  `client_payment_deadline_days` int NOT NULL DEFAULT '7',
  `deposit_deadline_hours` int UNSIGNED NOT NULL DEFAULT '72',
  `tattooer_design_deadline_days` int NOT NULL DEFAULT '7',
  `client_payment_deadline` timestamp NULL DEFAULT NULL,
  `tattooer_design_deadline` timestamp NULL DEFAULT NULL,
  `design_sent_at` timestamp NULL DEFAULT NULL,
  `deposit_deadline` timestamp NULL DEFAULT NULL,
  `is_long_term_booking` tinyint(1) NOT NULL DEFAULT '0',
  `design_preparation_starts_at` timestamp NULL DEFAULT NULL,
  `design_preparation_notified` tinyint(1) NOT NULL DEFAULT '0',
  `included_design_versions` int NOT NULL DEFAULT '3',
  `included_designs` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `modifications_per_design` tinyint UNSIGNED NOT NULL DEFAULT '2',
  `design_versions_used` int NOT NULL DEFAULT '0',
  `designs_sent_count` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `design_modifications_tracker` json DEFAULT NULL COMMENT 'JSON: {"1": 0, "2": 1} = modifs utilisées par dessin',
  `current_design_modifications_count` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `stripe_payment_intent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','accepted','deposit_requested','deposit_paid','date_confirmed','completed','balance_paid','balance_paid_offline','fully_completed','rejected','cancelled','expired','no_show') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deposit_paid_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `scheduled_start_time` time DEFAULT NULL,
  `scheduled_end_time` time DEFAULT NULL,
  `scheduled_duration_minutes` int DEFAULT NULL,
  `total_price` decimal(8,2) DEFAULT NULL,
  `balance_amount` decimal(10,2) DEFAULT NULL,
  `balance_paid_at` timestamp NULL DEFAULT NULL,
  `balance_payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance_stripe_session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refund_amount` decimal(10,2) DEFAULT NULL,
  `refund_percent` tinyint UNSIGNED DEFAULT NULL,
  `refund_processed_at` timestamp NULL DEFAULT NULL,
  `tattooer_missed_deadline` tinyint(1) NOT NULL DEFAULT '0',
  `client_missed_deadline` tinyint(1) NOT NULL DEFAULT '0',
  `appointment_datetime` timestamp NULL DEFAULT NULL,
  `appointment_duration_minutes` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `overage_decision` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surcharge_amount` decimal(10,2) DEFAULT NULL,
  `surcharge_paid_at` timestamp NULL DEFAULT NULL,
  `overage_reason` text COLLATE utf8mb4_unicode_ci,
  `cancelled_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancellation_reason` text COLLATE utf8mb4_unicode_ci,
  `cancelled_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `booking_transactions`
--

CREATE TABLE `booking_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_request_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'eur',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_payment_intent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('tattoolib-saas-cache-5c785c036466adea360111aa28563bfd556b5fba', 'i:2;', 1771751673),
('tattoolib-saas-cache-5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1771751673;', 1771751673);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `calendar_events`
--

CREATE TABLE `calendar_events` (
  `id` bigint UNSIGNED NOT NULL,
  `bookable_id` bigint UNSIGNED NOT NULL,
  `bookable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('appointment','break','vacation','closure') COLLATE utf8mb4_unicode_ci NOT NULL,
  `appointment_id` bigint UNSIGNED DEFAULT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT '0',
  `recurrence_rule` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#D4B59E',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tattooer_id` bigint UNSIGNED DEFAULT NULL,
  `pseudo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `no_show_count` int NOT NULL DEFAULT '0',
  `is_blacklisted` tinyint(1) NOT NULL DEFAULT '0',
  `blacklist_reason` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client_care_sheets`
--

CREATE TABLE `client_care_sheets` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `tattooer_id` bigint UNSIGNED NOT NULL,
  `appointment_id` bigint UNSIGNED NOT NULL,
  `tattoo_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tattoo_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tattoo_size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `technique_used` text COLLATE utf8mb4_unicode_ci,
  `ink_colors_used` text COLLATE utf8mb4_unicode_ci,
  `allergies` text COLLATE utf8mb4_unicode_ci,
  `skin_conditions` text COLLATE utf8mb4_unicode_ci,
  `medications` text COLLATE utf8mb4_unicode_ci,
  `has_diabetes` tinyint(1) NOT NULL DEFAULT '0',
  `has_blood_disorders` tinyint(1) NOT NULL DEFAULT '0',
  `is_pregnant` tinyint(1) NOT NULL DEFAULT '0',
  `immediate_care_instructions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `products_used` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `bandage_type` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `bandage_removal_time` datetime NOT NULL,
  `washing_instructions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `moisturizing_instructions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_restrictions` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sun_exposure_warnings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `healing_estimated_date` date NOT NULL,
  `first_touchup_date` date DEFAULT NULL,
  `healing_notes` text COLLATE utf8mb4_unicode_ci,
  `healing_status` enum('in_progress','healed','complicated','touchup_needed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `healing_photos` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client_consent_forms`
--

CREATE TABLE `client_consent_forms` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_request_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `tattooer_id` bigint UNSIGNED NOT NULL,
  `appointment_id` bigint UNSIGNED DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `id_document_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_document_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_document_expiry` date DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_adult` tinyint(1) NOT NULL DEFAULT '1',
  `consent_date` date DEFAULT NULL,
  `consent_time` time DEFAULT NULL,
  `has_allergies` tinyint(1) NOT NULL DEFAULT '0',
  `allergies_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_skin_conditions` tinyint(1) NOT NULL DEFAULT '0',
  `skin_conditions_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_blood_disorders` tinyint(1) NOT NULL DEFAULT '0',
  `blood_disorders_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_diabetes` tinyint(1) NOT NULL DEFAULT '0',
  `has_heart_conditions` tinyint(1) NOT NULL DEFAULT '0',
  `is_pregnant` tinyint(1) NOT NULL DEFAULT '0',
  `is_breastfeeding` tinyint(1) NOT NULL DEFAULT '0',
  `taking_medications` tinyint(1) NOT NULL DEFAULT '0',
  `medications_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_recent_surgery` tinyint(1) NOT NULL DEFAULT '0',
  `recent_surgery_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_existing_tattoos` tinyint(1) NOT NULL DEFAULT '0',
  `existing_tattoos_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `consents_to_tattoo` tinyint(1) NOT NULL DEFAULT '0',
  `understands_risks` tinyint(1) NOT NULL DEFAULT '0',
  `understands_aftercare` tinyint(1) NOT NULL DEFAULT '0',
  `consents_to_photos` tinyint(1) NOT NULL DEFAULT '0',
  `consents_to_data_processing` tinyint(1) NOT NULL DEFAULT '0',
  `id_document_photos` json DEFAULT NULL,
  `consent_signature` json DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','signed','verified','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `signed_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `client_full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_birth_date` date DEFAULT NULL,
  `client_address` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_minor` tinyint(1) NOT NULL DEFAULT '0',
  `parent_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_relation` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_signature_data` longtext COLLATE utf8mb4_unicode_ci,
  `act_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tatouage',
  `body_zone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `act_description` text COLLATE utf8mb4_unicode_ci,
  `medical_allergies` tinyint(1) NOT NULL DEFAULT '0',
  `medical_allergies_detail` text COLLATE utf8mb4_unicode_ci,
  `medical_anticoagulant` tinyint(1) NOT NULL DEFAULT '0',
  `medical_diabetes` tinyint(1) NOT NULL DEFAULT '0',
  `medical_cicatrisation` tinyint(1) NOT NULL DEFAULT '0',
  `medical_skin_disease` tinyint(1) NOT NULL DEFAULT '0',
  `medical_skin_disease_detail` text COLLATE utf8mb4_unicode_ci,
  `medical_vih_hepatite` tinyint(1) NOT NULL DEFAULT '0',
  `medical_pregnant` tinyint(1) NOT NULL DEFAULT '0',
  `medical_roaccutane` tinyint(1) NOT NULL DEFAULT '0',
  `medical_cheloide` tinyint(1) NOT NULL DEFAULT '0',
  `medical_other` text COLLATE utf8mb4_unicode_ci,
  `confirm_medical_sincere` tinyint(1) NOT NULL DEFAULT '0',
  `confirm_risks_informed` tinyint(1) NOT NULL DEFAULT '0',
  `confirm_info_sheet_read` tinyint(1) NOT NULL DEFAULT '0',
  `confirm_aftercare_received` tinyint(1) NOT NULL DEFAULT '0',
  `confirm_not_intoxicated` tinyint(1) NOT NULL DEFAULT '0',
  `confirm_over_18_or_authorized` tinyint(1) NOT NULL DEFAULT '0',
  `confirm_rgpd` tinyint(1) NOT NULL DEFAULT '0',
  `total_price` decimal(10,2) DEFAULT NULL,
  `deposit_amount` decimal(10,2) DEFAULT NULL,
  `retouche_included` tinyint(1) NOT NULL DEFAULT '0',
  `image_authorization` tinyint(1) DEFAULT NULL,
  `signature_data` longtext COLLATE utf8mb4_unicode_ci,
  `signed_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signed_user_agent` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handwritten_mention` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `complaints`
--

CREATE TABLE `complaints` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_request_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` enum('no_show','quality','hygiene','payment','other') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no_show',
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','investigating','resolved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `compliance_records`
--

CREATE TABLE `compliance_records` (
  `id` bigint UNSIGNED NOT NULL,
  `compliant_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `compliant_id` bigint UNSIGNED NOT NULL,
  `certification_type` enum('hygiene_salubrite','certibiocide','declaration_ars') COLLATE utf8mb4_unicode_ci NOT NULL,
  `certificate_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Numéro du certificat',
  `training_organization` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Organisme de formation agréé',
  `obtained_at` date NOT NULL COMMENT 'Date d''obtention',
  `expires_at` date DEFAULT NULL COMMENT 'Date d''expiration (null pour ARS)',
  `certificate_file_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chemin vers le PDF du certificat',
  `ars_proof_file_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Chemin vers la preuve de déclaration ARS',
  `status` enum('valid','expiring_soon','expired','missing','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'missing',
  `biocide_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Type de biocide : TP2, TP4, etc.',
  `is_decision_maker` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True si acheteur/décideur (Certibiocide requis)',
  `ars_region` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Région ARS : Île-de-France, PACA, etc.',
  `ars_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Numéro de déclaration ARS',
  `notification_90d_sent_at` timestamp NULL DEFAULT NULL COMMENT 'Date envoi alerte J-90',
  `notification_30d_sent_at` timestamp NULL DEFAULT NULL COMMENT 'Date envoi alerte J-30',
  `notification_expired_sent_at` timestamp NULL DEFAULT NULL COMMENT 'Date envoi notification expiration',
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL COMMENT 'Date de vérification admin',
  `admin_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Notes internes admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `consents`
--

CREATE TABLE `consents` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `booking_request_id` bigint UNSIGNED DEFAULT NULL,
  `bookable_id` bigint UNSIGNED NOT NULL,
  `bookable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signature_data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `signed_at` timestamp NOT NULL,
  `medical_conditions` json DEFAULT NULL,
  `allergies` json DEFAULT NULL,
  `medications` json DEFAULT NULL,
  `is_pregnant` tinyint(1) NOT NULL DEFAULT '0',
  `has_skin_conditions` tinyint(1) NOT NULL DEFAULT '0',
  `is_minor` tinyint(1) NOT NULL DEFAULT '0',
  `parent_signature_data` text COLLATE utf8mb4_unicode_ci,
  `parent_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_relation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accepts_terms` tinyint(1) NOT NULL DEFAULT '1',
  `accepts_aftercare` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_request_id` bigint UNSIGNED DEFAULT NULL,
  `expiry_type` enum('deposit_pending','permanent','post_appointment','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'deposit_pending' COMMENT 'Type expiration selon cycle booking',
  `deposit_deadline_at` timestamp NULL DEFAULT NULL COMMENT 'Date limite paiement acompte (Phase 1)',
  `appointment_completed_at` timestamp NULL DEFAULT NULL COMMENT 'Date fin RDV (déclenche Phase 3)',
  `expires_at` timestamp NULL DEFAULT NULL COMMENT 'Date expiration définitive',
  `archived_at` timestamp NULL DEFAULT NULL COMMENT 'Date archivage (plan PRO uniquement)',
  `is_expired` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True si conversation expirée',
  `images_preserved` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True si images conservées (plan PRO)',
  `expiry_warning_sent_at` timestamp NULL DEFAULT NULL COMMENT 'Alerte envoyée J-2 avant expiration',
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','archived','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `last_message_at` timestamp NULL DEFAULT NULL,
  `last_message_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `conversation_user`
--

CREATE TABLE `conversation_user` (
  `id` bigint UNSIGNED NOT NULL,
  `conversation_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role` enum('client','tattooer','admin','support') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_read_at` timestamp NULL DEFAULT NULL,
  `is_muted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `expense_items`
--

CREATE TABLE `expense_items` (
  `id` bigint UNSIGNED NOT NULL,
  `expense_report_id` bigint UNSIGNED NOT NULL,
  `inventory_item_id` bigint UNSIGNED DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('equipment','supplies','marketing','travel','utilities','rent','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `expense_date` date NOT NULL,
  `receipt_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachments` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `expense_reports`
--

CREATE TABLE `expense_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `tattooer_id` bigint UNSIGNED DEFAULT NULL,
  `studio_id` bigint UNSIGNED DEFAULT NULL,
  `report_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','submitted','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `total_amount` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_date` date DEFAULT NULL,
  `approval_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` bigint UNSIGNED NOT NULL,
  `tattooer_id` bigint UNSIGNED DEFAULT NULL,
  `studio_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_stock` int NOT NULL DEFAULT '0',
  `min_stock_level` int NOT NULL DEFAULT '0',
  `max_stock_level` int NOT NULL DEFAULT '0',
  `unit_price` decimal(8,2) DEFAULT NULL,
  `unit_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unit',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_vegan` tinyint(1) NOT NULL DEFAULT '0',
  `expiration_date` date DEFAULT NULL,
  `needle_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `needle_size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint UNSIGNED NOT NULL,
  `inventory_item_id` bigint UNSIGNED NOT NULL,
  `tattooer_id` bigint UNSIGNED DEFAULT NULL,
  `movement_type` enum('in','out','adjustment','transfer') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `stock_before` int NOT NULL,
  `stock_after` int NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `appointment_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `tattooer_id` bigint UNSIGNED DEFAULT NULL,
  `studio_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `appointment_id` bigint UNSIGNED DEFAULT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('appointment','product','service','deposit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','sent','paid','overdue','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `subtotal` decimal(10,2) NOT NULL,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '20.00',
  `tax_amount` decimal(8,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `remaining_amount` decimal(10,2) GENERATED ALWAYS AS ((`total_amount` - `paid_amount`)) STORED,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `paid_date` date DEFAULT NULL,
  `client_address` text COLLATE utf8mb4_unicode_ci,
  `client_email` text COLLATE utf8mb4_unicode_ci,
  `client_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `items` json NOT NULL,
  `payment_method` enum('cash','card','bank_transfer','stripe','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `payment_terms` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"43a3c82d-f7ac-4ad4-9c6f-d48f2accde28\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:400;}s:6:\\\"height\\\";a:1:{i:0;i:133;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:6:\\\"banner\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771590796,\"delay\":null}', 0, NULL, 1771590796, 1771590796),
(2, 'default', '{\"uuid\":\"c95813f4-cb45-4317-90d5-06c8a783e9d5\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:2;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:400;}s:6:\\\"height\\\";a:1:{i:0;i:400;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:9:\\\"portfolio\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771590876,\"delay\":null}', 0, NULL, 1771590876, 1771590876),
(3, 'default', '{\"uuid\":\"c64cb944-6198-4ea7-bc82-12d9bd1fdfa0\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:2;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:400;}s:6:\\\"height\\\";a:1:{i:0;i:400;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:9:\\\"portfolio\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771590876,\"delay\":null}', 0, NULL, 1771590876, 1771590876),
(4, 'default', '{\"uuid\":\"5abd33dd-7ef2-4c52-8647-1adce5a585ea\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:2;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:400;}s:6:\\\"height\\\";a:1:{i:0;i:400;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:9:\\\"portfolio\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:5;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771590876,\"delay\":null}', 0, NULL, 1771590876, 1771590876),
(5, 'default', '{\"uuid\":\"a7f9ab17-2552-4a76-9415-afd6b4908c9c\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:2;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:400;}s:6:\\\"height\\\";a:1:{i:0;i:400;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:9:\\\"portfolio\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:6;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771590876,\"delay\":null}', 0, NULL, 1771590876, 1771590876),
(6, 'default', '{\"uuid\":\"97286879-0bc4-473f-ae86-0030adc29c29\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:3;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:400;}s:6:\\\"height\\\";a:1:{i:0;i:400;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:8:\\\"drawings\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:7;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771590885,\"delay\":null}', 0, NULL, 1771590885, 1771590885),
(7, 'default', '{\"uuid\":\"e79eea39-7d65-42e7-9fcd-9ac727caec07\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:3;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:400;}s:6:\\\"height\\\";a:1:{i:0;i:400;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:8:\\\"drawings\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:8;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771590885,\"delay\":null}', 0, NULL, 1771590885, 1771590885),
(8, 'default', '{\"uuid\":\"82052a58-fed0-4a02-9a98-443b2716cd20\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:3;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:400;}s:6:\\\"height\\\";a:1:{i:0;i:400;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:8:\\\"drawings\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:9;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771590885,\"delay\":null}', 0, NULL, 1771590885, 1771590885),
(9, 'default', '{\"uuid\":\"70eec42c-7bd8-4ea1-aff2-86f6e85311ea\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:4;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:200;}s:6:\\\"height\\\";a:1:{i:0;i:200;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:12:\\\"before_after\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:10;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771590895,\"delay\":null}', 0, NULL, 1771590895, 1771590895),
(10, 'default', '{\"uuid\":\"4ee2256b-f97b-4822-bf2c-99e88a2d0f12\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:4;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:200;}s:6:\\\"height\\\";a:1:{i:0;i:200;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:12:\\\"before_after\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:11;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771590895,\"delay\":null}', 0, NULL, 1771590895, 1771590895);
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(11, 'default', '{\"uuid\":\"10bf88d1-8561-423f-893d-3ef0214f9f45\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:4;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:200;}s:6:\\\"height\\\";a:1:{i:0;i:200;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:12:\\\"before_after\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:12;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771590907,\"delay\":null}', 0, NULL, 1771590907, 1771590907),
(12, 'default', '{\"uuid\":\"618cf476-a3c2-4fca-9d73-fcee9aef4455\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:4;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:200;}s:6:\\\"height\\\";a:1:{i:0;i:200;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:12:\\\"before_after\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:13;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771590907,\"delay\":null}', 0, NULL, 1771590907, 1771590907),
(13, 'default', '{\"uuid\":\"71b0126f-5e76-4603-9a57-0970f494dc51\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:1;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:400;}s:6:\\\"height\\\";a:1:{i:0;i:133;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:6:\\\"banner\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:15;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771746487,\"delay\":null}', 0, NULL, 1771746487, 1771746487),
(14, 'default', '{\"uuid\":\"f5fd1fb0-9b98-4e13-9137-635a0835dd69\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:2;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:400;}s:6:\\\"height\\\";a:1:{i:0;i:400;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:9:\\\"portfolio\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:16;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771747378,\"delay\":null}', 0, NULL, 1771747378, 1771747378),
(15, 'default', '{\"uuid\":\"d11ea08d-02bd-46e2-8527-3c631d2c2716\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:2;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:400;}s:6:\\\"height\\\";a:1:{i:0;i:400;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:9:\\\"portfolio\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:17;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771747378,\"delay\":null}', 0, NULL, 1771747378, 1771747378),
(16, 'default', '{\"uuid\":\"635df35e-7724-4393-b093-544197021c4e\",\"displayName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\",\"command\":\"O:58:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Jobs\\\\PerformConversionsJob\\\":6:{s:14:\\\"\\u0000*\\u0000conversions\\\";O:52:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\ConversionCollection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:2;O:42:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Conversion\\\":11:{s:12:\\\"\\u0000*\\u0000fileNamer\\\";O:54:\\\"Spatie\\\\MediaLibrary\\\\Support\\\\FileNamer\\\\DefaultFileNamer\\\":0:{}s:28:\\\"\\u0000*\\u0000extractVideoFrameAtSecond\\\";d:0;s:16:\\\"\\u0000*\\u0000manipulations\\\";O:45:\\\"Spatie\\\\MediaLibrary\\\\Conversions\\\\Manipulations\\\":1:{s:16:\\\"\\u0000*\\u0000manipulations\\\";a:5:{s:8:\\\"optimize\\\";a:1:{i:0;O:36:\\\"Spatie\\\\ImageOptimizer\\\\OptimizerChain\\\":3:{s:13:\\\"\\u0000*\\u0000optimizers\\\";a:7:{i:0;O:42:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Jpegoptim\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m85\\\";i:1;s:7:\\\"--force\\\";i:2;s:11:\\\"--strip-all\\\";i:3;s:17:\\\"--all-progressive\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:9:\\\"jpegoptim\\\";}i:1;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Pngquant\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:7:\\\"--force\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"pngquant\\\";}i:2;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Optipng\\\":5:{s:7:\\\"options\\\";a:3:{i:0;s:3:\\\"-i0\\\";i:1;s:3:\\\"-o2\\\";i:2;s:6:\\\"-quiet\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"optipng\\\";}i:3;O:37:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Svgo\\\":5:{s:7:\\\"options\\\";a:1:{i:0;s:20:\\\"--disable=cleanupIDs\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:4:\\\"svgo\\\";}i:4;O:41:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Gifsicle\\\":5:{s:7:\\\"options\\\";a:2:{i:0;s:2:\\\"-b\\\";i:1;s:3:\\\"-O3\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:8:\\\"gifsicle\\\";}i:5;O:38:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Cwebp\\\":5:{s:7:\\\"options\\\";a:4:{i:0;s:4:\\\"-m 6\\\";i:1;s:8:\\\"-pass 10\\\";i:2;s:3:\\\"-mt\\\";i:3;s:5:\\\"-q 90\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:5:\\\"cwebp\\\";}i:6;O:40:\\\"Spatie\\\\ImageOptimizer\\\\Optimizers\\\\Avifenc\\\":6:{s:7:\\\"options\\\";a:8:{i:0;s:14:\\\"-a cq-level=23\\\";i:1;s:6:\\\"-j all\\\";i:2;s:7:\\\"--min 0\\\";i:3;s:8:\\\"--max 63\\\";i:4;s:12:\\\"--minalpha 0\\\";i:5;s:13:\\\"--maxalpha 63\\\";i:6;s:14:\\\"-a end-usage=q\\\";i:7;s:12:\\\"-a tune=ssim\\\";}s:9:\\\"imagePath\\\";s:0:\\\"\\\";s:10:\\\"binaryPath\\\";s:0:\\\"\\\";s:7:\\\"tmpPath\\\";N;s:10:\\\"binaryName\\\";s:7:\\\"avifenc\\\";s:16:\\\"decodeBinaryName\\\";s:7:\\\"avifdec\\\";}}s:9:\\\"\\u0000*\\u0000logger\\\";O:33:\\\"Spatie\\\\ImageOptimizer\\\\DummyLogger\\\":0:{}s:10:\\\"\\u0000*\\u0000timeout\\\";i:60;}}s:6:\\\"format\\\";a:1:{i:0;s:3:\\\"jpg\\\";}s:5:\\\"width\\\";a:1:{i:0;i:400;}s:6:\\\"height\\\";a:1:{i:0;i:400;}s:7:\\\"sharpen\\\";a:1:{i:0;i:10;}}}s:23:\\\"\\u0000*\\u0000performOnCollections\\\";a:1:{i:0;s:9:\\\"portfolio\\\";}s:17:\\\"\\u0000*\\u0000performOnQueue\\\";b:1;s:26:\\\"\\u0000*\\u0000keepOriginalImageFormat\\\";b:0;s:27:\\\"\\u0000*\\u0000generateResponsiveImages\\\";b:0;s:18:\\\"\\u0000*\\u0000widthCalculator\\\";N;s:24:\\\"\\u0000*\\u0000loadingAttributeValue\\\";N;s:16:\\\"\\u0000*\\u0000pdfPageNumber\\\";i:1;s:7:\\\"\\u0000*\\u0000name\\\";s:5:\\\"thumb\\\";}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:8:\\\"\\u0000*\\u0000media\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:49:\\\"Spatie\\\\MediaLibrary\\\\MediaCollections\\\\Models\\\\Media\\\";s:2:\\\"id\\\";i:18;s:9:\\\"relations\\\";a:1:{i:0;s:5:\\\"model\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:14:\\\"\\u0000*\\u0000onlyMissing\\\";b:0;s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\"},\"createdAt\":1771747378,\"delay\":null}', 0, NULL, 1771747378, 1771747378);

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint UNSIGNED NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`id`, `model_type`, `model_id`, `uuid`, `collection_name`, `name`, `file_name`, `mime_type`, `disk`, `conversions_disk`, `size`, `manipulations`, `custom_properties`, `generated_conversions`, `responsive_images`, `order_column`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 2, '401497cc-dcd0-4612-898a-d42668564a28', 'avatar', 'IMG_9849', 'IMG_9849.png', 'image/png', 'media', 'media', 1204537, '[]', '[]', '[]', '[]', 1, '2026-02-20 11:33:16', '2026-02-20 11:33:16'),
(2, 'App\\Models\\Tattooer', 1, 'b7e276c8-2e52-4de0-bb47-bacf7acc9cd8', 'banner', 'ChatGPT Image 27 janv. 2026, 15_54_40', 'ChatGPT-Image-27-janv.-2026,-15_54_40.png', 'image/png', 'public', 'public', 212397, '[]', '[]', '[]', '[]', 1, '2026-02-20 11:33:16', '2026-02-20 11:33:16'),
(3, 'App\\Models\\Tattooer', 1, 'c07d930c-86f3-43b4-b094-00ad284b6c02', 'portfolio', 'image0 (1)', 'image0-(1).png', 'image/png', 'public', 'public', 1986730, '[]', '{\"type\": \"tattoo\", \"uploaded_at\": \"2026-02-20T12:34:36.766615Z\"}', '[]', '[]', 2, '2026-02-20 11:34:36', '2026-02-20 11:34:36'),
(4, 'App\\Models\\Tattooer', 1, '4d56653a-ce60-4c2d-865e-6210261e05bb', 'portfolio', 'ChatGPT Image 27 janv. 2026, 15_57_38', 'ChatGPT-Image-27-janv.-2026,-15_57_38.png', 'image/png', 'public', 'public', 2890564, '[]', '{\"type\": \"tattoo\", \"uploaded_at\": \"2026-02-20T12:34:36.804054Z\"}', '[]', '[]', 3, '2026-02-20 11:34:36', '2026-02-20 11:34:36'),
(5, 'App\\Models\\Tattooer', 1, '460bb7ee-1b19-45c6-85e4-5ce30b79f641', 'portfolio', 'image0', 'image0.png', 'image/png', 'public', 'public', 1897189, '[]', '{\"type\": \"tattoo\", \"uploaded_at\": \"2026-02-20T12:34:36.813126Z\"}', '[]', '[]', 4, '2026-02-20 11:34:36', '2026-02-20 11:34:36'),
(6, 'App\\Models\\Tattooer', 1, 'e15529e7-c83c-4400-b3b5-23308ee23502', 'portfolio', 'ChatGPT Image 27 janv. 2026, 15_54_40', 'ChatGPT-Image-27-janv.-2026,-15_54_40.png', 'image/png', 'public', 'public', 212397, '[]', '{\"type\": \"tattoo\", \"uploaded_at\": \"2026-02-20T12:34:36.822643Z\"}', '[]', '[]', 5, '2026-02-20 11:34:36', '2026-02-20 11:34:36'),
(7, 'App\\Models\\Tattooer', 1, '4e74c3c0-a6fc-47f1-8472-2d1ac96e5d74', 'drawings', 'DominosUV_displacement', 'DominosUV_displacement.png', 'image/png', 'public', 'public', 163419, '[]', '{\"type\": \"drawings\", \"uploaded_at\": \"2026-02-20T12:34:45.390885Z\"}', '[]', '[]', 6, '2026-02-20 11:34:45', '2026-02-20 11:34:45'),
(8, 'App\\Models\\Tattooer', 1, '8f232498-06ab-4bab-9147-bf5ba205cf65', 'drawings', 'DominosUV_normal', 'DominosUV_normal.png', 'image/png', 'public', 'public', 2396767, '[]', '{\"type\": \"drawings\", \"uploaded_at\": \"2026-02-20T12:34:45.427189Z\"}', '[]', '[]', 7, '2026-02-20 11:34:45', '2026-02-20 11:34:45'),
(9, 'App\\Models\\Tattooer', 1, '2da1f92e-6e6b-41cf-99a0-efb35b4ca696', 'drawings', 'DominosUV_specular', 'DominosUV_specular.png', 'image/png', 'public', 'public', 222542, '[]', '{\"type\": \"drawings\", \"uploaded_at\": \"2026-02-20T12:34:45.435670Z\"}', '[]', '[]', 8, '2026-02-20 11:34:45', '2026-02-20 11:34:45'),
(10, 'App\\Models\\Tattooer', 1, '2bf58818-c572-4d31-ad5d-74fec72fe0d5', 'before_after', 'IMG_9849', 'IMG_9849.JPG', 'image/jpeg', 'public', 'public', 353905, '[]', '{\"type\": \"before\", \"pair_id\": \"pair_699854efdba74_1771590895\", \"description\": null, \"uploaded_at\": \"2026-02-20T12:34:55.900984Z\"}', '[]', '[]', 9, '2026-02-20 11:34:55', '2026-02-20 11:34:55'),
(11, 'App\\Models\\Tattooer', 1, 'e98ef446-1538-4b0b-bbdd-16cfaf9ec691', 'before_after', 'Gemini_Generated_Image_rn5e80rn5e80rn5e', 'Gemini_Generated_Image_rn5e80rn5e80rn5e.webp', 'image/webp', 'public', 'public', 1178344, '[]', '{\"type\": \"after\", \"pair_id\": \"pair_699854efdba74_1771590895\", \"description\": null, \"uploaded_at\": \"2026-02-20T12:34:55.936245Z\"}', '[]', '[]', 10, '2026-02-20 11:34:55', '2026-02-20 11:34:55'),
(12, 'App\\Models\\Tattooer', 1, '91d58e11-4103-47c3-b9ce-bd74dbd6bc58', 'before_after', 'IMG_9849', 'IMG_9849.JPG', 'image/jpeg', 'public', 'public', 353905, '[]', '{\"type\": \"before\", \"pair_id\": \"pair_699854fb84df8_1771590907\", \"description\": null, \"uploaded_at\": \"2026-02-20T12:35:07.545816Z\"}', '[]', '[]', 11, '2026-02-20 11:35:07', '2026-02-20 11:35:07'),
(13, 'App\\Models\\Tattooer', 1, '564944dc-d4af-4bc9-8253-31c1fb859a14', 'before_after', 'image0 (1)', 'image0-(1).png', 'image/png', 'public', 'public', 1986730, '[]', '{\"type\": \"after\", \"pair_id\": \"pair_699854fb84df8_1771590907\", \"description\": null, \"uploaded_at\": \"2026-02-20T12:35:07.582505Z\"}', '[]', '[]', 12, '2026-02-20 11:35:07', '2026-02-20 11:35:07'),
(14, 'App\\Models\\User', 8, 'd0190056-ad75-4961-8f9c-c5d0abb537c1', 'avatar', 'Gemini_Generated_Image_rn5e80rn5e80rn5e', 'Gemini_Generated_Image_rn5e80rn5e80rn5e.webp', 'image/webp', 'media', 'media', 1178344, '[]', '[]', '[]', '[]', 1, '2026-02-22 06:48:07', '2026-02-22 06:48:07'),
(15, 'App\\Models\\Piercer', 5, '212f0c5c-076d-4bf6-9798-508712f4a04f', 'banner', 'ChatGPT Image 27 janv. 2026, 15_57_38', 'ChatGPT-Image-27-janv.-2026,-15_57_38.png', 'image/png', 'public', 'public', 2890564, '[]', '[]', '[]', '[]', 1, '2026-02-22 06:48:07', '2026-02-22 06:48:07'),
(16, 'App\\Models\\Piercer', 5, '2d278ab4-7dc4-4530-b2d8-816e989650d2', 'portfolio', 'image0 (1)', 'image0-(1).png', 'image/png', 'public', 'public', 1986730, '[]', '{\"type\": \"tattoo\", \"uploaded_at\": \"2026-02-22T08:02:58.873262Z\"}', '[]', '[]', 2, '2026-02-22 07:02:58', '2026-02-22 07:02:58'),
(17, 'App\\Models\\Piercer', 5, '3a36dda1-a93f-4edb-90bb-36aea0acbb29', 'portfolio', 'ChatGPT Image 27 janv. 2026, 15_57_38', 'ChatGPT-Image-27-janv.-2026,-15_57_38.png', 'image/png', 'public', 'public', 2890564, '[]', '{\"type\": \"tattoo\", \"uploaded_at\": \"2026-02-22T08:02:58.912281Z\"}', '[]', '[]', 3, '2026-02-22 07:02:58', '2026-02-22 07:02:58'),
(18, 'App\\Models\\Piercer', 5, 'f1c2126e-9761-437b-89bc-5ed95ee8c5d6', 'portfolio', 'image0', 'image0.png', 'image/png', 'public', 'public', 1897189, '[]', '{\"type\": \"tattoo\", \"uploaded_at\": \"2026-02-22T08:02:58.921091Z\"}', '[]', '[]', 4, '2026-02-22 07:02:58', '2026-02-22 07:02:58');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_request_id` bigint UNSIGNED DEFAULT NULL,
  `sender_id` bigint UNSIGNED DEFAULT NULL,
  `sender_type` enum('tattooer','client','system') COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_by_tattooer_at` timestamp NULL DEFAULT NULL,
  `read_by_client_at` timestamp NULL DEFAULT NULL,
  `attachment_type` enum('image','document') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_design_version` tinyint(1) NOT NULL DEFAULT '0',
  `design_version_number` int DEFAULT NULL,
  `conversation_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_19_000001_create_studios_table', 1),
(5, '2024_01_19_000002_create_studio_subscriptions_table', 1),
(6, '2024_01_19_000003_create_studio_artists_table', 1),
(7, '2025_09_22_145432_add_two_factor_columns_to_users_table', 1),
(8, '2026_01_09_141927_create_permission_tables', 1),
(9, '2026_01_09_141956_create_customer_columns', 1),
(10, '2026_01_09_141957_create_subscriptions_table', 1),
(11, '2026_01_09_141958_create_subscription_items_table', 1),
(12, '2026_01_09_141959_add_meter_id_to_subscription_items_table', 1),
(13, '2026_01_09_142000_add_meter_event_name_to_subscription_items_table', 1),
(14, '2026_01_09_143813_create_tattooers_table', 1),
(15, '2026_01_09_143819_create_clients_table', 1),
(16, '2026_01_09_143826_create_booking_requests_table', 1),
(17, '2026_01_09_143831_create_appointments_table', 1),
(18, '2026_01_09_143835_create_conversations_table', 1),
(19, '2026_01_09_143837_create_conversation_user_table', 1),
(20, '2026_01_12_103000_add_fields_to_users_table', 1),
(21, '2026_01_12_112600_optimize_booking_requests_table', 1),
(22, '2026_01_12_161924_create_media_table', 1),
(23, '2026_01_12_162026_add_fcm_token_to_users_table', 1),
(24, '2026_01_13_125415_add_dispute_fields_to_appointments_table', 1),
(25, '2026_01_13_143707_create_personal_access_tokens_table', 1),
(26, '2026_01_14_133606_add_studio_id_to_tattooers_table', 1),
(27, '2026_01_14_142244_add_user_id_to_studios_table', 1),
(28, '2026_01_14_143836_create_messages_table', 1),
(29, '2026_01_15_000000_cleanup_messages_table', 1),
(30, '2026_01_16_121600_add_last_message_foreign_key_to_conversations_table', 1),
(31, '2026_01_17_140000_create_working_hours_table', 1),
(32, '2026_01_20_100000_create_availabilities_table', 1),
(33, '2026_01_20_110000_create_client_care_sheets_table', 1),
(34, '2026_01_20_120000_create_payments_table', 1),
(35, '2026_01_20_140000_create_traceability_tables', 1),
(36, '2026_01_21_000001_create_subscriptions_table', 1),
(37, '2026_01_21_000002_add_subscription_fields_to_tattooers', 1),
(38, '2026_01_22_120000_create_inventory_tables', 1),
(39, '2026_01_22_130000_create_accounting_tables', 1),
(40, '2026_01_23_000004_add_studio_fields_to_users_table', 1),
(41, '2026_01_23_110000_add_slug_to_tattooers_table', 1),
(42, '2026_01_23_120934_add_is_admin_to_users_table', 1),
(43, '2026_01_24_120101_add_payment_fields_to_booking_requests_table', 1),
(44, '2026_01_26_085253_add_expiration_fields_to_conversations', 1),
(45, '2026_01_26_085317_add_indexes_to_conversations_for_cleanup', 1),
(46, '2026_01_26_100508_create_compliance_records_table', 1),
(47, '2026_01_26_100524_add_compliance_fields_to_artists_tables', 1),
(48, '2026_01_26_101341_add_payment_mode_to_studios', 1),
(49, '2026_01_26_101411_create_studio_accounting_entries_table', 1),
(50, '2026_01_26_101436_add_recipient_info_to_payments', 1),
(51, '2026_01_26_141659_add_stripe_connect_status_to_tattooers', 1),
(52, '2026_01_28_102544_add_role_status_to_users_table', 1),
(53, '2026_01_28_104412_add_siret_to_tattooers_table', 1),
(54, '2026_01_28_151556_add_pseudo_to_users_table', 1),
(55, '2026_01_28_155540_create_piercers_table', 1),
(56, '2026_01_31_100002_create_consents_table', 1),
(57, '2026_01_31_100006_create_tattoo_histories_table', 1),
(58, '2026_01_31_100007_create_calendar_events_table', 1),
(59, '2026_01_31_100008_add_fields_to_clients_table', 1),
(60, '2026_01_31_104808_create_reviews_table', 1),
(61, '2026_01_31_130543_add_styles_to_tattooers_table', 1),
(62, '2026_01_31_200010_make_booking_request_id_nullable_in_messages_table', 1),
(63, '2026_01_31_200011_make_conversation_id_nullable_in_messages_table', 1),
(64, '2026_01_31_200012_add_read_columns_to_messages_table', 1),
(65, '2026_02_01_095459_add_studio_id_and_other_fields_to_piercers_table', 1),
(66, '2026_02_01_101303_standardize_appointments_datetime_columns', 1),
(67, '2026_02_01_114713_create_notifications_table', 1),
(68, '2026_02_03_100002_add_tattooer_notes_to_booking_requests', 1),
(69, '2026_02_03_120000_add_tattooer_profile_fields', 1),
(70, '2026_02_03_130917_add_working_hours_to_tattooers_table', 1),
(71, '2026_02_03_152732_add_experience_and_price_to_tattooers_table', 1),
(72, '2026_02_03_152756_add_wait_time_weeks_to_tattooers_table', 1),
(73, '2026_02_05_120000_add_complete_fields_to_users_table', 1),
(74, '2026_02_05_120001_create_roles_table', 1),
(75, '2026_02_05_120002_add_first_last_name_to_clients_table', 1),
(76, '2026_02_05_120003_add_first_last_name_to_tattooers_table', 1),
(77, '2026_02_05_120004_add_first_last_name_to_piercers_table', 1),
(78, '2026_02_05_120005_make_name_nullable_in_users_table', 1),
(79, '2026_02_07_183000_create_accounting_transactions_table', 1),
(80, '2026_02_08_110000_add_acceptance_fields_to_booking_requests_table', 1),
(81, '2026_02_08_130000_add_missing_acceptance_fields', 1),
(82, '2026_02_08_140000_add_overage_fields_to_booking_requests', 1),
(83, '2026_02_08_150000_create_accounting_transactions_table', 1),
(84, '2026_02_08_160000_update_accounting_transactions_table', 1),
(85, '2026_02_08_170000_check_accounting_transactions_structure', 1),
(86, '2026_02_08_180000_add_no_show_and_status_fields', 1),
(87, '2026_02_08_200000_add_final_missing_fields', 1),
(88, '2026_02_09_100000_remove_experience_years_from_tattooers', 1),
(89, '2026_02_09_100001_remove_wait_time_days_from_tattooers', 1),
(90, '2026_02_09_100002_remove_price_from_from_tattooers', 1),
(91, '2026_02_09_101610_add_admin_verified_at_to_tattooers_table', 1),
(92, '2026_02_09_165730_add_client_date_selection_fields_to_booking_requests', 1),
(93, '2026_02_09_184130_add_client_date_selection_fields_to_booking_requests', 1),
(94, '2026_02_10_143946_add_design_modifications_tracker_to_booking_requests', 1),
(95, '2026_02_11_121213_make_sender_id_nullable_in_messages', 1),
(96, '2026_02_11_130000_add_system_to_sender_type_enum', 1),
(97, '2026_02_11_153604_add_scheduled_status_to_appointments_table', 1),
(98, '2026_02_11_154105_add_date_confirmed_status_to_booking_requests_table', 1),
(99, '2026_02_11_155901_add_title_to_appointments_table', 1),
(100, '2026_02_12_155427_add_booking_request_id_to_consents_table', 1),
(101, '2026_02_13_090549_add_snat2026_consent_fields', 1),
(102, '2026_02_13_135026_add_completion_fields_to_appointments_table', 1),
(103, '2026_02_13_135904_add_missing_booking_request_statuses', 1),
(104, '2026_02_13_142913_add_notification_tracking_to_appointments_table', 1),
(105, '2026_02_13_145007_add_balance_fields_to_booking_requests_table', 1),
(106, '2026_02_13_145359_add_balance_payment_statuses_to_booking_requests_table', 1),
(107, '2026_02_14_152757_add_custom_styles_to_tattooers_table', 1),
(108, '2026_02_16_115847_add_booking_request_id_to_client_consent_forms_table', 1),
(109, '2026_02_16_120407_make_appointment_id_nullable_in_client_consent_forms_table', 1),
(110, '2026_02_16_121300_make_full_name_nullable_in_client_consent_forms_table', 1),
(111, '2026_02_16_121408_make_birth_date_nullable_in_client_consent_forms_table', 1),
(112, '2026_02_16_121524_make_id_document_type_nullable_in_client_consent_forms_table', 1),
(113, '2026_02_16_121719_make_id_document_number_nullable_in_client_consent_forms_table', 1),
(114, '2026_02_16_121847_make_id_document_expiry_nullable_in_client_consent_forms_table', 1),
(115, '2026_02_16_121955_make_phone_nullable_in_client_consent_forms_table', 1),
(116, '2026_02_16_122109_make_email_nullable_in_client_consent_forms_table', 1),
(117, '2026_02_16_122243_make_legacy_fields_nullable_in_client_consent_forms_table', 1),
(118, '2026_02_18_092820_add_tattooer_id_to_clients_table', 1),
(119, '2026_02_18_104430_add_client_id_to_traceability_records_table', 1),
(120, '2026_02_19_132501_add_aftercare_to_tattooers_table', 1),
(121, '2026_02_19_145459_create_complaints_table', 1),
(122, '2026_02_20_101359_update_reviews_table_add_default_is_visible', 1),
(123, '2026_02_20_112547_create_refunds_table', 1),
(124, '2026_02_20_112632_create_transactions_table', 1),
(125, '2026_02_21_171815_add_missing_columns_to_piercers_table', 2),
(126, '2026_02_22_082217_add_custom_pricing_note_to_piercers_table', 3),
(127, '2026_02_22_082308_add_missing_columns_to_piercers_table', 4);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `parental_consent_forms`
--

CREATE TABLE `parental_consent_forms` (
  `id` bigint UNSIGNED NOT NULL,
  `client_consent_form_id` bigint UNSIGNED NOT NULL,
  `tattooer_id` bigint UNSIGNED NOT NULL,
  `parent_full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_relationship` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id_document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id_document_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id_document_expiry` date NOT NULL,
  `parent_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_consents_to_tattoo` tinyint(1) NOT NULL DEFAULT '0',
  `parent_understands_risks` tinyint(1) NOT NULL DEFAULT '0',
  `parent_will_supervise_aftercare` tinyint(1) NOT NULL DEFAULT '0',
  `parent_consents_to_emergency_treatment` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id_document_photos` json DEFAULT NULL,
  `parent_signature` json DEFAULT NULL,
  `parent_ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','signed','verified','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `signed_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_request_id` bigint UNSIGNED NOT NULL,
  `stripe_payment_intent_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_charge_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EUR',
  `status` enum('pending','succeeded','failed','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_type` enum('deposit','remaining','full') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'deposit',
  `recipient_type` enum('artist','studio') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'artist = artiste direct, studio = studio centralisé',
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nom du destinataire pour affichage',
  `paid_at` timestamp NULL DEFAULT NULL,
  `failure_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `piercers`
--

CREATE TABLE `piercers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pseudo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `siret` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialization` enum('pierceur','bodemodeur','pierceur_bodemodeur') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `styles` json DEFAULT NULL,
  `custom_styles` json DEFAULT NULL,
  `years_of_experience` int DEFAULT NULL,
  `minimum_price` decimal(8,2) DEFAULT NULL,
  `wait_time_weeks_min` int DEFAULT NULL,
  `wait_time_weeks_max` int DEFAULT NULL,
  `working_hours` text COLLATE utf8mb4_unicode_ci,
  `aftercare_sheet` text COLLATE utf8mb4_unicode_ci,
  `aftercare_reminder_2h` tinyint(1) NOT NULL DEFAULT '1',
  `aftercare_reminder_7d` tinyint(1) NOT NULL DEFAULT '1',
  `aftercare_reminder_14d` tinyint(1) NOT NULL DEFAULT '1',
  `pricing_grid` json DEFAULT NULL,
  `custom_pricing_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piercing_types` json DEFAULT NULL,
  `default_appointment_duration` int UNSIGNED NOT NULL DEFAULT '45',
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_plan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free',
  `is_subscribed` tinyint(1) NOT NULL DEFAULT '0',
  `stripe_connect_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `has_compliance_badge` tinyint(1) NOT NULL DEFAULT '0',
  `admin_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `studio_id` bigint UNSIGNED DEFAULT NULL,
  `siret_verified` tinyint(1) NOT NULL DEFAULT '0',
  `is_decision_maker` tinyint(1) NOT NULL DEFAULT '0',
  `compliance_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_compliance_check_at` timestamp NULL DEFAULT NULL,
  `studio_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_connect_account_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_onboarding_complete` tinyint(1) NOT NULL DEFAULT '0',
  `stripe_connect_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_connect_activated_at` timestamp NULL DEFAULT NULL,
  `stripe_connect_last_transaction_at` timestamp NULL DEFAULT NULL,
  `stripe_connect_deactivated_at` timestamp NULL DEFAULT NULL,
  `has_accepted_payment_terms` tinyint(1) NOT NULL DEFAULT '0',
  `payment_terms_accepted_at` timestamp NULL DEFAULT NULL,
  `minimum_deposit` decimal(8,2) NOT NULL DEFAULT '50.00',
  `default_deposit_rate` decimal(5,2) NOT NULL DEFAULT '30.00',
  `default_client_payment_deadline_days` int NOT NULL DEFAULT '7',
  `default_design_versions_included` int NOT NULL DEFAULT '3',
  `weekday_wait_days` int NOT NULL DEFAULT '7',
  `weekend_wait_days` int NOT NULL DEFAULT '14',
  `current_plan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free',
  `upgraded_to_pro_at` timestamp NULL DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tiktok` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `piercers`
--

INSERT INTO `piercers` (`id`, `user_id`, `first_name`, `last_name`, `pseudo`, `siret`, `name`, `slug`, `specialization`, `bio`, `styles`, `custom_styles`, `years_of_experience`, `minimum_price`, `wait_time_weeks_min`, `wait_time_weeks_max`, `working_hours`, `aftercare_sheet`, `aftercare_reminder_2h`, `aftercare_reminder_7d`, `aftercare_reminder_14d`, `pricing_grid`, `custom_pricing_note`, `piercing_types`, `default_appointment_duration`, `city`, `postal_code`, `address`, `phone`, `email`, `subscription_plan`, `is_subscribed`, `stripe_connect_id`, `has_compliance_badge`, `admin_verified_at`, `created_at`, `updated_at`, `deleted_at`, `studio_id`, `siret_verified`, `is_decision_maker`, `compliance_status`, `last_compliance_check_at`, `studio_name`, `stripe_connect_account_id`, `stripe_onboarding_complete`, `stripe_connect_status`, `stripe_connect_activated_at`, `stripe_connect_last_transaction_at`, `stripe_connect_deactivated_at`, `has_accepted_payment_terms`, `payment_terms_accepted_at`, `minimum_deposit`, `default_deposit_rate`, `default_client_payment_deadline_days`, `default_design_versions_included`, `weekday_wait_days`, `weekend_wait_days`, `current_plan`, `upgraded_to_pro_at`, `instagram`, `facebook`, `tiktok`, `website`) VALUES
(5, 8, 'Christelle', 'Baudoin', 'KryssPik', '12584698765425', 'Christelle Baudoin', 'christelle-baudoin-draguignan', 'pierceur_bodemodeur', 'test Pierceur', '[]', '[]', 8, 20.00, 0, 1, '{\"lundi\":{\"open\":\"09:00\",\"close\":\"18:00\",\"break_start\":null,\"break_end\":null},\"mardi\":{\"open\":\"09:00\",\"close\":\"18:00\",\"break_start\":null,\"break_end\":null},\"mercredi\":{\"open\":\"09:00\",\"close\":\"18:00\",\"break_start\":null,\"break_end\":null},\"jeudi\":{\"open\":\"09:00\",\"close\":\"18:00\",\"break_start\":null,\"break_end\":null},\"vendredi\":{\"open\":\"09:00\",\"close\":\"18:00\",\"break_start\":null,\"break_end\":null},\"samedi\":{\"open\":\"09:00\",\"close\":\"18:00\",\"break_start\":null,\"break_end\":null},\"dimanche\":{\"open\":null,\"close\":null,\"break_start\":null,\"break_end\":null}}', NULL, 1, 1, 1, '\"[{\\\"type\\\":\\\"Lobe\\\",\\\"price\\\":25},{\\\"type\\\":\\\"H\\\\u00e9lix\\\",\\\"price\\\":35},{\\\"type\\\":\\\"Tragus\\\",\\\"price\\\":30}]\"', NULL, '[\"Lobe\", \"Hélix\", \"Tragus\", \"Anti-tragus\", \"Daith\", \"Conch\", \"Rook\", \"Industrial\", \"Septum\", \"Narine\", \"Sourcil\", \"Labret\", \"Langue\", \"Nombril\", \"Microdermal\"]', 45, 'Draguignan', '83300', '20 Avenue du 8 Mai 1945', '0761403949', 'kryssPik@icloud.com', 'free', 0, NULL, 0, '2026-02-22 06:55:27', '2026-02-21 15:42:49', '2026-02-22 07:44:44', NULL, NULL, 0, 0, NULL, NULL, 'pikpik', NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, 50.00, 30.00, 7, 3, 7, 14, 'free', NULL, 'pik', 'https://facebook.com/Pik', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `tattooer_id` bigint UNSIGNED DEFAULT NULL,
  `studio_id` bigint UNSIGNED DEFAULT NULL,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','sent','received','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `order_date` date NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_cost` decimal(8,2) NOT NULL DEFAULT '0.00',
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` bigint UNSIGNED NOT NULL,
  `inventory_item_id` bigint UNSIGNED DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_ordered` int NOT NULL,
  `quantity_received` int NOT NULL DEFAULT '0',
  `unit_price` decimal(8,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `refunds`
--

CREATE TABLE `refunds` (
  `id` bigint UNSIGNED NOT NULL,
  `payment_id` bigint UNSIGNED NOT NULL,
  `stripe_refund_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admin_id` bigint UNSIGNED DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `reviewable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reviewable_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `rating` decimal(3,2) NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2026-02-20 10:30:40', '2026-02-20 10:30:40'),
(2, 'tattooer', 'web', '2026-02-20 10:30:40', '2026-02-20 10:30:40'),
(3, 'client', 'web', '2026-02-20 10:30:40', '2026-02-20 10:30:40'),
(4, 'studio_owner', 'web', '2026-02-20 10:30:40', '2026-02-20 10:30:40'),
(5, 'studio_artist', 'web', '2026-02-20 10:30:40', '2026-02-20 10:30:40'),
(6, 'pierceur', 'web', '2026-02-21 20:08:27', '2026-02-21 20:08:27');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4F5j3M2PjIs58eMmcht6ujcI6xvMFxI7EarBGIwu', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaTMxUVJndWlGUWlSWDMwWUNwdGcwTnI4SFV2cDhoSWMyNEZLTENlQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly90YXR0b29saWItc2Fhcy50ZXN0IjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo4O30=', 1771751612);

-- --------------------------------------------------------

--
-- Structure de la table `studios`
--

CREATE TABLE `studios` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FR',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_media_links` json DEFAULT NULL,
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_images` json DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `opening_hours` json DEFAULT NULL,
  `facilities` json DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `siret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_customer_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_artists` int UNSIGNED NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `verified_at` timestamp NULL DEFAULT NULL,
  `payment_mode` enum('artist_direct','studio_managed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'artist_direct' COMMENT 'Mode paiement : direct artiste ou centralisé studio',
  `uses_accounting_module` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True si studio utilise module compta interne',
  `payment_mode_changed_at` timestamp NULL DEFAULT NULL COMMENT 'Date dernier changement mode paiement',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `studio_accounting_entries`
--

CREATE TABLE `studio_accounting_entries` (
  `id` bigint UNSIGNED NOT NULL,
  `studio_id` bigint UNSIGNED NOT NULL,
  `entry_type` enum('income','expense','artist_payout','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL COMMENT 'Montant de l''opération (en euros)',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Description libre de l''opération',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Catégorie définie par le studio',
  `payment_id` bigint UNSIGNED DEFAULT NULL,
  `studio_artist_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_date` date NOT NULL COMMENT 'Date de l''opération',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Notes libres du studio',
  `attachments` json DEFAULT NULL COMMENT 'Chemins vers factures/reçus (JSON)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `studio_artists`
--

CREATE TABLE `studio_artists` (
  `id` bigint UNSIGNED NOT NULL,
  `studio_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `artist_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `specialties` json DEFAULT NULL,
  `stripe_connect_account_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_connect_status` enum('not_connected','onboarding','active','inactive','reactivating') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_connected',
  `stripe_connect_activated_at` timestamp NULL DEFAULT NULL,
  `stripe_connect_last_transaction_at` timestamp NULL DEFAULT NULL,
  `stripe_connect_deactivated_at` timestamp NULL DEFAULT NULL,
  `has_accepted_payment_terms` tinyint(1) NOT NULL DEFAULT '0',
  `payment_terms_accepted_at` timestamp NULL DEFAULT NULL,
  `is_decision_maker` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True si acheteur/décideur (Certibiocide requis)',
  `compliance_status` enum('non_compliant','compliant','expiring_soon') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'non_compliant' COMMENT 'Statut global de conformité (auto-calculé)',
  `last_compliance_check_at` timestamp NULL DEFAULT NULL COMMENT 'Dernière vérification auto du statut',
  `status` enum('active','inactive','on_leave','deleted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `joined_at` date NOT NULL,
  `left_at` date DEFAULT NULL,
  `working_schedule` json DEFAULT NULL,
  `total_appointments` int UNSIGNED NOT NULL DEFAULT '0',
  `total_revenue` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credentials_managed_by_studio` tinyint(1) NOT NULL DEFAULT '1',
  `siret_verified` tinyint(1) NOT NULL DEFAULT '0',
  `stripe_onboarding_complete` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `studio_subscriptions`
--

CREATE TABLE `studio_subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `studio_id` bigint UNSIGNED NOT NULL,
  `stripe_subscription_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_customer_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_price_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','trialing','past_due','canceled','unpaid','incomplete') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `base_price` decimal(8,2) NOT NULL DEFAULT '79.99',
  `price_per_artist` decimal(8,2) NOT NULL DEFAULT '39.99',
  `total_price` decimal(8,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EUR',
  `billing_interval` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'month',
  `included_artists` int UNSIGNED NOT NULL DEFAULT '1',
  `current_artists` int UNSIGNED NOT NULL DEFAULT '1',
  `additional_artists` int UNSIGNED NOT NULL DEFAULT '0',
  `features` json DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `current_period_start` timestamp NOT NULL,
  `current_period_end` timestamp NOT NULL,
  `canceled_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `subscription_items`
--

CREATE TABLE `subscription_items` (
  `id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_product` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stripe_price` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meter_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `meter_event_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tattooers`
--

CREATE TABLE `tattooers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pseudo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `studio_id` bigint UNSIGNED DEFAULT NULL,
  `siret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `siret_verified` tinyint(1) NOT NULL DEFAULT '0',
  `is_decision_maker` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'True si acheteur/décideur (Certibiocide requis)',
  `compliance_status` enum('non_compliant','compliant','expiring_soon') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'non_compliant' COMMENT 'Statut global de conformité (auto-calculé)',
  `last_compliance_check_at` timestamp NULL DEFAULT NULL COMMENT 'Dernière vérification auto du statut',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `studio_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `working_hours` json DEFAULT NULL COMMENT 'Horaires de travail avec créneaux multiples',
  `styles` json DEFAULT NULL,
  `custom_styles` json DEFAULT NULL,
  `years_of_experience` int DEFAULT NULL,
  `minimum_price` decimal(8,2) DEFAULT NULL,
  `wait_time_weeks_min` int DEFAULT NULL,
  `wait_time_weeks_max` int DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_connect_account_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_connect_status` enum('not_connected','onboarding','active','inactive','reactivating') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'not_connected' COMMENT 'État activation Stripe Connect',
  `stripe_connect_activated_at` timestamp NULL DEFAULT NULL COMMENT 'Date activation Connect (début facturation 2€)',
  `stripe_connect_last_transaction_at` timestamp NULL DEFAULT NULL COMMENT 'Dernière transaction encaissée',
  `stripe_connect_deactivated_at` timestamp NULL DEFAULT NULL COMMENT 'Date désactivation (fin facturation 2€)',
  `has_accepted_payment_terms` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'CGU paiements acceptées',
  `payment_terms_accepted_at` timestamp NULL DEFAULT NULL COMMENT 'Date acceptation CGU',
  `current_plan` enum('free','pro') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free' COMMENT 'Plan actuel (dénormalisé pour requêtes rapides)',
  `is_subscribed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True si abonnement PRO actif',
  `has_compliance_badge` tinyint(1) NOT NULL DEFAULT '0',
  `upgraded_to_pro_at` timestamp NULL DEFAULT NULL COMMENT 'Date premier upgrade PRO',
  `stripe_onboarding_complete` tinyint(1) NOT NULL DEFAULT '0',
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tiktok` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minimum_deposit` decimal(8,2) NOT NULL DEFAULT '50.00',
  `default_deposit_rate` int NOT NULL DEFAULT '40',
  `default_client_payment_deadline_days` int NOT NULL DEFAULT '7',
  `default_tattooer_design_deadline_days` int NOT NULL DEFAULT '7',
  `default_design_versions_included` int NOT NULL DEFAULT '3',
  `weekday_wait_days` int NOT NULL DEFAULT '0',
  `weekend_wait_days` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `admin_verified_at` timestamp NULL DEFAULT NULL COMMENT 'Date à laquelle le compte a été validé par l''admin',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `aftercare_sheet` text COLLATE utf8mb4_unicode_ci,
  `aftercare_reminder_2h` tinyint(1) NOT NULL DEFAULT '1',
  `aftercare_reminder_7d` tinyint(1) NOT NULL DEFAULT '1',
  `aftercare_reminder_14d` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tattooers`
--

INSERT INTO `tattooers` (`id`, `user_id`, `first_name`, `last_name`, `pseudo`, `studio_id`, `siret`, `siret_verified`, `is_decision_maker`, `compliance_status`, `last_compliance_check_at`, `name`, `studio_name`, `bio`, `working_hours`, `styles`, `custom_styles`, `years_of_experience`, `minimum_price`, `wait_time_weeks_min`, `wait_time_weeks_max`, `slug`, `phone`, `address`, `city`, `postal_code`, `email`, `stripe_connect_account_id`, `stripe_connect_status`, `stripe_connect_activated_at`, `stripe_connect_last_transaction_at`, `stripe_connect_deactivated_at`, `has_accepted_payment_terms`, `payment_terms_accepted_at`, `current_plan`, `is_subscribed`, `has_compliance_badge`, `upgraded_to_pro_at`, `stripe_onboarding_complete`, `instagram`, `facebook`, `tiktok`, `website`, `minimum_deposit`, `default_deposit_rate`, `default_client_payment_deadline_days`, `default_tattooer_design_deadline_days`, `default_design_versions_included`, `weekday_wait_days`, `weekend_wait_days`, `created_at`, `updated_at`, `admin_verified_at`, `deleted_at`, `aftercare_sheet`, `aftercare_reminder_2h`, `aftercare_reminder_7d`, `aftercare_reminder_14d`) VALUES
(1, 2, 'Christopher', 'Mueller', 'Tito', NULL, '12584698765423', 0, 1, 'non_compliant', NULL, 'Christopher Mueller', 'Freak\'s Tattoo Shop', 'Test final', '{\"jeudi\": {\"open\": \"09:00\", \"close\": \"18:00\", \"break_end\": \"13:00\", \"break_start\": \"12:00\"}, \"lundi\": {\"open\": null, \"close\": null, \"break_end\": null, \"break_start\": null}, \"mardi\": {\"open\": \"09:00\", \"close\": \"18:00\", \"break_end\": \"13:00\", \"break_start\": \"12:00\"}, \"samedi\": {\"open\": \"09:00\", \"close\": \"18:00\", \"break_end\": null, \"break_start\": null}, \"dimanche\": {\"open\": null, \"close\": null, \"break_end\": null, \"break_start\": null}, \"mercredi\": {\"open\": \"09:00\", \"close\": \"18:00\", \"break_end\": null, \"break_start\": null}, \"vendredi\": {\"open\": \"09:00\", \"close\": \"18:00\", \"break_end\": \"13:00\", \"break_start\": \"12:00\"}}', '[\"Japonais\", \"Traditionnel\", \"Neo-traditionnel\"]', '[\"Manga\"]', 15, 80.00, 2, 6, 'christopher-mueller-draguignan', '0684877501', '15 Avenue du 8 Mai 1945', 'Draguignan', '83300', 'chrismueller314@icloud.com', NULL, 'not_connected', NULL, NULL, NULL, 0, NULL, 'free', 0, 0, NULL, 0, 'tito', 'https://facebook.com/Tito', NULL, 'https://mwdcreativesense.com', 50.00, 40, 7, 7, 3, 0, 0, '2026-02-20 11:23:06', '2026-02-21 14:40:38', '2026-02-21 14:40:25', NULL, NULL, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `tattooer_subscriptions`
--

CREATE TABLE `tattooer_subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `subscribable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscribable_id` bigint UNSIGNED NOT NULL,
  `plan` enum('free','pro','studio') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free' COMMENT 'free=7% commission, pro=49.99€/mois, studio=79.99€+39.99€/artiste',
  `status` enum('active','past_due','canceled','unpaid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `stripe_subscription_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID abonnement Stripe (null pour FREE)',
  `stripe_price_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ID prix Stripe',
  `current_period_start` timestamp NULL DEFAULT NULL COMMENT 'Début période facturation',
  `current_period_end` timestamp NULL DEFAULT NULL COMMENT 'Fin période facturation',
  `canceled_at` timestamp NULL DEFAULT NULL COMMENT 'Date annulation',
  `ends_at` timestamp NULL DEFAULT NULL COMMENT 'Fin effective abonnement',
  `price_monthly` decimal(8,2) DEFAULT NULL COMMENT 'Prix mensuel en euros (49.99 pour PRO, null pour FREE)',
  `commission_rate` decimal(5,2) NOT NULL DEFAULT '7.00' COMMENT 'Taux commission en % (7.00 pour FREE, 0.00 pour PRO)',
  `features` json DEFAULT NULL COMMENT 'Features activées selon plan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tattoo_histories`
--

CREATE TABLE `tattoo_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `bookable_id` bigint UNSIGNED NOT NULL,
  `bookable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_request_id` bigint UNSIGNED DEFAULT NULL,
  `tattoo_date` date NOT NULL,
  `body_location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int NOT NULL,
  `total_paid` decimal(10,2) NOT NULL,
  `payment_method` enum('stripe','cash','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `traceability_inks`
--

CREATE TABLE `traceability_inks` (
  `id` bigint UNSIGNED NOT NULL,
  `traceability_record_id` bigint UNSIGNED NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lot_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration_date` date NOT NULL,
  `quantity_ml` int NOT NULL DEFAULT '0',
  `photo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `traceability_needles`
--

CREATE TABLE `traceability_needles` (
  `id` bigint UNSIGNED NOT NULL,
  `traceability_record_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `lot_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration_date` date NOT NULL,
  `photo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `traceability_records`
--

CREATE TABLE `traceability_records` (
  `id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED DEFAULT NULL,
  `tattooer_id` bigint UNSIGNED NOT NULL,
  `session_date` date DEFAULT NULL,
  `appointment_id` bigint UNSIGNED NOT NULL,
  `client_consent_form_id` bigint UNSIGNED NOT NULL,
  `procedure_date` date NOT NULL,
  `procedure_start_time` time NOT NULL,
  `procedure_end_time` time NOT NULL,
  `sterile_equipment` json NOT NULL,
  `aftercare_products` json NOT NULL,
  `room_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `autoclave_batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `autoclave_test_date` date DEFAULT NULL,
  `procedure_photos` json DEFAULT NULL,
  `workstation_photos` json DEFAULT NULL,
  `procedure_notes` text COLLATE utf8mb4_unicode_ci,
  `client_condition_notes` text COLLATE utf8mb4_unicode_ci,
  `equipment_notes` text COLLATE utf8mb4_unicode_ci,
  `client_verified_photos` tinyint(1) NOT NULL DEFAULT '0',
  `tattooer_verified_traceability` tinyint(1) NOT NULL DEFAULT '0',
  `verified_at` timestamp NULL DEFAULT NULL,
  `verification_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tattoo_description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body_zone` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `payment_id` bigint UNSIGNED DEFAULT NULL,
  `stripe_payment_intent_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_charge_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` bigint UNSIGNED DEFAULT NULL,
  `artist_id` bigint UNSIGNED DEFAULT NULL,
  `artist_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `commission_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EUR',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refund_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `refund_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pseudo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Europe/Paris',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `banned_at` timestamp NULL DEFAULT NULL,
  `banned_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unbanned_at` timestamp NULL DEFAULT NULL,
  `unbanned_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `suspended_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `studio_id` bigint UNSIGNED DEFAULT NULL,
  `is_studio_owner` tinyint(1) NOT NULL DEFAULT '0',
  `is_studio_artist` tinyint(1) NOT NULL DEFAULT '0',
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fcm_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pm_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pm_last_four` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial_ends_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `pseudo`, `phone`, `birth_date`, `role_id`, `email`, `first_name`, `last_name`, `timezone`, `email_verified_at`, `password`, `role`, `status`, `banned_at`, `banned_reason`, `unbanned_at`, `unbanned_reason`, `suspended_at`, `suspended_reason`, `studio_id`, `is_studio_owner`, `is_studio_artist`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `fcm_token`, `is_active`, `is_admin`, `last_login_at`, `created_at`, `updated_at`, `stripe_id`, `pm_name`, `pm_last_four`, `trial_ends_at`) VALUES
(1, 'Super Admin', NULL, NULL, NULL, NULL, 'admin@inkpik.com', NULL, NULL, 'Europe/Paris', NULL, '$2y$12$au/MjgD1g4GH7LzeU0tse.toQm0Qg8uFv2IpJCtP.oqOpn8twrucC', 'admin', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, '2026-02-20 10:30:40', '2026-02-20 10:30:40', NULL, NULL, NULL, NULL),
(2, NULL, 'Tito', '0684877501', NULL, NULL, 'chrismueller314@icloud.com', 'Christopher', 'Mueller', 'Europe/Paris', NULL, '$2y$12$Q9zO5xrPG1c1dvhoDw7IB.EH1KoHfNgrWVfPzzqgsoiViYDml3fzq', 'tattooer', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, '2026-02-20 11:23:06', '2026-02-21 14:40:25', NULL, NULL, NULL, NULL),
(8, 'Christelle Baudoin', 'KryssPik', '0761403949', NULL, NULL, 'kryssPik@icloud.com', 'Christelle', 'Baudoin', 'Europe/Paris', NULL, '$2y$12$C1hx7aTplIM5/oanpVaQ0ew2jSUASsPGczQB6vaDBRgYenMnO/XFm', 'Piercer', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, '2026-02-21 15:42:49', '2026-02-22 06:55:27', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `working_hours`
--

CREATE TABLE `working_hours` (
  `id` bigint UNSIGNED NOT NULL,
  `owner_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` bigint UNSIGNED NOT NULL,
  `day_of_week` tinyint NOT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT '1',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `break_start` time DEFAULT NULL,
  `break_end` time DEFAULT NULL,
  `slot_duration_minutes` int NOT NULL DEFAULT '60',
  `buffer_time_minutes` int NOT NULL DEFAULT '15',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accounting_transactions`
--
ALTER TABLE `accounting_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `accounting_transactions_reference_unique` (`reference`),
  ADD KEY `accounting_transactions_appointment_id_foreign` (`appointment_id`),
  ADD KEY `accounting_transactions_client_id_foreign` (`client_id`),
  ADD KEY `accounting_transactions_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `accounting_transactions_tattooer_id_transaction_date_index` (`tattooer_id`,`transaction_date`),
  ADD KEY `accounting_transactions_studio_id_transaction_date_index` (`studio_id`,`transaction_date`),
  ADD KEY `accounting_transactions_type_index` (`type`),
  ADD KEY `accounting_transactions_status_index` (`status`),
  ADD KEY `accounting_transactions_reference_index` (`reference`),
  ADD KEY `accounting_transactions_booking_request_id_foreign` (`booking_request_id`);

--
-- Index pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_booking_request_id_foreign` (`booking_request_id`),
  ADD KEY `appointments_bookable_type_bookable_id_index` (`bookable_type`,`bookable_id`),
  ADD KEY `appointments_bookable_type_bookable_id_start_time_index` (`bookable_type`,`bookable_id`,`start_datetime`),
  ADD KEY `appointments_client_id_start_time_index` (`client_id`,`start_datetime`),
  ADD KEY `appointments_dispute_resolved_by_foreign` (`dispute_resolved_by`),
  ADD KEY `appointments_no_show_reported_at_index` (`no_show_reported_at`),
  ADD KEY `appointments_tattooer_absence_reported_at_index` (`tattooer_absence_reported_at`);

--
-- Index pour la table `availabilities`
--
ALTER TABLE `availabilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `availabilities_owner_type_owner_id_index` (`owner_type`,`owner_id`),
  ADD KEY `availabilities_owner_type_owner_id_date_index` (`owner_type`,`owner_id`,`date`),
  ADD KEY `availabilities_owner_type_owner_id_date_type_index` (`owner_type`,`owner_id`,`date`,`type`),
  ADD KEY `availabilities_appointment_id_index` (`appointment_id`);

--
-- Index pour la table `booking_requests`
--
ALTER TABLE `booking_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_requests_stripe_payment_intent_id_unique` (`stripe_payment_intent_id`),
  ADD KEY `booking_requests_bookable_type_bookable_id_index` (`bookable_type`,`bookable_id`),
  ADD KEY `booking_requests_client_id_bookable_type_bookable_id_index` (`client_id`,`bookable_type`,`bookable_id`),
  ADD KEY `booking_requests_client_payment_deadline_index` (`client_payment_deadline`),
  ADD KEY `booking_requests_tattooer_design_deadline_index` (`tattooer_design_deadline`),
  ADD KEY `booking_requests_status_index` (`status`),
  ADD KEY `booking_requests_confirmed_date_confirmed_period_index` (`confirmed_date`,`confirmed_period`),
  ADD KEY `booking_requests_deposit_deadline_hours_index` (`deposit_deadline_hours`),
  ADD KEY `booking_requests_refund_processed_at_index` (`refund_processed_at`);

--
-- Index pour la table `booking_transactions`
--
ALTER TABLE `booking_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_transactions_booking_request_id_type_index` (`booking_request_id`,`type`),
  ADD KEY `booking_transactions_stripe_payment_intent_id_index` (`stripe_payment_intent_id`),
  ADD KEY `booking_transactions_stripe_session_id_index` (`stripe_session_id`),
  ADD KEY `booking_transactions_user_id_type_index` (`user_id`,`type`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calendar_events_appointment_id_foreign` (`appointment_id`),
  ADD KEY `calendar_events_datetime_index` (`bookable_type`,`bookable_id`,`start_datetime`,`end_datetime`),
  ADD KEY `calendar_events_type_index` (`type`,`start_datetime`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_user_id_foreign` (`user_id`),
  ADD KEY `clients_first_name_last_name_index` (`first_name`,`last_name`),
  ADD KEY `clients_no_show_count_index` (`no_show_count`),
  ADD KEY `clients_tattooer_id_foreign` (`tattooer_id`);

--
-- Index pour la table `client_care_sheets`
--
ALTER TABLE `client_care_sheets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_care_sheets_tattooer_id_foreign` (`tattooer_id`),
  ADD KEY `client_care_sheets_client_id_tattooer_id_index` (`client_id`,`tattooer_id`),
  ADD KEY `client_care_sheets_appointment_id_index` (`appointment_id`);

--
-- Index pour la table `client_consent_forms`
--
ALTER TABLE `client_consent_forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_consent_forms_appointment_id_unique` (`appointment_id`),
  ADD KEY `client_consent_forms_verified_by_foreign` (`verified_by`),
  ADD KEY `client_consent_forms_client_id_appointment_id_index` (`client_id`,`appointment_id`),
  ADD KEY `client_consent_forms_tattooer_id_status_index` (`tattooer_id`,`status`),
  ADD KEY `client_consent_forms_booking_request_id_index` (`booking_request_id`);

--
-- Index pour la table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaints_booking_request_id_foreign` (`booking_request_id`),
  ADD KEY `complaints_user_id_foreign` (`user_id`);

--
-- Index pour la table `compliance_records`
--
ALTER TABLE `compliance_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_certification_per_artist` (`compliant_type`,`compliant_id`,`certification_type`),
  ADD KEY `compliant_index` (`compliant_type`,`compliant_id`),
  ADD KEY `compliance_records_verified_by_foreign` (`verified_by`),
  ADD KEY `compliance_records_status_index` (`status`),
  ADD KEY `compliance_records_expires_at_index` (`expires_at`),
  ADD KEY `compliance_records_certification_type_index` (`certification_type`);

--
-- Index pour la table `consents`
--
ALTER TABLE `consents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consents_client_id_bookable_type_bookable_id_index` (`client_id`,`bookable_type`,`bookable_id`),
  ADD KEY `consents_booking_request_id_index` (`booking_request_id`);

--
-- Index pour la table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conversations_booking_request_id_foreign` (`booking_request_id`),
  ADD KEY `conversations_status_index` (`status`),
  ADD KEY `conversations_last_message_at_index` (`last_message_at`),
  ADD KEY `conversations_last_message_id_foreign` (`last_message_id`),
  ADD KEY `conversations_expiry_type_index` (`expiry_type`),
  ADD KEY `conversations_expires_at_index` (`expires_at`),
  ADD KEY `conversations_is_expired_index` (`is_expired`),
  ADD KEY `expiry_lookup` (`expiry_type`,`expires_at`);

--
-- Index pour la table `conversation_user`
--
ALTER TABLE `conversation_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversation_user_conversation_id_user_id_unique` (`conversation_id`,`user_id`),
  ADD KEY `conversation_user_user_id_last_read_at_index` (`user_id`,`last_read_at`),
  ADD KEY `conversation_user_conversation_id_last_read_at_index` (`conversation_id`,`last_read_at`),
  ADD KEY `conversation_user_role_index` (`role`);

--
-- Index pour la table `expense_items`
--
ALTER TABLE `expense_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expense_items_expense_report_id_foreign` (`expense_report_id`),
  ADD KEY `expense_items_inventory_item_id_foreign` (`inventory_item_id`);

--
-- Index pour la table `expense_reports`
--
ALTER TABLE `expense_reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_reports_report_number_unique` (`report_number`),
  ADD KEY `expense_reports_studio_id_foreign` (`studio_id`),
  ADD KEY `expense_reports_approved_by_foreign` (`approved_by`),
  ADD KEY `expense_reports_tattooer_id_status_index` (`tattooer_id`,`status`),
  ADD KEY `expense_reports_report_number_index` (`report_number`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_items_sku_unique` (`sku`),
  ADD KEY `inventory_items_tattooer_id_category_index` (`tattooer_id`,`category`),
  ADD KEY `inventory_items_studio_id_category_index` (`studio_id`,`category`),
  ADD KEY `inventory_items_sku_index` (`sku`);

--
-- Index pour la table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_movements_tattooer_id_foreign` (`tattooer_id`),
  ADD KEY `inventory_movements_appointment_id_foreign` (`appointment_id`),
  ADD KEY `inventory_movements_inventory_item_id_created_at_index` (`inventory_item_id`,`created_at`),
  ADD KEY `inventory_movements_movement_type_index` (`movement_type`);

--
-- Index pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_studio_id_foreign` (`studio_id`),
  ADD KEY `invoices_appointment_id_foreign` (`appointment_id`),
  ADD KEY `invoices_tattooer_id_invoice_date_index` (`tattooer_id`,`invoice_date`),
  ADD KEY `invoices_client_id_status_index` (`client_id`,`status`),
  ADD KEY `invoices_invoice_number_index` (`invoice_number`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_booking_request_id_sender_id_index` (`booking_request_id`,`sender_id`),
  ADD KEY `messages_conversation_id_index` (`conversation_id`),
  ADD KEY `messages_conversation_id_created_at_index` (`conversation_id`,`created_at`),
  ADD KEY `messages_read_by_tattooer_at_index` (`read_by_tattooer_at`),
  ADD KEY `messages_read_by_client_at_index` (`read_by_client_at`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Index pour la table `parental_consent_forms`
--
ALTER TABLE `parental_consent_forms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parental_consent_forms_tattooer_id_foreign` (`tattooer_id`),
  ADD KEY `parental_consent_forms_verified_by_foreign` (`verified_by`),
  ADD KEY `parental_consent_forms_client_consent_form_id_status_index` (`client_consent_form_id`,`status`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_stripe_payment_intent_id_unique` (`stripe_payment_intent_id`),
  ADD KEY `payments_booking_request_id_payment_type_index` (`booking_request_id`,`payment_type`),
  ADD KEY `payments_status_index` (`status`),
  ADD KEY `payments_recipient_type_index` (`recipient_type`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Index pour la table `piercers`
--
ALTER TABLE `piercers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `piercers_siret_unique` (`siret`),
  ADD UNIQUE KEY `piercers_slug_unique` (`slug`),
  ADD KEY `piercers_user_id_foreign` (`user_id`),
  ADD KEY `piercers_studio_id_index` (`studio_id`),
  ADD KEY `piercers_siret_verified_index` (`siret_verified`),
  ADD KEY `piercers_stripe_connect_status_index` (`stripe_connect_status`),
  ADD KEY `piercers_first_name_last_name_index` (`first_name`,`last_name`);

--
-- Index pour la table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_order_number_unique` (`order_number`),
  ADD KEY `purchase_orders_studio_id_foreign` (`studio_id`),
  ADD KEY `purchase_orders_tattooer_id_status_index` (`tattooer_id`,`status`),
  ADD KEY `purchase_orders_order_number_index` (`order_number`);

--
-- Index pour la table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `purchase_order_items_inventory_item_id_foreign` (`inventory_item_id`);

--
-- Index pour la table `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `refunds_stripe_refund_id_unique` (`stripe_refund_id`),
  ADD KEY `refunds_admin_id_foreign` (`admin_id`),
  ADD KEY `refunds_payment_id_status_index` (`payment_id`,`status`),
  ADD KEY `refunds_stripe_refund_id_index` (`stripe_refund_id`);

--
-- Index pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_reviewable_type_reviewable_id_index` (`reviewable_type`,`reviewable_id`),
  ADD KEY `reviews_client_id_index` (`client_id`),
  ADD KEY `reviews_rating_index` (`rating`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `studios`
--
ALTER TABLE `studios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `studios_slug_unique` (`slug`),
  ADD UNIQUE KEY `studios_siret_unique` (`siret`),
  ADD UNIQUE KEY `studios_stripe_customer_id_unique` (`stripe_customer_id`),
  ADD KEY `studios_is_active_is_verified_index` (`is_active`,`is_verified`),
  ADD KEY `studios_city_index` (`city`),
  ADD KEY `studios_latitude_longitude_index` (`latitude`,`longitude`),
  ADD KEY `studios_user_id_foreign` (`user_id`),
  ADD KEY `studios_payment_mode_index` (`payment_mode`);

--
-- Index pour la table `studio_accounting_entries`
--
ALTER TABLE `studio_accounting_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `studio_accounting_entries_payment_id_foreign` (`payment_id`),
  ADD KEY `studio_accounting_entries_studio_artist_id_foreign` (`studio_artist_id`),
  ADD KEY `studio_accounting_entries_studio_id_entry_type_index` (`studio_id`,`entry_type`),
  ADD KEY `studio_accounting_entries_transaction_date_index` (`transaction_date`),
  ADD KEY `studio_accounting_entries_category_index` (`category`);

--
-- Index pour la table `studio_artists`
--
ALTER TABLE `studio_artists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `studio_artists_studio_id_user_id_unique` (`studio_id`,`user_id`),
  ADD UNIQUE KEY `studio_artists_slug_unique` (`slug`),
  ADD UNIQUE KEY `studio_artists_stripe_connect_account_id_unique` (`stripe_connect_account_id`),
  ADD KEY `studio_artists_user_id_foreign` (`user_id`),
  ADD KEY `studio_artists_studio_id_status_index` (`studio_id`,`status`),
  ADD KEY `studio_artists_compliance_status_index` (`compliance_status`),
  ADD KEY `studio_artists_stripe_connect_status_index` (`stripe_connect_status`),
  ADD KEY `studio_artists_stripe_connect_last_transaction_at_index` (`stripe_connect_last_transaction_at`);

--
-- Index pour la table `studio_subscriptions`
--
ALTER TABLE `studio_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `studio_subscriptions_stripe_subscription_id_unique` (`stripe_subscription_id`),
  ADD KEY `studio_subscriptions_studio_id_status_index` (`studio_id`,`status`),
  ADD KEY `studio_subscriptions_current_period_end_index` (`current_period_end`);

--
-- Index pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscriptions_stripe_id_unique` (`stripe_id`),
  ADD KEY `subscriptions_user_id_stripe_status_index` (`user_id`,`stripe_status`);

--
-- Index pour la table `subscription_items`
--
ALTER TABLE `subscription_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_items_stripe_id_unique` (`stripe_id`),
  ADD KEY `subscription_items_subscription_id_stripe_price_index` (`subscription_id`,`stripe_price`);

--
-- Index pour la table `tattooers`
--
ALTER TABLE `tattooers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tattooers_siret_unique` (`siret`),
  ADD UNIQUE KEY `tattooers_slug_unique` (`slug`),
  ADD UNIQUE KEY `tattooers_stripe_connect_account_id_unique` (`stripe_connect_account_id`),
  ADD KEY `tattooers_user_id_foreign` (`user_id`),
  ADD KEY `tattooers_studio_id_index` (`studio_id`),
  ADD KEY `tattooers_siret_index` (`siret`),
  ADD KEY `tattooers_compliance_status_index` (`compliance_status`),
  ADD KEY `tattooers_stripe_connect_status_index` (`stripe_connect_status`),
  ADD KEY `tattooers_stripe_connect_last_transaction_at_index` (`stripe_connect_last_transaction_at`),
  ADD KEY `tattooers_first_name_last_name_index` (`first_name`,`last_name`);

--
-- Index pour la table `tattooer_subscriptions`
--
ALTER TABLE `tattooer_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tattooer_subscriptions_stripe_subscription_id_unique` (`stripe_subscription_id`),
  ADD KEY `tattooer_subscriptions_subscribable_type_subscribable_id_index` (`subscribable_type`,`subscribable_id`),
  ADD KEY `subscribable_index` (`subscribable_type`,`subscribable_id`),
  ADD KEY `tattooer_subscriptions_status_index` (`status`),
  ADD KEY `tattooer_subscriptions_plan_index` (`plan`),
  ADD KEY `tattooer_subscriptions_stripe_subscription_id_index` (`stripe_subscription_id`);

--
-- Index pour la table `tattoo_histories`
--
ALTER TABLE `tattoo_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tattoo_histories_bookable_index` (`client_id`,`bookable_type`,`bookable_id`),
  ADD KEY `tattoo_histories_client_date_index` (`client_id`,`tattoo_date`),
  ADD KEY `tattoo_histories_booking_request_id_index` (`booking_request_id`);

--
-- Index pour la table `traceability_inks`
--
ALTER TABLE `traceability_inks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `traceability_inks_traceability_record_id_foreign` (`traceability_record_id`),
  ADD KEY `traceability_inks_lot_number_index` (`lot_number`);

--
-- Index pour la table `traceability_needles`
--
ALTER TABLE `traceability_needles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `traceability_needles_traceability_record_id_foreign` (`traceability_record_id`),
  ADD KEY `traceability_needles_lot_number_index` (`lot_number`);

--
-- Index pour la table `traceability_records`
--
ALTER TABLE `traceability_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `traceability_records_appointment_id_unique` (`appointment_id`),
  ADD KEY `traceability_records_client_consent_form_id_foreign` (`client_consent_form_id`),
  ADD KEY `traceability_records_tattooer_id_procedure_date_index` (`tattooer_id`,`procedure_date`),
  ADD KEY `traceability_records_appointment_id_index` (`appointment_id`),
  ADD KEY `traceability_records_client_id_index` (`client_id`);

--
-- Index pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_payment_id_foreign` (`payment_id`),
  ADD KEY `transactions_client_id_status_index` (`client_id`,`status`),
  ADD KEY `transactions_artist_id_artist_type_index` (`artist_id`,`artist_type`),
  ADD KEY `transactions_stripe_payment_intent_id_index` (`stripe_payment_intent_id`),
  ADD KEY `transactions_processed_at_index` (`processed_at`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_pseudo_unique` (`pseudo`),
  ADD KEY `users_stripe_id_index` (`stripe_id`),
  ADD KEY `users_studio_id_is_studio_owner_index` (`studio_id`,`is_studio_owner`),
  ADD KEY `users_role_id_index` (`role_id`),
  ADD KEY `users_status_index` (`status`),
  ADD KEY `users_banned_at_index` (`banned_at`),
  ADD KEY `users_suspended_at_index` (`suspended_at`);

--
-- Index pour la table `working_hours`
--
ALTER TABLE `working_hours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `working_hours_owner_type_owner_id_index` (`owner_type`,`owner_id`),
  ADD KEY `working_hours_owner_type_owner_id_is_open_index` (`owner_type`,`owner_id`,`is_open`),
  ADD KEY `working_hours_owner_type_owner_id_day_of_week_index` (`owner_type`,`owner_id`,`day_of_week`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `accounting_transactions`
--
ALTER TABLE `accounting_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `availabilities`
--
ALTER TABLE `availabilities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `booking_requests`
--
ALTER TABLE `booking_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `booking_transactions`
--
ALTER TABLE `booking_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `calendar_events`
--
ALTER TABLE `calendar_events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `client_care_sheets`
--
ALTER TABLE `client_care_sheets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `client_consent_forms`
--
ALTER TABLE `client_consent_forms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `compliance_records`
--
ALTER TABLE `compliance_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `consents`
--
ALTER TABLE `consents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `conversation_user`
--
ALTER TABLE `conversation_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `expense_items`
--
ALTER TABLE `expense_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `expense_reports`
--
ALTER TABLE `expense_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT pour la table `parental_consent_forms`
--
ALTER TABLE `parental_consent_forms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `piercers`
--
ALTER TABLE `piercers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `studios`
--
ALTER TABLE `studios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `studio_accounting_entries`
--
ALTER TABLE `studio_accounting_entries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `studio_artists`
--
ALTER TABLE `studio_artists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `studio_subscriptions`
--
ALTER TABLE `studio_subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `subscription_items`
--
ALTER TABLE `subscription_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tattooers`
--
ALTER TABLE `tattooers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `tattooer_subscriptions`
--
ALTER TABLE `tattooer_subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tattoo_histories`
--
ALTER TABLE `tattoo_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `traceability_inks`
--
ALTER TABLE `traceability_inks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `traceability_needles`
--
ALTER TABLE `traceability_needles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `traceability_records`
--
ALTER TABLE `traceability_records`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `working_hours`
--
ALTER TABLE `working_hours`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `accounting_transactions`
--
ALTER TABLE `accounting_transactions`
  ADD CONSTRAINT `accounting_transactions_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `accounting_transactions_booking_request_id_foreign` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounting_transactions_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `accounting_transactions_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `accounting_transactions_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `accounting_transactions_tattooer_id_foreign` FOREIGN KEY (`tattooer_id`) REFERENCES `tattooers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_booking_request_id_foreign` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_dispute_resolved_by_foreign` FOREIGN KEY (`dispute_resolved_by`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `availabilities`
--
ALTER TABLE `availabilities`
  ADD CONSTRAINT `availabilities_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `booking_requests`
--
ALTER TABLE `booking_requests`
  ADD CONSTRAINT `booking_requests_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `booking_transactions`
--
ALTER TABLE `booking_transactions`
  ADD CONSTRAINT `booking_transactions_booking_request_id_foreign` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `calendar_events`
--
ALTER TABLE `calendar_events`
  ADD CONSTRAINT `calendar_events_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_tattooer_id_foreign` FOREIGN KEY (`tattooer_id`) REFERENCES `tattooers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `client_care_sheets`
--
ALTER TABLE `client_care_sheets`
  ADD CONSTRAINT `client_care_sheets_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_care_sheets_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_care_sheets_tattooer_id_foreign` FOREIGN KEY (`tattooer_id`) REFERENCES `tattooers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `client_consent_forms`
--
ALTER TABLE `client_consent_forms`
  ADD CONSTRAINT `client_consent_forms_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_consent_forms_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_consent_forms_tattooer_id_foreign` FOREIGN KEY (`tattooer_id`) REFERENCES `tattooers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_consent_forms_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_booking_request_id_foreign` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `compliance_records`
--
ALTER TABLE `compliance_records`
  ADD CONSTRAINT `compliance_records_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `consents`
--
ALTER TABLE `consents`
  ADD CONSTRAINT `consents_booking_request_id_foreign` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consents_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_booking_request_id_foreign` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `conversations_last_message_id_foreign` FOREIGN KEY (`last_message_id`) REFERENCES `messages` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `conversation_user`
--
ALTER TABLE `conversation_user`
  ADD CONSTRAINT `conversation_user_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversation_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `expense_items`
--
ALTER TABLE `expense_items`
  ADD CONSTRAINT `expense_items_expense_report_id_foreign` FOREIGN KEY (`expense_report_id`) REFERENCES `expense_reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_items_inventory_item_id_foreign` FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `expense_reports`
--
ALTER TABLE `expense_reports`
  ADD CONSTRAINT `expense_reports_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expense_reports_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_reports_tattooer_id_foreign` FOREIGN KEY (`tattooer_id`) REFERENCES `tattooers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD CONSTRAINT `inventory_items_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_items_tattooer_id_foreign` FOREIGN KEY (`tattooer_id`) REFERENCES `tattooers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_movements_inventory_item_id_foreign` FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_movements_tattooer_id_foreign` FOREIGN KEY (`tattooer_id`) REFERENCES `tattooers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_tattooer_id_foreign` FOREIGN KEY (`tattooer_id`) REFERENCES `tattooers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_booking_request_id_foreign` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `parental_consent_forms`
--
ALTER TABLE `parental_consent_forms`
  ADD CONSTRAINT `parental_consent_forms_client_consent_form_id_foreign` FOREIGN KEY (`client_consent_form_id`) REFERENCES `client_consent_forms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parental_consent_forms_tattooer_id_foreign` FOREIGN KEY (`tattooer_id`) REFERENCES `tattooers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parental_consent_forms_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_booking_request_id_foreign` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `piercers`
--
ALTER TABLE `piercers`
  ADD CONSTRAINT `piercers_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `piercers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_orders_tattooer_id_foreign` FOREIGN KEY (`tattooer_id`) REFERENCES `tattooers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_inventory_item_id_foreign` FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `refunds_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `studios`
--
ALTER TABLE `studios`
  ADD CONSTRAINT `studios_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `studio_accounting_entries`
--
ALTER TABLE `studio_accounting_entries`
  ADD CONSTRAINT `studio_accounting_entries_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `studio_accounting_entries_studio_artist_id_foreign` FOREIGN KEY (`studio_artist_id`) REFERENCES `studio_artists` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `studio_accounting_entries_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `studio_artists`
--
ALTER TABLE `studio_artists`
  ADD CONSTRAINT `studio_artists_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `studio_artists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `studio_subscriptions`
--
ALTER TABLE `studio_subscriptions`
  ADD CONSTRAINT `studio_subscriptions_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `subscription_items`
--
ALTER TABLE `subscription_items`
  ADD CONSTRAINT `subscription_items_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tattooers`
--
ALTER TABLE `tattooers`
  ADD CONSTRAINT `tattooers_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tattooers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tattoo_histories`
--
ALTER TABLE `tattoo_histories`
  ADD CONSTRAINT `tattoo_histories_booking_request_id_foreign` FOREIGN KEY (`booking_request_id`) REFERENCES `booking_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tattoo_histories_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `traceability_inks`
--
ALTER TABLE `traceability_inks`
  ADD CONSTRAINT `traceability_inks_traceability_record_id_foreign` FOREIGN KEY (`traceability_record_id`) REFERENCES `traceability_records` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `traceability_needles`
--
ALTER TABLE `traceability_needles`
  ADD CONSTRAINT `traceability_needles_traceability_record_id_foreign` FOREIGN KEY (`traceability_record_id`) REFERENCES `traceability_records` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `traceability_records`
--
ALTER TABLE `traceability_records`
  ADD CONSTRAINT `traceability_records_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `traceability_records_client_consent_form_id_foreign` FOREIGN KEY (`client_consent_form_id`) REFERENCES `client_consent_forms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `traceability_records_tattooer_id_foreign` FOREIGN KEY (`tattooer_id`) REFERENCES `tattooers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_studio_id_foreign` FOREIGN KEY (`studio_id`) REFERENCES `studios` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
