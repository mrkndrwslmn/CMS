<?php

return [
    // Reward System
    'rewards' => [
        'referrer' => [
            'completion_points' => 1000,  // Points when referred user completes first payment
            'coupon_discount' => 20,       // 20% discount coupon
            'coupon_validity_days' => 60,  // Valid for 60 days
        ],
        'referred' => [
            'welcome_points' => 500,       // Immediate signup bonus
            'coupon_discount' => 15,       // 15% welcome discount
            'coupon_validity_days' => 30,  // Valid for 30 days
        ],
    ],

    // Code Generation
    'code' => [
        'format' => 'name_year_random', // FirstName + Year + Random
        'length' => 11,                 // Total length
        'uppercase' => true,            // Force uppercase
    ],

    // Eligibility
    'eligibility' => [
        'min_account_age_days' => 0,    // Minimum days before can refer
        'allowed_roles' => ['client'],  // Who can participate
        'one_referral_per_user' => true, // Each user can only be referred once
    ],

    // Tracking
    'tracking' => [
        'cookie_duration_days' => 30,   // How long to track referral in cookies
        'require_first_payment' => true, // Must complete payment to count
    ],
];
