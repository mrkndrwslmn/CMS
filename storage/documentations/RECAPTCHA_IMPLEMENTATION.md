# reCAPTCHA Integration Guide

## Overview
This document outlines the reCAPTCHA integration implemented for the get-started form to prevent spam and automated submissions.

## Implementation Details

### 1. Architecture
- **Custom Service**: `App\Services\RecaptchaService` - Handles all reCAPTCHA operations
- **Validation Rule**: `App\Rules\RecaptchaValidation` - Laravel validation rule for server-side verification
- **Configuration**: `config/recaptcha.php` - Centralized configuration file
- **Environment Variables**: Secure storage of API keys and settings

### 2. Components

#### RecaptchaService (`app/Services/RecaptchaService.php`)
- **Purpose**: Core reCAPTCHA functionality and Google API integration
- **Key Methods**:
  - `verify()` - Verify reCAPTCHA response with Google API
  - `getHtml()` - Generate reCAPTCHA widget HTML
  - `getSiteKey()` - Get site key for frontend
  - `isEnabled()` - Check if reCAPTCHA is enabled
  - `getScriptUrl()` - Get Google reCAPTCHA script URL

#### RecaptchaValidation (`app/Rules/RecaptchaValidation.php`)
- **Purpose**: Laravel validation rule for form submissions
- **Features**:
  - Integrates with RecaptchaService
  - Handles IP whitelisting
  - Provides user-friendly error messages

#### Configuration (`config/recaptcha.php`)
- **Purpose**: Centralized configuration management
- **Settings**:
  - Enable/disable reCAPTCHA
  - API keys configuration
  - Version selection (v2/v3)
  - Theme and size options
  - Score thresholds for v3
  - Error handling preferences

### 3. Environment Variables

Add these to your `.env` file:

```env
# reCAPTCHA Configuration
RECAPTCHA_ENABLED=true
RECAPTCHA_SITE_KEY=your_recaptcha_site_key_here
RECAPTCHA_SECRET_KEY=your_recaptcha_secret_key_here
RECAPTCHA_VERSION=v2
RECAPTCHA_THEME=light
RECAPTCHA_SIZE=normal
RECAPTCHA_MIN_SCORE=0.5
RECAPTCHA_FAIL_OPEN=false
```

### 4. Getting reCAPTCHA Keys

1. **Visit Google reCAPTCHA Console**: https://www.google.com/recaptcha/admin
2. **Create New Site**:
   - Label: Your site name
   - reCAPTCHA type: Choose v2 ("I'm not a robot") or v3 (Score based)
   - Domains: Add your domain(s) (localhost for development)
3. **Copy Keys**:
   - Site Key: Use for frontend (public)
   - Secret Key: Use for backend verification (keep private)

### 5. Frontend Integration

#### Script Loading
The reCAPTCHA script is automatically loaded in the form template:
```blade
@if(app(\App\Services\RecaptchaService::class)->isEnabled())
    <script src="{{ app(\App\Services\RecaptchaService::class)->getScriptUrl() }}" async defer></script>
@endif
```

#### Widget Display
The widget is automatically rendered:
```blade
{!! app(\App\Services\RecaptchaService::class)->getHtml() !!}
```

### 6. Backend Integration

#### Controller Validation
The `PublicServiceRequestController` includes reCAPTCHA validation:
```php
'g-recaptcha-response' => ['required', new RecaptchaValidation()],
```

#### Service Registration
The service is registered in `AppServiceProvider`:
```php
$this->app->singleton(RecaptchaService::class, function ($app) {
    return new RecaptchaService();
});
```

### 7. Version Differences

#### reCAPTCHA v2
- **User Experience**: Shows "I'm not a robot" checkbox
- **Implementation**: Simple widget with user interaction
- **Security**: Basic bot detection
- **Recommended For**: General forms, registration

#### reCAPTCHA v3
- **User Experience**: Invisible to users
- **Implementation**: Analyzes user behavior and returns score (0.0-1.0)
- **Security**: Advanced behavioral analysis
- **Recommended For**: Login forms, sensitive actions

### 8. Security Features

#### IP Whitelisting
Configure trusted IPs that skip reCAPTCHA:
```php
'skip_ips' => [
    '127.0.0.1',  // Localhost
    '::1',        // IPv6 localhost
],
```

#### Fail-Safe Behavior
- **Fail Closed** (Default): Block requests if reCAPTCHA service fails
- **Fail Open**: Allow requests if service unavailable (less secure)

#### Score Validation (v3)
Minimum score threshold prevents low-confidence submissions:
```php
'min_score' => 0.5, // Adjust based on your security needs
```

### 9. Error Handling

#### Common Issues and Solutions

1. **"Site key not found"**
   - Check RECAPTCHA_SITE_KEY in .env
   - Verify domain is registered in Google Console

2. **"Secret key missing"**
   - Check RECAPTCHA_SECRET_KEY in .env
   - Ensure key matches the site configuration

3. **"Invalid domain"**
   - Add current domain to Google reCAPTCHA console
   - For localhost, add localhost and 127.0.0.1

4. **"Connection timeout"**
   - Check internet connectivity
   - Increase timeout in RecaptchaService
   - Consider enabling fail_open for development

#### Logging
All reCAPTCHA attempts are logged for monitoring:
- Verification successes/failures
- API response details
- Error conditions
- Score information (v3)

### 10. Testing

#### Development Testing
1. **Enable Development Mode**:
   ```env
   RECAPTCHA_ENABLED=false
   ```
   This bypasses reCAPTCHA during development.

2. **Test Keys**: Google provides test keys that always pass:
   - Site Key: `6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI`
   - Secret Key: `6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe`

#### Production Testing
1. Verify form submission works with valid reCAPTCHA
2. Test that form blocks without reCAPTCHA completion
3. Monitor logs for verification failures
4. Test different browsers and devices

### 11. Performance Considerations

#### Loading Optimization
- Scripts loaded asynchronously (`async defer`)
- Widget only rendered when enabled
- Minimal impact on page load time

#### Caching
- Configuration values cached by Laravel
- API responses can be cached for repeat submissions
- Consider implementing rate limiting

### 12. Compliance and Privacy

#### Data Protection
- reCAPTCHA processes user data (IP, behavior)
- Update privacy policy to include reCAPTCHA usage
- Consider GDPR compliance for EU users

#### Accessibility
- reCAPTCHA v2 includes audio challenges
- v3 is more accessible (no user interaction)
- Consider providing alternative contact methods

### 13. Monitoring and Maintenance

#### Regular Checks
- Monitor verification success rates
- Check for increased spam attempts
- Review error logs regularly
- Update keys if compromised

#### Analytics
- Track form completion rates
- Monitor false positive/negative rates
- Adjust score thresholds based on data

### 14. Troubleshooting Commands

```bash
# Clear configuration cache
php artisan config:clear

# Test reCAPTCHA service
php artisan tinker
app(\App\Services\RecaptchaService::class)->isEnabled()

# Check logs
tail -f storage/logs/laravel.log | grep -i recaptcha
```

### 15. Future Enhancements

#### Potential Improvements
- Multiple form support
- A/B testing for different versions
- Custom challenge types
- Integration with rate limiting
- Advanced analytics and reporting

#### Configuration Expansion
- Per-form settings
- Dynamic threshold adjustment
- Geolocation-based rules
- Time-based validation

## Support and Resources

### Documentation
- **Google reCAPTCHA**: https://developers.google.com/recaptcha
- **Laravel Validation**: https://laravel.com/docs/validation
- **HTTP Client**: https://laravel.com/docs/http-client

### Configuration Files
- `config/recaptcha.php` - Main configuration
- `app/Services/RecaptchaService.php` - Core service
- `app/Rules/RecaptchaValidation.php` - Validation rule
- `.env` - Environment variables