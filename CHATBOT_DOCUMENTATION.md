# Chatbot Integration Documentation

## Overview
The Treis Adiutor CMS now includes an AI-powered chatbot that helps clients and visitors get instant answers about our services, processes, and how to get started. The chatbot is powered by Google's Gemini API and features intelligent caching and a beautiful user interface.

## Features

### 🤖 AI-Powered Responses
- Powered by Google Gemini 1.5 Flash model
- Context-aware conversations
- Knowledge about Treis Adiutor services and processes
- Professional and friendly tone

### 💬 User Interface
- **Floating Icon**: Eye-catching gradient button in the bottom-right corner
- **Expandable Chat Window**: Clean, modern interface with smooth animations
- **Message History**: Displays full conversation history
- **Typing Indicators**: Shows when the bot is processing
- **Quick Suggestions**: Provides suggested questions to help users get started
- **Responsive Design**: Works on desktop and mobile devices

### 💾 Smart Caching
- **Local Storage**: Messages are cached in the browser's localStorage
- **1-Hour Expiration**: Cache automatically expires after 1 hour
- **Conversation Persistence**: Users can close and reopen the chat without losing history
- **Automatic Cleanup**: Expired cache is automatically removed

### 🎨 Design Features
- Gradient backgrounds matching your brand colors
- Smooth animations and transitions
- Mobile-responsive layout
- Professional message bubbles
- Timestamp display
- Visual typing indicators

## Files Created/Modified

### Backend Files
1. **`app/Http/Controllers/ChatbotController.php`**
   - Handles chat message processing
   - Integrates with Gemini API
   - Manages conversation context
   - Provides initial greeting

2. **`config/services.php`**
   - Added Gemini API configuration

3. **`routes/web.php`**
   - Added chatbot API routes:
     - `POST /api/chatbot/chat` - Send messages
     - `GET /api/chatbot/greeting` - Get initial greeting

4. **`storage/app/chatbot/project-information.md`**
   - Contains company information for AI context
   - Can be updated to reflect current services and processes

### Frontend Files
1. **`public/js/chatbot.js`**
   - Complete chatbot widget implementation
   - Handles UI interactions
   - Manages caching
   - API communication

2. **`resources/views/layouts/public.blade.php`**
   - Updated to include chatbot script
   - Added CSRF token meta tag

### Configuration Files
1. **`.env.example`**
   - Added Gemini API configuration template

## Setup Instructions

### 1. Get Gemini API Key
1. Visit [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Sign in with your Google account
3. Create a new API key
4. Copy the API key

### 2. Configure Environment
Add the following to your `.env` file:

```bash
GEMINI_API_KEY=your_actual_gemini_api_key_here
GEMINI_MODEL=gemini-1.5-flash
```

### 3. Update Project Information (Optional)
Edit `storage/app/chatbot/project-information.md` to reflect your current:
- Services offered
- Pricing information
- Contact details
- Process information
- Any other relevant company information

### 4. Test the Integration
1. Visit any public page on your website
2. Look for the floating chat icon in the bottom-right corner
3. Click the icon to open the chat
4. Send a test message

## Usage

### For Visitors/Clients
1. **Open Chat**: Click the floating chat icon
2. **Ask Questions**: Type questions about services, pricing, process, etc.
3. **Use Suggestions**: Click on suggested questions for quick answers
4. **Close Chat**: Click the X button or the icon again
5. **Return Later**: Conversation history is saved for 1 hour

### Common Questions the Bot Can Answer
- "What services do you offer?"
- "How can I get started?"
- "What is the pricing?"
- "How does the project process work?"
- "How do I submit a service request?"
- "What payment methods do you accept?"
- "How long does a project take?"

## Customization

### Modifying the Appearance
Edit `public/js/chatbot.js` to customize:
- Colors (search for `primary-600`, `primary-700`, etc.)
- Size of the chat window
- Position of the floating icon
- Animation styles

### Changing Cache Duration
In `public/js/chatbot.js`, modify:
```javascript
this.cacheExpiry = 60 * 60 * 1000; // Current: 1 hour
// Change to 2 hours: 2 * 60 * 60 * 1000
// Change to 30 minutes: 30 * 60 * 1000
```

### Updating AI Behavior
Edit the system prompt in `app/Http/Controllers/ChatbotController.php`:
```php
private function buildContext($conversationHistory, $userMessage)
{
    $systemPrompt = "You are a helpful assistant...";
    // Modify the prompt to change AI behavior
}
```

### Adding to Other Layouts
To add the chatbot to other layouts (admin, client, adiutor):

1. Add CSRF token meta tag in `<head>`:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

2. Add the chatbot script before `</body>`:
```html
<script src="{{ asset('js/chatbot.js') }}"></script>
```

## API Reference

### POST /api/chatbot/chat
Send a message to the chatbot.

**Request Body:**
```json
{
  "message": "What services do you offer?",
  "conversation_history": [
    {
      "role": "user",
      "content": "Hello"
    },
    {
      "role": "model",
      "content": "Hi! How can I help you?"
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "response": "We offer web development, mobile development, design services...",
  "timestamp": "2025-10-21T12:00:00.000000Z"
}
```

### GET /api/chatbot/greeting
Get the initial greeting message.

**Response:**
```json
{
  "success": true,
  "message": "👋 Hello! I'm here to help you learn about Treis Adiutor's services. How can I assist you today?",
  "suggestions": [
    "What services do you offer?",
    "How can I get started?",
    "What is the pricing?",
    "How does the project process work?"
  ]
}
```

## Troubleshooting

### Chatbot Icon Not Appearing
1. Check browser console for JavaScript errors
2. Verify `chatbot.js` is loading correctly
3. Check that the script tag is in the layout file

### No Response from Bot
1. Verify Gemini API key is set in `.env`
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify internet connection (API requires external access)
4. Check API quota limits in Google AI Studio

### Cache Not Working
1. Check browser's localStorage is enabled
2. Clear browser cache and try again
3. Check browser console for errors

### Styling Issues
1. Ensure Tailwind CSS is properly compiled
2. Run `npm run build` to rebuild assets
3. Clear browser cache

## Security Considerations

1. **API Key Protection**: The Gemini API key is stored server-side and never exposed to clients
2. **Rate Limiting**: Consider adding rate limiting to prevent API abuse
3. **Input Validation**: User messages are validated (max 1000 characters)
4. **CSRF Protection**: All POST requests require CSRF token
5. **Content Filtering**: Gemini API has built-in safety settings

## Performance Optimization

1. **Caching**: Reduces API calls by caching conversations locally
2. **Lazy Loading**: Chatbot only initializes when DOM is ready
3. **Efficient Storage**: Only stores essential conversation data
4. **Timeout**: API requests have 30-second timeout

## Future Enhancements

Possible improvements:
- [ ] Add conversation export feature
- [ ] Implement user feedback on responses
- [ ] Add analytics tracking
- [ ] Support for file attachments
- [ ] Multi-language support
- [ ] Integration with CRM
- [ ] Chatbot availability schedule
- [ ] Admin dashboard for chat analytics

## Support

For issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Review API documentation: [Gemini API Docs](https://ai.google.dev/docs)
- Contact development team

## License

This chatbot integration is part of the Treis Adiutor CMS and is subject to the same proprietary license.
