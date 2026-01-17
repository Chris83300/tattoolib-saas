<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuration TattooLib - Système de Planning
    |--------------------------------------------------------------------------
    |
    | Ce fichier contient les paramètres de configuration pour le système
    | de planning complet de TattooLib.
    |
    */

    'availability' => [
        // Fenêtre glissante maintenue automatiquement
        'rolling_window_days' => env('AVAILABILITY_WINDOW_DAYS', 90), // 3 mois

        // Génération initiale
        'initial_generation_days' => env('AVAILABILITY_INITIAL_DAYS', 365), // 1 an

        // Nettoyage du passé
        'keep_past_days' => env('AVAILABILITY_KEEP_PAST_DAYS', 30), // 30 jours d'historique

        // Limite recherche client
        'client_search_limit_months' => env('CLIENT_SEARCH_LIMIT_MONTHS', 3),

        // Durée minimale/maximale d'un créneau (minutes)
        'min_slot_duration' => env('MIN_SLOT_DURATION', 30),
        'max_slot_duration' => env('MAX_SLOT_DURATION', 480), // 8h

        // Buffer entre les créneaux (minutes)
        'slot_buffer_minutes' => env('SLOT_BUFFER_MINUTES', 15),
    ],

    'booking_requests' => [
        // Délai de paiement acompte (heures)
        'default_deposit_deadline_hours' => env('DEFAULT_DEPOSIT_DEADLINE_HOURS', 72), // 3 jours

        // Taux d'acompte par défaut (%)
        'default_deposit_rate' => env('DEFAULT_DEPOSIT_RATE', 30),

        // Délai de réponse du tatoueur (heures)
        'tattooer_response_deadline_hours' => env('TATTOOER_RESPONSE_DEADLINE_HOURS', 48),

        // Nombre maximum de versions de design incluses
        'max_included_design_versions' => env('MAX_INCLUDED_DESIGN_VERSIONS', 3),
    ],

    'planning' => [
        // Vue par défaut pour le dashboard
        'default_view' => env('DEFAULT_PLANNING_VIEW', 'week'), // day|week|month

        // Nombre de jours affichés par vue
        'days_in_week_view' => 7,
        'days_in_month_view' => 31,

        // Heures de travail par défaut (si non configurées)
        'default_start_time' => env('DEFAULT_START_TIME', '09:00'),
        'default_end_time' => env('DEFAULT_END_TIME', '18:00'),
        'default_break_start' => env('DEFAULT_BREAK_START', '12:00'),
        'default_break_end' => env('DEFAULT_BREAK_END', '13:00'),
    ],

    'notifications' => [
        // Rappels RDV
        'appointment_reminder_hours_before' => env('APPOINTMENT_REMINDER_HOURS_BEFORE', [24, 2]),

        // Notifications demandes expirées
        'expired_request_notification_enabled' => env('EXPIRED_REQUEST_NOTIFICATION_ENABLED', true),

        // Notifications nouveau booking
        'new_booking_notification_enabled' => env('NEW_BOOKING_NOTIFICATION_ENABLED', true),
    ],

    'external_sources' => [
        // Sources pour RDV externes
        'sources' => [
            'external_walk_in' => 'En boutique',
            'external_phone' => 'Par téléphone',
            'external_social' => 'Réseaux sociaux',
        ],
    ],

    'time_slots' => [
        // Créneaux prédéfinis pour le client
        'morning' => [
            'start' => '09:00',
            'end' => '12:00',
            'label' => 'Matin',
        ],
        'afternoon' => [
            'start' => '14:00',
            'end' => '18:00',
            'label' => 'Après-midi',
        ],
        'evening' => [
            'start' => '18:00',
            'end' => '21:00',
            'label' => 'Soirée',
        ],
    ],
];
