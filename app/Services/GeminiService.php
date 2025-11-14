<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private $apiKey;
    private $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-001:generateContent';
    
    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }
    
    /**
     * Generate response from Gemini AI
     */
    public function generateResponse($prompt, $options = [])
    {
        if (empty($this->apiKey)) {
            Log::warning('Gemini API key not configured');
            return null;
        }
        
        try {
            $response = Http::timeout(30)
                ->post($this->apiUrl . '?key=' . $this->apiKey, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => $options['temperature'] ?? 0.7,
                        'maxOutputTokens' => $options['maxTokens'] ?? 2048,
                        'topP' => $options['topP'] ?? 0.8,
                        'topK' => $options['topK'] ?? 40,
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
                    ]
                ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Gemini 2.5 format - check for parts
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }
                
                // Alternative format - check if content has text directly
                if (isset($data['candidates'][0]['content']['text'])) {
                    return $data['candidates'][0]['content']['text'];
                }
                
                // Check if response was blocked or truncated
                if (isset($data['candidates'][0]['finishReason'])) {
                    $finishReason = $data['candidates'][0]['finishReason'];
                    Log::warning("Gemini response finished with reason: {$finishReason}", ['data' => $data]);
                    
                    // If MAX_TOKENS, try to get any partial content
                    if ($finishReason === 'MAX_TOKENS' && isset($data['candidates'][0]['content'])) {
                        // Try to extract any text from content
                        $content = $data['candidates'][0]['content'];
                        if (is_string($content)) {
                            return $content;
                        }
                    }
                }
                
                Log::warning('Gemini API returned unexpected format', ['data' => $data]);
                return null;
            }
            
            Log::error('Gemini API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }
    
    /**
     * Check if Gemini is configured and available
     */
    public function isAvailable()
    {
        return !empty($this->apiKey);
    }
}
