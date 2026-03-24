<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // Auto name (UI has no name input)
        $userName = 'User';

        // Save user message
        Message::create([
            'name' => $userName,
            'message' => $request->message,
        ]);

        try {
            $apiKey = env('GROQ_API_KEY');
            if (!$apiKey) {
                throw new \Exception('Groq API key missing');
            }

            $client = new Client();

            $response = $client->post(
                'https://api.groq.com/openai/v1/chat/completions',
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'model' => 'llama-3.3-70b-versatile',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' =>
                                    'You are a helpful AI assistant inside a student dashboard. Keep replies short, clear, and friendly. Use **bold** for key points.',
                            ],
                            [
                                'role' => 'user',
                                'content' => $request->message,
                            ],
                        ],
                        'temperature' => 0.7,
                        'max_tokens' => 300,
                    ],
                    'timeout' => 30,
                ]
            );

            $data = json_decode($response->getBody(), true);
            $reply = $data['choices'][0]['message']['content'] ?? 'No response';

            Message::create([
                'name' => 'ChatBot 🤖',
                'message' => $this->formatResponse($reply),
            ]);
        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());

            Message::create([
                'name' => 'ChatBot 🤖',
                'message' => '**Error:** Something went wrong.',
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function fetchMessages()
    {
        return response()->json(
            Message::orderBy('created_at', 'asc')->get()
        );
    }

    private function formatResponse($text)
    {
        return preg_replace('/\*\*(.*?)\*\*/', '**$1**', $text);
    }
}
