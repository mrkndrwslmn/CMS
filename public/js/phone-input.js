/**
 * Phone Number Input Validation and Formatting
 * 
 * This script provides consistent phone number validation and formatting
 * across all forms in the application.
 * 
 * Usage:
 * 1. Add class="phone-input" to any phone input field
 * 2. Optionally add data-format="ph" for Philippine format (+63 XXX XXX XXXX)
 * 3. Optionally add data-format="international" for general international format
 * 
 * Example:
 * <input type="tel" name="phone" class="phone-input" data-format="ph">
 */

(function() {
    'use strict';

    // Phone validation patterns (must match App\Rules\PhoneNumber patterns)
    const PHONE_PATTERNS = {
        // Strict pattern for required fields
        strict: /^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,9}$/,
        // Simple pattern for optional fields
        simple: /^[\d\s\+\-\(\)\.]{7,25}$/,
        // Only allow valid phone characters
        allowedChars: /[^\d\s\+\-\(\)\.]/g
    };

    /**
     * Format phone number for Philippine format (+63 XXX XXX XXXX)
     * @param {string} value - Raw phone value
     * @returns {string} - Formatted phone number
     */
    function formatPhilippinePhone(value) {
        // Remove everything except digits and +
        let cleaned = value.replace(/[^\d+]/g, '');
        
        // Ensure it starts with +63 for Philippine numbers
        if (cleaned.startsWith('63') && !cleaned.startsWith('+63')) {
            cleaned = '+' + cleaned;
        } else if (cleaned.startsWith('0')) {
            // Convert 09XX to +63 9XX
            cleaned = '+63' + cleaned.substring(1);
        } else if (!cleaned.startsWith('+63') && !cleaned.startsWith('+')) {
            // If it's just digits, assume Philippine and add +63
            if (cleaned.length > 0 && !cleaned.startsWith('63')) {
                cleaned = '+63' + cleaned;
            }
        }
        
        // Extract the number after +63
        const raw = cleaned.replace('+63', '');
        
        // Format as +63 XXX XXX XXXX
        const part1 = raw.substring(0, 3);
        const part2 = raw.substring(3, 6);
        const part3 = raw.substring(6, 10);
        
        let formatted = '+63';
        if (part1) formatted += ' ' + part1;
        if (part2) formatted += ' ' + part2;
        if (part3) formatted += ' ' + part3;
        
        return formatted;
    }

    /**
     * Clean phone number input - remove invalid characters
     * @param {string} value - Raw input value
     * @returns {string} - Cleaned phone number
     */
    function cleanPhoneInput(value) {
        return value.replace(PHONE_PATTERNS.allowedChars, '');
    }

    /**
     * Validate phone number
     * @param {string} value - Phone number to validate
     * @param {boolean} strict - Use strict pattern
     * @returns {boolean} - Whether the phone number is valid
     */
    function validatePhone(value, strict = false) {
        if (!value || value.trim() === '') {
            return true; // Empty is valid (let 'required' handle that)
        }
        
        const pattern = strict ? PHONE_PATTERNS.strict : PHONE_PATTERNS.simple;
        return pattern.test(value);
    }

    /**
     * Initialize phone input handling
     * @param {HTMLInputElement} input - The input element
     */
    function initPhoneInput(input) {
        const format = input.dataset.format || 'clean';
        const isRequired = input.hasAttribute('required');
        
        // Handle input event - clean/format as user types
        input.addEventListener('input', function(e) {
            const cursorPos = this.selectionStart;
            const oldLength = this.value.length;
            
            if (format === 'ph') {
                this.value = formatPhilippinePhone(this.value);
            } else {
                this.value = cleanPhoneInput(this.value);
            }
            
            // Try to maintain cursor position
            const newLength = this.value.length;
            const posDiff = newLength - oldLength;
            const newPos = Math.max(0, cursorPos + posDiff);
            this.setSelectionRange(newPos, newPos);
        });
        
        // Handle paste event
        input.addEventListener('paste', function(e) {
            // Let the paste happen, then clean on next tick
            setTimeout(() => {
                if (format === 'ph') {
                    this.value = formatPhilippinePhone(this.value);
                } else {
                    this.value = cleanPhoneInput(this.value);
                }
            }, 0);
        });
        
        // Handle blur event - validate
        input.addEventListener('blur', function() {
            const value = this.value.trim();
            
            // Skip validation if empty and not required
            if (!value && !isRequired) {
                this.classList.remove('phone-invalid');
                return;
            }
            
            // Validate
            if (!validatePhone(value, isRequired)) {
                this.classList.add('phone-invalid');
                
                // Add error message if not exists
                let errorEl = this.parentElement.querySelector('.phone-error-message');
                if (!errorEl) {
                    errorEl = document.createElement('p');
                    errorEl.className = 'phone-error-message text-red-600 text-xs mt-1';
                    errorEl.textContent = 'Please enter a valid phone number. Use only numbers, +, -, (), spaces, or dots.';
                    this.parentElement.appendChild(errorEl);
                }
                errorEl.style.display = 'block';
            } else {
                this.classList.remove('phone-invalid');
                
                // Hide error message
                const errorEl = this.parentElement.querySelector('.phone-error-message');
                if (errorEl) {
                    errorEl.style.display = 'none';
                }
            }
        });
        
        // Handle focus - remove error styling
        input.addEventListener('focus', function() {
            this.classList.remove('phone-invalid');
        });
        
        // Mark as initialized
        input.dataset.phoneInitialized = 'true';
    }

    /**
     * Initialize all phone inputs on the page
     */
    function initAllPhoneInputs() {
        const phoneInputs = document.querySelectorAll('.phone-input:not([data-phone-initialized="true"]), input[type="tel"]:not([data-phone-initialized="true"])');
        phoneInputs.forEach(initPhoneInput);
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllPhoneInputs);
    } else {
        initAllPhoneInputs();
    }

    // Re-initialize when new content is added (for dynamic forms)
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                initAllPhoneInputs();
            }
        });
    });
    
    observer.observe(document.body, { childList: true, subtree: true });

    // Expose functions globally for manual use
    window.PhoneInput = {
        init: initPhoneInput,
        initAll: initAllPhoneInputs,
        format: {
            philippine: formatPhilippinePhone,
            clean: cleanPhoneInput
        },
        validate: validatePhone
    };
})();
