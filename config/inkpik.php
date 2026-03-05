<?php

return [
    'stripe' => [
        'pro_price_id' => env('STRIPE_PRICE_ID_PRO'), // rétrocompatibilité
    ],

    'plans' => [
        'starter' => [
            'name'            => 'Starter',
            'price'           => 9.99,
            'commission_rate' => 7.0,
        ],
        'pro' => [
            'name'            => 'Pro',
            'price'           => 29.99,
            'commission_rate' => 0.0,
        ],
        'studio' => [
            'name'               => 'Studio',
            'price'              => 59.99,
            'extra_artist_price' => 24.99,
            'commission_rate'    => 0.0,
            'included_artists'   => 1,
        ],
    ],

    'pricing' => [
        'starter' => [
            'price'          => env('STRIPE_PRICE_STARTER', 9.99),
            'stripe_price_id' => env('STRIPE_PRICE_ID_STARTER', ''),
            'commission_rate' => 0.07,
        ],
        'pro' => [
            'price'          => env('STRIPE_PRICE_PRO', 29.99),
            'stripe_price_id' => env('STRIPE_PRICE_ID_PRO', ''),
            'commission_rate' => 0.0,
        ],
        'studio' => [
            'price'              => env('STRIPE_PRICE_STUDIO', 59.99),
            'stripe_price_id'    => env('STRIPE_PRICE_ID_STUDIO', ''),
            'extra_artist_price' => env('STRIPE_PRICE_STUDIO_EXTRA', 24.99),
            'stripe_price_id_extra' => env('STRIPE_PRICE_ID_STUDIO_EXTRA', ''),
            'commission_rate'    => 0.0,
            'included_artists'   => 1,
        ],
        'trial_days'           => 14,
        'beta_discount_percent' => 30,
        'beta_coupon_id'        => env('STRIPE_BETA_COUPON_ID', 'BETA-LAUNCH-30'),
    ],
];
