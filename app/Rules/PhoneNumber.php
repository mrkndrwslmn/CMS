<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Phone Number Validation Rule
 * 
 * Validates phone numbers for Philippine format and international formats.
 * Allows: +, (), digits 0-9, spaces, dots, hyphens
 * Blocks: letters and other special characters
 * 
 * Valid examples:
 * - +63 917 123 4567 (Philippine mobile)
 * - 09171234567 (Philippine local format)
 * - +1-555-123-4567 (US format)
 * - (02) 8123-4567 (Philippine landline)
 */
class PhoneNumber implements ValidationRule
{
    /**
     * The regex pattern for validating phone numbers.
     * 
     * Pattern breakdown:
     * - ^[\+]? - Optional + at the start (for international codes)
     * - [(]?[0-9]{1,4}[)]? - Optional country code in parentheses (1-4 digits)
     * - [-\s\.]? - Optional separator (dash, space, or dot)
     * - [0-9]{1,4} - Area code or first part (1-4 digits)
     * - [-\s\.]? - Optional separator
     * - [0-9]{1,4} - Middle part (1-4 digits)
     * - [-\s\.]? - Optional separator
     * - [0-9]{1,9}$ - Last part (1-9 digits)
     * 
     * Total length: 7-25 characters
     */
    public const PATTERN = '/^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,9}$/';
    
    /**
     * Simple pattern for basic validation (allows any phone-like characters).
     * Use this for nullable/optional phone fields with less strict requirements.
     */
    public const SIMPLE_PATTERN = '/^[\d\s\+\-\(\)\.]{7,25}$/';

    /**
     * Whether to use simple pattern instead of strict pattern.
     */
    protected bool $useSimplePattern;

    /**
     * Create a new rule instance.
     *
     * @param bool $simple Use simple pattern (less strict) for optional fields
     */
    public function __construct(bool $simple = false)
    {
        $this->useSimplePattern = $simple;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Skip validation if value is empty (let 'required' rule handle that)
        if (empty($value)) {
            return;
        }

        // Remove all whitespace for length check
        $cleanedValue = preg_replace('/\s+/', '', $value);
        
        // Check minimum length (at least 7 digits for a valid phone)
        if (strlen($cleanedValue) < 7) {
            $fail('The :attribute must be at least 7 characters.');
            return;
        }

        // Check maximum length
        if (strlen($cleanedValue) > 25) {
            $fail('The :attribute must not exceed 25 characters.');
            return;
        }

        $pattern = $this->useSimplePattern ? self::SIMPLE_PATTERN : self::PATTERN;
        
        if (!preg_match($pattern, $value)) {
            $fail('The :attribute must be a valid phone number. Use only numbers, +, -, (), spaces, or dots.');
        }
    }

    /**
     * Get the validation regex pattern for use in JavaScript.
     */
    public static function getJsPattern(): string
    {
        // Return pattern without PHP delimiters for JS use
        return '^[\\+]?[(]?[0-9]{1,4}[)]?[-\\s\\.]?[0-9]{1,4}[-\\s\\.]?[0-9]{1,4}[-\\s\\.]?[0-9]{1,9}$';
    }

    /**
     * Get simple pattern for JavaScript.
     */
    public static function getSimpleJsPattern(): string
    {
        return '^[\\d\\s\\+\\-\\(\\)\\.]{7,25}$';
    }
}
