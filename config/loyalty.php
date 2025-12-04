<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Points Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how loyalty points are earned, converted, and expire.
    |
    */
    'points' => [
        // Earning rate percentage per tier (points earned per ₱100 spent)
        'earning_rate' => [
            'bronze' => 1,      // 1% cashback in points
            'silver' => 2,      // 2% cashback in points
            'gold' => 3,        // 3% cashback in points
            'platinum' => 5,    // 5% cashback in points
        ],

        // Conversion rate: how much ₱ each point is worth when redeeming
        'conversion_rate' => 1,  // 1 point = ₱1

        // Minimum points required for redemption
        'minimum_redemption' => 100,

        // Maximum percentage of order value that can be paid with points
        'maximum_redemption_percentage' => 50,

        // Points expiration in months (from earning date)
        'expiry_months' => 12,

        // Days before expiry to send warning notification
        'expiry_warning_days' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Tier Configuration
    |--------------------------------------------------------------------------
    |
    | Define tier thresholds (lifetime earned points) and automatic discounts.
    |
    */
    'tiers' => [
        'bronze' => [
            'points' => 0,
            'discount' => 0,
        ],
        'silver' => [
            'points' => 5000,
            'discount' => 5,
        ],
        'gold' => [
            'points' => 15000,
            'discount' => 10,
        ],
        'platinum' => [
            'points' => 50000,
            'discount' => 15,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Bonus Points Configuration
    |--------------------------------------------------------------------------
    |
    | Define bonus points for various actions.
    |
    */
    'bonuses' => [
        // First project completion bonus
        'first_project' => 500,

        // Milestone completion bonus
        'milestone_completion' => 200,

        // Project completion bonus
        'project_completion' => 500,

        // Referral bonus (when referred client completes first payment)
        'referral' => 1000,

        // Feedback submission bonus
        'feedback_submission' => 100,

        // Anniversary bonus (yearly)
        'anniversary' => 1000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Transaction Types
    |--------------------------------------------------------------------------
    |
    | Valid transaction type constants for consistency.
    |
    */
    'transaction_types' => [
        'earned',
        'redeemed',
        'expired',
        'adjusted',
        'refunded',
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Settings
    |--------------------------------------------------------------------------
    |
    | Administrative limits and controls.
    |
    */
    'admin' => [
        // Maximum points that can be adjusted in a single operation
        'max_adjustment' => 100000,

        // Require reason for adjustments above this threshold
        'adjustment_reason_threshold' => 1000,
    ],
];
