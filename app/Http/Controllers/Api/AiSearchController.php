<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiSearchController extends Controller
{
    /**
     * Perform AI-powered service search using Gemini API
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:500',
            'services' => 'required|array'
        ]);

        $query = $request->input('query');
        $services = $request->input('services');

        // Get Gemini API key from environment
        $geminiApiKey = env('GEMINI_API_KEY');
        
        if (!$geminiApiKey) {
            return response()->json([
                'error' => 'Gemini API key not configured'
            ], 500);
        }

        // Build the prompt
        $serviceNames = collect($services)->pluck('service_name')->map(fn($name) => "\"{$name}\"")->join(', ');
        
        $prompt = "I'm a user looking for a service related to \"{$query}\". Please provide suggestions for services in the following structure, considering these existing service names: {$serviceNames}. Ensure that the new service prices are in the range of 100 to 1000. Service type should be just the name itself and one of these 3: 1. Writing 2. Editing & Arts 3. Programming Structure should be:

Service Type: [There are 3 service type: Writing / Editing & Arts / Programming.]
Service Name: [service name]
Service Description: [service description]
Price: [estimated price]

Return only 1 service if the query is too generic, and return 3-5 if there is a specific query";

        try {
            // Call Gemini API
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$geminiApiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]
            );

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Gemini API raw response:', $data);

                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $responseText = trim($data['candidates'][0]['content']['parts'][0]['text']);
                    
                    return response()->json([
                        'responseText' => $responseText
                    ]);
                } else {
                    Log::error('No valid response from Gemini', $data);
                    return response()->json([
                        'error' => 'Invalid response from Gemini'
                    ], 500);
                }
            } else {
                Log::error('Gemini API error: ' . $response->body());
                return response()->json([
                    'error' => 'Error calling Gemini API',
                    'details' => $response->body()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Gemini error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error calling Gemini API',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
