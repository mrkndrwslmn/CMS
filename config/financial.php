<?php

/**
 * Financial Configuration
 * 
 * Centralized configuration for all financial-related settings
 * including hourly rates, payout limits, and currency settings.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hourly Rate
    |--------------------------------------------------------------------------
    |
    | The default hourly rate (in PHP currency) used for adiutors when
    | no specific rate is set in their profile or project assignment.
    |
    */
    'default_hourly_rate' => env('DEFAULT_HOURLY_RATE', 500.00),

    /*
    |--------------------------------------------------------------------------
    | Minimum Payout Amount
    |--------------------------------------------------------------------------
    |
    | The minimum amount (in PHP currency) that an adiutor must have
    | in their wallet before they can request a payout.
    |
    */
    'minimum_payout_amount' => env('MINIMUM_PAYOUT_AMOUNT', 500.00),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | The default currency code used throughout the system.
    |
    */
    'currency' => env('FINANCIAL_CURRENCY', 'PHP'),

    /*
    |--------------------------------------------------------------------------
    | Currency Symbol
    |--------------------------------------------------------------------------
    |
    | The symbol used for displaying currency amounts.
    |
    */
    'currency_symbol' => env('CURRENCY_SYMBOL', '₱'),

    /*
    |--------------------------------------------------------------------------
    | Payment Settings
    |--------------------------------------------------------------------------
    |
    | Settings related to client payments and payment processing.
    |
    */
    'payments' => [
        // Minimum project amount for milestone payment option
        'milestone_minimum_amount' => env('MILESTONE_MINIMUM_AMOUNT', 10000.00),
        
        // Default downpayment percentage
        'default_downpayment_percentage' => env('DEFAULT_DOWNPAYMENT_PERCENTAGE', 50),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payout Settings
    |--------------------------------------------------------------------------
    |
    | Settings related to adiutor payouts and withdrawals.
    |
    */
    'payouts' => [
        // Processing fee percentage (if applicable)
        'processing_fee_percentage' => env('PAYOUT_PROCESSING_FEE', 0),
        
        // Maximum single payout amount
        'maximum_payout_amount' => env('MAXIMUM_PAYOUT_AMOUNT', 50000.00),
    ],

    /*
    |--------------------------------------------------------------------------
    | Referral Settings
    |--------------------------------------------------------------------------
    |
    | Settings for the referral rewards system.
    |
    */
    'referral' => [
        // Bonus amount for successful referral
        'bonus_amount' => env('REFERRAL_BONUS_AMOUNT', 500.00),
    ],

];
