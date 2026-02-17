<?php

return [
    'stripe' => [
        'pro_price_id' => env('STRIPE_PRO_PRICE_ID'),
    ],
    'plans' => [
        'free' => [
            'name' => 'Free',
            'price' => 0,
            'commission_rate' => 7.0,
        ],
        'pro' => [
            'name' => 'PRO',
            'price' => 49.99,
            'commission_rate' => 0.0,
        ],
    ],
];
