<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    private $apiKey;
    private $model;
    private $apiUrl;
    private $projectInfo;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model');
        $this->apiUrl = config('services.gemini.api_url');
        
        // Load project information
        $this->projectInfo = $this->loadProjectInformation();
    }

    /**
     * Load project information from storage
     */
    private function loadProjectInformation()
    {
        try {
            $infoPath = 'chatbot/project-information.md';
            
            if (Storage::exists($infoPath)) {
                return Storage::get($infoPath);
            }
            
            return "Treis Adiutor is a professional technology service provider specializing in technological solutions.";
        } catch (\Exception $e) {
            Log::error('Failed to load project information: ' . $e->getMessage());
            return "Treis Adiutor is a professional technology service provider.";
        }
    }

    /**
     * Handle chat messages
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'conversation_history' => 'nullable|array',
        ]);

        try {
            $userMessage = $request->input('message');
            $conversationHistory = $request->input('conversation_history', []);

            // Build the conversation context
            $context = $this->buildContext($conversationHistory, $userMessage);

            // Call Gemini API
            $response = $this->callGeminiApi($context);

            return response()->json([
                'success' => true,
                'response' => $response,
                'timestamp' => now()->toIso8601String(),
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Sorry, I encountered an error processing your request. Please try again.',
                'message' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Build conversation context for Gemini
     */
    private function buildContext($conversationHistory, $userMessage)
    {  
        $systemPrompt = "You are a professional, friendly assistant for Treis Adiutor — a trusted technology service provider. ";
        $systemPrompt .= "Your goal is to clearly explain our services, simplify our process, and guide users to take action, such as submitting service requests or exploring relevant pages.\n\n";
        $systemPrompt .= "When mentioning website pages, ALWAYS format them as clickable links in this EXACT format: [Page Title](/route-path)\n";
        $systemPrompt .= "Examples:\n";
        $systemPrompt .= "- 'You can submit a service request on our [Get Started](/get-started) page'\n";
        $systemPrompt .= "- 'Learn more about what we offer on our [Services](/services) page'\n";
        $systemPrompt .= "- 'Manage your projects in the [Client Dashboard](/client/dashboard)'\n";
        $systemPrompt .= "- 'Find quick answers on our [FAQ](/faq) page'\n\n";
        $systemPrompt .= "Here's important information about Treis Adiutor:\n\n";
        $systemPrompt .= $this->projectInfo . "\n\n";
        $systemPrompt .= "Guidelines:\n";
        $systemPrompt .= "- Base answers only on the provided information\n";
        $systemPrompt .= "- Highlight relevant services and invite users to get started\n";
        $systemPrompt .= "- For project-related questions, guide users to submit a service request\n";
        $systemPrompt .= "- If unsure, politely clarify or acknowledge limits\n";
        $systemPrompt .= "- Keep responses concise (2–4 sentences unless more detail is requested)\n";
        $systemPrompt .= "- Maintain a friendly, confident, and professional tone\n";
        $systemPrompt .= "- ALWAYS include helpful clickable links in [text](/route) format\n";


        // Build the contents array for Gemini API
        $contents = [];
        
        // Add system prompt as first user message
        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $systemPrompt]
            ]
        ];
        
        // Add acknowledgment from model
        $contents[] = [
            'role' => 'model',
            'parts' => [
                ['text' => "I understand. I'm here to help answer questions about Treis Adiutor's services and guide clients."]
            ]
        ];

        // Add conversation history (limit to last 10 messages)
        $recentHistory = array_slice($conversationHistory, -10);
        foreach ($recentHistory as $message) {
            $role = $message['role'] === 'user' ? 'user' : 'model';
            $contents[] = [
                'role' => $role,
                'parts' => [
                    ['text' => $message['content']]
                ]
            ];
        }

        // Add current user message
        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $userMessage]
            ]
        ];

        return $contents;
    }

    /**
     * Call Gemini API
     */
    private function callGeminiApi($contents)
    {
        $url = "{$this->apiUrl}/{$this->model}:generateContent?key={$this->apiKey}";

        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.7,
                'topK' => 40,
                'topP' => 0.95,
                'maxOutputTokens' => 1024,
            ],
            'safetySettings' => [
                [
                    'category' => 'HARM_CATEGORY_HARASSMENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_HATE_SPEECH',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
                [
                    'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                    'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                ],
            ],
        ];

        $response = Http::timeout(30)
            ->post($url, $payload);

        if (!$response->successful()) {
            Log::error('Gemini API error: ' . $response->body());
            throw new \Exception('Failed to get response from AI service');
        }

        $data = $response->json();

        // Extract the response text from Gemini's response structure
        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            return $data['candidates'][0]['content']['parts'][0]['text'];
        }

        throw new \Exception('Invalid response format from AI service');
    }

    /**
     * Get initial greeting message
     */
    public function greeting()
    {
        return response()->json([
            'success' => true,
            'message' => "👋 Hello! I'm here to help you learn about Treis Adiutor's services.\n\nFeel free to ask me anything about our services, or check out our [Services](/services) page and [Get Started](/get-started) to begin your project!\n\nHow can I assist you today?",
            'suggestions' => [
                "What services do you offer?",
                "How can I get started?",
                "What is the pricing?",
                "How does the project process work?",
            ],
        ]);
    }
}
