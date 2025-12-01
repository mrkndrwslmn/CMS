<?php

return [
    // Tiered Reward System Based on Payment Amount
    // More realistic and sustainable for business
    'reward_tiers' => [
        [
            'min_amount' => 100000,
            'max_amount' => 200000,
            'referrer_percentage' => 3.0,   // 3% of payment amount (₱3K-6K)
            'referred_percentage' => 10.0,  // 10% coupon discount
        ],
        [
            'min_amount' => 200000,
            'max_amount' => 500000,
            'referrer_percentage' => 2.0,   // 2% of payment amount (₱4K-10K)
            'referred_percentage' => 8.0,   // 8% coupon discount
        ],
        [
            'min_amount' => 500000,
            'max_amount' => 1000000,
            'referrer_percentage' => 1.5,   // 1.5% of payment amount (₱7.5K-15K)
            'referred_percentage' => 5.0,   // 5% coupon discount
        ],
        [
            'min_amount' => 1000000,
            'max_amount' => null,           // No upper limit
            'referrer_percentage' => 1.0,   // 1% of payment amount (₱10K+)
            'referred_percentage' => 5.0,   // 5% coupon discount
        ],
    ],

    // Benefit Types Configuration
    'benefits' => [
        'referrer_default_type' => 'credits',  // ALWAYS credits (withdrawable money)
        'referred_default_type' => 'coupon',   // ALWAYS coupon (discount)
        
        // Credits Configuration
        'credits' => [
            'minimum_withdrawal' => 1000,      // Minimum PHP 1,000 to withdraw
            'withdrawal_fee_percentage' => 0,  // No withdrawal fee (can be changed)
            'withdrawal_methods' => [
                'bank_transfer' => 'Bank Transfer',
                'gcash' => 'GCash',
                'paymaya' => 'PayMaya / Maya',
                'paypal' => 'PayPal',
            ],
        ],
        
        // Coupon Configuration  
        'coupon' => [
            'validity_days' => 90,             // Valid for 90 days
            'min_purchase_amount' => 50000,    // Minimum PHP 50,000 to use coupon
            'max_discount_amount' => 30000,    // Maximum PHP 30,000 discount
            'stackable_with_loyalty' => false, // Cannot stack with loyalty discounts
        ],
    ],

    // Legacy Points System (kept for backward compatibility)
    'legacy_rewards' => [
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
        'min_account_age_days' => 0,        // Minimum days before can refer
        'allowed_roles' => ['client', 'adiutor'],  // Both clients and adiutors can refer
        'one_referral_per_user' => true,    // Each user can only be referred once
        'min_qualifying_amount' => 100000,  // Minimum payment to trigger rewards (PHP 100,000)
    ],

    // Tracking
    'tracking' => [
        'cookie_duration_days' => 30,   // How long to track referral in cookies
        'require_first_payment' => true, // Must complete payment to count
        'track_all_payments' => false,   // false = only first payment triggers reward
    ],
];
