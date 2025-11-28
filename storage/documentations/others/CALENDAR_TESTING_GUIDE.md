# Quick Testing Guide - Calendar Integration

## Prerequisites ✅

Make sure you have:
- [x] MySQL tables created (adiutor_calendar_integrations, task_schedules, etc.)
- [x] Google Cloud Console project with OAuth 2.0 credentials
- [x] `.env` file configured with Google Calendar credentials
- [x] `google/apiclient` package installed
- [x] Laravel server running (`php artisan serve`)

## Environment Variables Check

Open `.env` and verify these exist:
```env
GOOGLE_CALENDAR_CLIENT_ID=your-client-id-here.apps.googleusercontent.com
GOOGLE_CALENDAR_CLIENT_SECRET=your-client-secret-here
GOOGLE_CALENDAR_REDIRECT_URI=http://localhost:8000/calendar/callback
```

## Testing Steps

### Step 1: Login as Adiutor
```
URL: http://localhost:8000/login
Credentials: Use an existing adiutor account
```

### Step 2: Navigate to Calendar Page
```
URL: http://localhost:8000/calendar
OR: Look for "Calendar" link in adiutor dashboard navigation
```

**Expected Result:**
- See "Not Connected" status badge
- See benefits list and privacy note
- See "Connect Google Calendar" button
- See "How It Works" 3-step guide

### Step 3: Connect Calendar
1. Click **"Connect Google Calendar"** button
2. Browser redirects to Google OAuth consent screen
3. Select your Google account
4. Click **"Allow"** to grant permissions
5. Browser redirects back to `/calendar/callback`
6. Should redirect to `/calendar` with success message

**Expected Result:**
- Success message: "Google Calendar connected successfully! Your schedule is now synced."
- Status changes to "Connected" (green badge)
- See Calendar ID and Last Synced time
- See "Test Connection" and "Disconnect" buttons

### Step 4: Test Connection
1. Click **"Test Connection"** button
2. Wait for AJAX request to complete

**Expected Result:**
- Toast notification appears at bottom-right
- Success: "Connection Successful! Found X events this week."
- OR Error: "Connection Failed" with error message

### Step 5: Disconnect Calendar (Optional)
1. Click **"Disconnect"** button
2. Confirm in dialog: "Are you sure...?"
3. Click **OK**

**Expected Result:**
- Success message: "Google Calendar disconnected successfully."
- Status changes to "Not Connected"
- Back to initial state

## Common Issues & Solutions

### Issue 1: "Redirect URI Mismatch" Error
**Symptom:** OAuth error after clicking Connect
**Solution:** 
- Go to Google Cloud Console → Credentials
- Edit OAuth 2.0 Client ID
- Add `http://localhost:8000/calendar/callback` to Authorized Redirect URIs
- Must match exactly (no trailing slash)

### Issue 2: "404 Not Found" on /calendar
**Symptom:** Page not found when visiting calendar URL
**Solution:**
- Run `php artisan route:clear`
- Check routes are registered: `php artisan route:list | findstr calendar`
- Should see: calendar.index, calendar.connect, calendar.callback, calendar.disconnect, calendar.test

### Issue 3: "Class 'Google\Client' not found"
**Symptom:** Fatal error about Google Client class
**Solution:**
- Run `composer require google/apiclient --prefer-source`
- Run `composer dump-autoload`

### Issue 4: "Only adiutors can connect calendars"
**Symptom:** Error message when trying to connect
**Solution:**
- Verify user role is 'adiutor' in database: `SELECT id, fullName, role FROM users WHERE id = YOUR_ID;`
- If role is wrong, update: `UPDATE users SET role = 'adiutor' WHERE id = YOUR_ID;`

### Issue 5: Connection works but Test fails
**Symptom:** Connected successfully but test shows error
**Solution:**
- Check Laravel logs: `storage/logs/laravel.log`
- Common cause: Token expired (should auto-refresh)
- Try disconnecting and reconnecting
- Verify Google Calendar API is enabled in Cloud Console

## Database Verification

Check connection in MySQLyog:

```sql
-- View all calendar integrations
SELECT * FROM adiutor_calendar_integrations;

-- Check specific adiutor
SELECT 
    id,
    adiutor_id,
    provider,
    is_connected,
    last_synced_at,
    token_expires_at
FROM adiutor_calendar_integrations 
WHERE adiutor_id = YOUR_ADIUTOR_ID;

-- Verify tokens are encrypted (should see garbled text)
SELECT access_token FROM adiutor_calendar_integrations WHERE id = 1;
```

**Expected:**
- `is_connected` should be `1` after successful connection
- `access_token` should be encrypted (long encrypted string)
- `token_expires_at` should be ~1 hour in future
- `last_synced_at` should be current timestamp

## What to Test Next

Once calendar connection works:

1. **Create TaskSchedule record** (manual for now):
```sql
INSERT INTO task_schedules (
    task_id, 
    adiutor_id, 
    scheduled_start, 
    scheduled_end, 
    estimated_duration_minutes,
    schedule_type,
    created_at,
    updated_at
) VALUES (
    1,  -- Replace with valid taskID
    YOUR_ADIUTOR_ID,
    '2025-11-15 10:00:00',
    '2025-11-15 12:00:00',
    120,
    'manual',
    NOW(),
    NOW()
);
```

2. **Manually test GoogleCalendarService** (via Tinker):
```php
php artisan tinker

$user = App\Models\User::find(YOUR_ADIUTOR_ID);
$service = new App\Services\GoogleCalendarService();

// Test getting events
$events = $service->getEvents($user, now()->startOfWeek(), now()->endOfWeek());
dd($events);

// Test creating event
$taskData = [
    'title' => 'Test Task from CMS',
    'description' => 'Testing calendar integration',
    'project' => 'Test Project',
    'priority' => 'high',
    'start' => now()->addHours(2),
    'end' => now()->addHours(4),
    'url' => 'http://localhost:8000/adiutor/tasks/1',
];

$eventId = $service->createTaskEvent($user, $taskData);
echo "Event created: $eventId\n";
```

3. **Check your actual Google Calendar**:
- Go to https://calendar.google.com
- Should see "[CMS] Test Task from CMS" event
- Event should be colored red (high priority)
- Description should have project details

## Success Criteria ✅

Your implementation is working if:
- ✅ Adiutors can connect Google Calendar via OAuth
- ✅ Connection status displays correctly
- ✅ Test connection shows event count
- ✅ Tokens are encrypted in database
- ✅ Can disconnect calendar
- ✅ GoogleCalendarService can fetch events
- ✅ GoogleCalendarService can create events
- ✅ Events appear in actual Google Calendar

## Ready for Next Phase! 🚀

Once all tests pass, you're ready to build:
1. ConflictDetector service
2. TaskSchedule model & controller
3. Visual schedule timeline
4. Drag-and-drop scheduler

---

**Need Help?**
Check the logs:
- Laravel: `storage/logs/laravel.log`
- Browser Console: F12 → Console tab (for JavaScript errors)
- Network Tab: F12 → Network tab (for AJAX requests)
