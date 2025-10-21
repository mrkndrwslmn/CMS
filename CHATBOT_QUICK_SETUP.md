# Quick Setup Guide - Chatbot Integration

## 🚀 Quick Start (5 Minutes)

### Step 1: Get Your Gemini API Key
1. Go to https://makersuite.google.com/app/apikey
2. Sign in with Google
3. Click "Create API Key"
4. Copy the key

### Step 2: Add API Key to .env
Open your `.env` file and add:
```bash
GEMINI_API_KEY=paste_your_api_key_here
GEMINI_MODEL=gemini-1.5-flash
```

### Step 3: Test It!
1. Open your website in a browser
2. Look for the purple chat icon in the bottom-right corner
3. Click it and try asking: "What services do you offer?"

That's it! Your chatbot is now live! 🎉

## 📝 What Was Installed?

### New Files Created:
- ✅ `app/Http/Controllers/ChatbotController.php` - Backend logic
- ✅ `public/js/chatbot.js` - Frontend widget
- ✅ `storage/app/chatbot/project-information.md` - Company info for AI

### Files Modified:
- ✅ `config/services.php` - Added Gemini config
- ✅ `routes/web.php` - Added chatbot routes
- ✅ `resources/views/layouts/public.blade.php` - Added chatbot widget
- ✅ `.env.example` - Added API key template

## 🎨 Features

✅ **Floating Chat Icon** - Purple gradient button bottom-right  
✅ **Smart Caching** - Conversations saved for 1 hour  
✅ **AI Responses** - Powered by Google Gemini  
✅ **Mobile Friendly** - Works on all devices  
✅ **Typing Indicators** - Shows when bot is thinking  
✅ **Quick Suggestions** - Helps users get started  

## ⚙️ Configuration Options

### Change Cache Duration
Edit `public/js/chatbot.js` line 11:
```javascript
this.cacheExpiry = 60 * 60 * 1000; // 1 hour (in milliseconds)
```

### Update Company Information
Edit `storage/app/chatbot/project-information.md` with your latest:
- Services
- Pricing
- Contact details
- Process information

### Change Colors
Edit `public/js/chatbot.js` - search and replace:
- `primary-600` with your color
- `primary-700` with your darker shade

## 🔧 Troubleshooting

### Chatbot Not Showing?
1. Clear your browser cache (Ctrl+Shift+R)
2. Check browser console for errors (F12)
3. Verify chatbot.js is loading

### No AI Responses?
1. Check `.env` has correct `GEMINI_API_KEY`
2. Verify API key is active at https://makersuite.google.com
3. Check Laravel logs: `storage/logs/laravel.log`

### Cache Not Working?
1. Enable localStorage in your browser
2. Clear browser cache
3. Check for JavaScript errors in console

## 📱 Where Does It Appear?

The chatbot automatically appears on all pages that use the `public.blade.php` layout:
- ✅ Homepage
- ✅ Services page
- ✅ About page
- ✅ Contact/Get Started page
- ✅ All other public pages

## 🎯 Test Questions

Try asking the chatbot:
- "What services do you offer?"
- "How can I get started?"
- "What is your pricing?"
- "How does the payment process work?"
- "What technologies do you work with?"
- "How long does a project take?"

## 📊 API Usage

Your Gemini API key has a free tier:
- **Free Tier**: 60 requests per minute
- **Good for**: Small to medium websites
- **Upgrade**: Available if you need more

Monitor usage at: https://makersuite.google.com/app/apikey

## 🔐 Security Notes

✅ API key is stored server-side only  
✅ CSRF protection enabled  
✅ Input validation (max 1000 chars)  
✅ Gemini safety filters active  
✅ No sensitive data in cache  

## 🎨 Customization Ideas

### Add to Admin Panel
Copy the chatbot script to `resources/views/layouts/admin.blade.php`:
```html
<script src="{{ asset('js/chatbot.js') }}"></script>
```

### Change Position
Edit `public/js/chatbot.js` line 28:
```javascript
// From: bottom-6 right-6
// To: bottom-6 left-6 (left side)
```

### Disable on Mobile
Add to chatbot.js initialization:
```javascript
if (window.innerWidth < 768) return; // Skip on mobile
```

## 📚 Full Documentation

For detailed information, see: `CHATBOT_DOCUMENTATION.md`

## ✅ Deployment Checklist

Before going live:
- [ ] Added `GEMINI_API_KEY` to production `.env`
- [ ] Updated `project-information.md` with current info
- [ ] Tested on mobile and desktop
- [ ] Verified cache expiration works
- [ ] Checked API rate limits
- [ ] Tested all suggested questions
- [ ] Cleared production cache: `php artisan cache:clear`

## 🆘 Need Help?

1. Check `CHATBOT_DOCUMENTATION.md` for detailed info
2. Review Laravel logs: `storage/logs/laravel.log`
3. Test API key at: https://makersuite.google.com
4. Check browser console for JavaScript errors

---

**Enjoy your new AI-powered chatbot! 🤖**
