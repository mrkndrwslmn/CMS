# 🗓️ Google Calendar Integration Setup Guide

## Error 403: access_denied - SOLVED

If you're seeing **"PEMS has not completed the Google verification process"**, your Google Cloud Project is in **Testing mode** and you need to add test users.

---

## 🔧 Quick Fix (5 minutes)

### Step 1: Go to Google Cloud Console
1. Visit: https://console.cloud.google.com/
2. Select your project: **PEMS** (or whatever you named it)

### Step 2: Add Test Users
1. In the left sidebar, click **"APIs & Services"** → **"OAuth consent screen"**
2. Scroll down to **"Test users"** section
3. Click **"+ ADD USERS"** button
4. Add your Gmail address(es) that you want to test with:
   ```
   your-email@gmail.com
   ```
5. Click **"SAVE"**

### Step 3: Test the Connection
1. Go back to your PEMS app: http://127.0.0.1:8000/calendar
2. Click **"Connect Google Calendar"**
3. You should now be able to authorize the app! ✅

---

## 📋 Complete Setup Checklist

### ✅ Prerequisites (You Already Have These)
- [x] Google Cloud Project created
- [x] Google Calendar API enabled
- [x] OAuth 2.0 credentials created
- [x] Credentials in `.env` file:
  ```env
  GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
  GOOGLE_CLIENT_SECRET=your-client-secret
  GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/calendar/callback
  ```

### ⚠️ Missing Step (Add This Now)
- [ ] **Add test users to OAuth consent screen**
  - This is what's causing the Error 403
  - Google only allows approved testers when app is in Testing mode

---

## 🎯 OAuth Consent Screen Settings

### Publishing Status Options

#### Option 1: Testing Mode (Current - Recommended for Development)
- **Pros:**
  - No verification needed
  - Quick setup
  - Perfect for development
- **Cons:**
  - Maximum 100 test users
  - Must manually add each tester
  - Users see "unverified app" warning
- **Use when:** Developing and testing with known users

#### Option 2: Production Mode (For Public Launch)
- **Pros:**
  - Any Google user can connect
  - No test user limit
  - Professional appearance
- **Cons:**
  - Requires Google verification (can take weeks)
  - Need privacy policy URL
  - Need terms of service URL
  - Must justify scopes requested
- **Use when:** Ready for public release

### What You Need for Each Mode

#### Testing Mode (Current)
```
✅ Application name: PEMS
✅ User support email: your-email@gmail.com
✅ Developer contact: your-email@gmail.com
✅ Scopes: 
   - https://www.googleapis.com/auth/calendar.readonly
   - https://www.googleapis.com/auth/calendar.events
✅ Test users: your-email@gmail.com (ADD THIS!)
```

#### Production Mode (Future)
```
✅ Everything from Testing Mode
📝 App homepage: https://yourapp.com
📝 Privacy policy URL: https://yourapp.com/privacy
📝 Terms of service URL: https://yourapp.com/terms
📝 App logo (120x120px)
📝 Verification justification for each scope
⏳ Wait for Google review (1-6 weeks)
```

---

## 🔐 OAuth Scopes Explained

### What PEMS Requests:

```javascript
// In CalendarController.php
$scopes = [
    'https://www.googleapis.com/auth/calendar.readonly',  // Read calendar events
    'https://www.googleapis.com/auth/calendar.events'     // Create/update events
];
```

### Why These Scopes?

1. **`calendar.readonly`** - Fetch Google Calendar events to:
   - Show adiutor's existing meetings in schedule timeline
   - Detect scheduling conflicts
   - Display busy/free times

2. **`calendar.events`** - Create task events:
   - Add CMS tasks to Google Calendar
   - Sync task schedules automatically
   - Update event details when tasks change

---

## 🧪 Testing the Integration

### 1. Connect Calendar
```
Route: http://127.0.0.1:8000/calendar
Action: Click "Connect Google Calendar"
Expected: Google OAuth consent screen → Authorization → Redirect to /calendar/callback
Result: "Google Calendar connected successfully!"
```

### 2. Verify Connection
```
Route: http://127.0.0.1:8000/calendar
Check: Green "Connected" badge with calendar email
Action: Click "Test Connection"
Expected: Success message with sync time
```

### 3. View Schedule Timeline
```
Route: http://127.0.0.1:8000/calendar
Feature: Weekly timeline view
Expected: 
  - Blue blocks = CMS scheduled tasks
  - Purple blocks = Google Calendar events
  - Conflicts highlighted
```

### 4. Schedule a Task
```
Route: http://127.0.0.1:8000/calendar
Action: Click "Schedule Task" on unscheduled task
Expected: 
  - Conflict detection modal if time conflicts
  - Suggestions for alternative times
  - Option to apply or choose manually
```

---

## 🐛 Troubleshooting

### Error: "Access blocked: PEMS has not completed verification"
**Solution:** Add your email as test user in OAuth consent screen

### Error: "Invalid redirect_uri"
**Check:** 
```env
# .env file
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/calendar/callback

# Google Cloud Console → Credentials → OAuth 2.0 Client
# Authorized redirect URIs must match exactly (including http/https)
```

### Error: "The app is in testing mode"
**Options:**
1. Add email as test user (recommended for dev)
2. Publish app (requires verification for production)

### Error: "Invalid client_id or client_secret"
**Check:**
```bash
# View current credentials
php artisan tinker
>>> config('services.google_calendar')
```

### Connection works but no events showing
**Check:**
```php
// Test in tinker
$service = app(App\Services\GoogleCalendarService::class);
$events = $service->getEvents(Auth::user(), now(), now()->addDays(7));
dd($events);
```

---

## 📝 Adding More Test Users

### Via Console (Manual)
1. Go to OAuth consent screen
2. Scroll to "Test users"
3. Click "+ ADD USERS"
4. Enter email addresses (one per line)
5. Save

### Maximum Limits
- **Testing mode:** 100 test users
- **Production mode:** Unlimited users (after verification)

### Who Should Be a Test User?
- Developers working on the app
- QA testers
- Internal team members
- Beta testers (if applicable)

---

## 🚀 Moving to Production

When you're ready to allow any Google user to connect:

### 1. Prepare Documentation
- Create privacy policy page
- Create terms of service page
- Prepare app logo (120x120px)

### 2. Submit for Verification
1. Go to OAuth consent screen
2. Click "PUBLISH APP"
3. Fill out verification questionnaire:
   - Why you need each scope
   - How you protect user data
   - Video demo of app using scopes
   - Privacy policy and ToS links

### 3. Wait for Review
- Google reviews in 1-6 weeks
- May request additional information
- Once approved, any user can connect

### 4. Update App Status
```php
// No code changes needed!
// Just change publishing status in Google Cloud Console
```

---

## 🔒 Security Best Practices

### Token Storage
```php
// Tokens encrypted in database
// Table: calendar_integrations
// Columns: access_token (encrypted), refresh_token (encrypted)
```

### Token Refresh
```php
// Automatically handled by GoogleCalendarService
// Refreshes when access_token expires (1 hour)
// Uses refresh_token to get new access_token
```

### Disconnect Flow
```php
// User can disconnect anytime
// Revokes access on Google side
// Clears tokens from database
```

---

## 📊 Integration Status Check

### Database Check
```sql
-- Check connected users
SELECT 
    u.firstName, 
    u.lastName, 
    ci.calendar_id, 
    ci.is_connected,
    ci.last_synced_at
FROM calendar_integrations ci
JOIN users u ON u.id = ci.adiutor_id
WHERE ci.is_connected = 1;
```

### Code Check
```php
// In tinker
$user = User::find(1);
$user->calendarIntegration; // Should return integration model
$user->calendarIntegration->is_connected; // Should be true
```

---

## 📞 Need Help?

### Common Issues
- **Error 403:** Add test user (see Step 2 above)
- **Invalid redirect:** Check .env matches Google Console
- **No events showing:** Check token refresh and API quota

### Resources
- Google Calendar API Docs: https://developers.google.com/calendar/api
- OAuth 2.0 Guide: https://developers.google.com/identity/protocols/oauth2
- PEMS Documentation: `/storage/documentations/`

---

**✅ After adding yourself as a test user, you should be able to connect Google Calendar successfully!**

*For production deployment, plan for Google verification 2-4 weeks before launch.*
