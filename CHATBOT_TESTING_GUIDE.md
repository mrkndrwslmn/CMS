# Testing Your Chatbot - Step by Step

## Prerequisites
Make sure you have:
- ✅ Added `GEMINI_API_KEY` to your `.env` file
- ✅ Cleared Laravel cache: `php artisan cache:clear`
- ✅ Restarted your development server

## Test 1: Visual Check ✅

### Steps:
1. Open your website: `http://localhost:8000`
2. Look at the bottom-right corner
3. You should see a **purple gradient circular button** with a chat icon

### Expected Result:
- Purple/blue gradient circle
- Chat bubble icon in white
- Subtle shadow effect
- Hover effect (scales slightly)

### If Not Visible:
- Open browser console (F12) and check for errors
- Verify `chatbot.js` is loaded in Network tab
- Clear browser cache (Ctrl+Shift+R or Cmd+Shift+R)

---

## Test 2: Opening the Chat Window ✅

### Steps:
1. Click the circular chat icon
2. Wait for the chat window to appear

### Expected Result:
- Chat window slides up from bottom-right
- Header shows "Treis Adiutor" with "Online • AI Assistant" status
- Welcome message appears automatically
- 4 suggestion buttons show up
- Input field is ready at the bottom

### If It Doesn't Open:
- Check browser console for JavaScript errors
- Verify CSRF token is present in page source (View > Source)
- Check that `public/js/chatbot.js` file exists

---

## Test 3: Initial Greeting ✅

### Expected Initial Message:
```
👋 Hello! I'm here to help you learn about Treis Adiutor's services. How can I assist you today?

[What services do you offer?]
[How can I get started?]
[What is the pricing?]
[How does the project process work?]
```

### Check:
- ✅ Message appears in white bubble on left side
- ✅ Timestamp shows at bottom of message
- ✅ 4 suggestion buttons are clickable
- ✅ Messages have smooth animation

---

## Test 4: Sending Messages ✅

### Test Message 1: "What services do you offer?"

#### Steps:
1. Type message in input field OR click suggestion button
2. Press Enter or click Send button (paper plane icon)

#### Expected Behavior:
- Your message appears on right side in purple gradient bubble
- Typing indicator shows (three bouncing dots)
- Bot response appears on left side after 2-5 seconds
- Response mentions services like web development, mobile development, etc.
- New message scrolls into view

### Test Message 2: "How can I get started?"

#### Expected Response Should Include:
- Mention of service request form
- Reference to /get-started page
- Explanation of the process
- Professional and friendly tone

### Test Message 3: Custom Question

Try: "What technologies do you work with?"

#### Expected:
- Bot mentions Laravel, PHP, JavaScript, Vue.js, etc.
- Based on info in `storage/app/chatbot/project-information.md`

---

## Test 5: Conversation History ✅

### Steps:
1. Send 3-4 messages
2. Close the chat (click X button)
3. Wait 5 seconds
4. Open chat again (click icon)

### Expected Result:
- All previous messages are still visible
- Conversation continues from where you left off
- Timestamps are preserved

---

## Test 6: Cache Expiration ✅

### Steps:
1. Open browser DevTools (F12)
2. Go to Application > Local Storage > your domain
3. Find key: `treisadiutor_chatbot_cache`
4. Check the timestamp value

### Expected:
- Cache exists with messages and timestamp
- Timestamp is recent (current time)

### To Test Expiration (Optional):
1. In DevTools, modify the timestamp to 2 hours ago
2. Refresh the page
3. Open chat
4. Cache should be cleared and greeting should show again

---

## Test 7: Mobile Responsiveness 📱

### Steps:
1. Open DevTools (F12)
2. Click device toolbar icon (or Ctrl+Shift+M)
3. Select iPhone or Android device
4. Test the chatbot

### Expected:
- Chat icon appears in same position
- Chat window fits screen width
- Messages are readable
- Input is accessible
- No horizontal scrolling

---

## Test 8: Error Handling ✅

### Test Invalid API Key:

#### Steps:
1. In `.env`, change `GEMINI_API_KEY` to `invalid_key_123`
2. Restart server: `php artisan serve`
3. Try sending a message

#### Expected:
- Error message appears: "Sorry, I encountered an error..."
- Message is in bot's style (left side, white bubble)
- Chat doesn't crash

### Restore:
1. Put correct API key back in `.env`
2. Restart server

---

## Test 9: Quick Suggestions ✅

### Steps:
1. Click each suggestion button in the initial greeting
2. Verify each sends the message automatically

### Expected:
- Button text becomes your message
- Bot responds appropriately to each suggestion
- New suggestions may appear in responses

---

## Test 10: Multiple Conversations ✅

### Steps:
1. Open chat in one browser window
2. Send some messages
3. Open same site in incognito/private window
4. Open chat

### Expected:
- Each window has independent conversation
- Cache is separate per browser session
- Both work simultaneously

---

## Common Issues & Solutions

### Issue: Chat icon not showing
**Solution:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Rebuild assets
npm run build
```

### Issue: "Failed to get response"
**Solution:**
1. Check `.env` has valid `GEMINI_API_KEY`
2. Test API key at https://makersuite.google.com
3. Check Laravel logs: `tail -f storage/logs/laravel.log`
4. Verify internet connection

### Issue: Messages not caching
**Solution:**
1. Check browser allows localStorage
2. Clear browser cache completely
3. Check for JavaScript errors in console
4. Try different browser

### Issue: Styling broken
**Solution:**
```bash
# Rebuild Tailwind CSS
npm run build

# Clear browser cache
Ctrl+Shift+R (Windows/Linux)
Cmd+Shift+R (Mac)
```

---

## Performance Tests

### Check Response Time:
1. Open Network tab in DevTools
2. Send a message
3. Find `POST /api/chatbot/chat` request
4. Check response time

**Expected:** 2-8 seconds depending on:
- Message complexity
- API response time
- Internet speed

### Check Cache Performance:
1. Send a message (first time - hits API)
2. Close and reopen chat
3. Send same message (uses cache)

**Expected:**
- First time: 2-8 seconds
- Cached: Instant (< 100ms)

---

## API Monitoring

### Check API Usage:
1. Visit https://makersuite.google.com/app/apikey
2. Click on your API key
3. View usage statistics

### Free Tier Limits:
- 60 requests per minute
- Good for development and testing
- Upgrade available for production

---

## Success Criteria ✅

Your chatbot is working correctly if:
- ✅ Icon appears and is clickable
- ✅ Chat window opens smoothly
- ✅ Initial greeting loads automatically
- ✅ Can send and receive messages
- ✅ Responses are relevant and helpful
- ✅ Conversations are cached for 1 hour
- ✅ Works on mobile devices
- ✅ No console errors
- ✅ Styling looks professional
- ✅ Typing indicators work

---

## Next Steps

Once all tests pass:
1. ✅ Update `storage/app/chatbot/project-information.md` with accurate info
2. ✅ Customize colors if needed
3. ✅ Add to other layouts (admin, client) if desired
4. ✅ Monitor API usage
5. ✅ Deploy to production

---

## Production Checklist

Before deploying:
- [ ] Valid production `GEMINI_API_KEY` in `.env`
- [ ] Updated project information file
- [ ] Tested on multiple devices
- [ ] Checked API rate limits
- [ ] Reviewed chatbot responses
- [ ] SSL certificate active (HTTPS required)
- [ ] Cache cleared on server
- [ ] Assets compiled: `npm run build`

---

**Happy Testing! 🎉**

If you encounter any issues not covered here, check:
- `CHATBOT_DOCUMENTATION.md` for detailed info
- Laravel logs: `storage/logs/laravel.log`
- Browser console for JavaScript errors
