<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OracleController extends Controller
{
    public function ask(Request $request)
    {
        $query = $request->input('query');
        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {
            return response()->json(['response' => 'ORACLE: [ERROR] GROQ API Key Missing.'], 200);
        }

        // Fetching "Aether Vision" Context
        $mobCount = \App\Models\Mob::count();
        $topCategory = \App\Models\Category::withCount('mobs')->orderBy('mobs_count', 'desc')->first();
        $latestMob = \App\Models\Mob::latest()->first();
        $uncategorized = \App\Models\Mob::whereNull('category_id')->count();

        $context = "Context of this Wiki: Total Entities: {$mobCount}. ";
        if ($topCategory) $context .= "Most populated category: {$topCategory->name} ({$topCategory->mobs_count} mobs). ";
        if ($latestMob) $context .= "Newest discovery: {$latestMob->name}. ";
        $context .= "Uncategorized items: {$uncategorized}. ";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post("https://api.groq.com/openai/v1/chat/completions", [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are 'The Oracle', a high-dimensional AI assistant for the Aether Ocean Minecraft Mob Wiki. Your tone is mysterious, intelligent, and authoritative. Use the following internal data to answer queries if relevant. {$context} Keep responses brief (max 3 sentences)."
                    ],
                    [
                        'role' => 'user',
                        'content' => $query
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 200
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data['choices'][0]['message']['content'] ?? 'ORACLE: [SIGNAL LOST] Unable to parse multiverse data.';
                return response()->json(['response' => 'ORACLE: ' . $aiResponse]);
            }

            $errorDetail = $response->json()['error']['message'] ?? 'Unknown Groq Error';
            return response()->json(['response' => 'ORACLE: [UNSTABLE] Status: ' . $response->status() . ' - Msg: ' . $errorDetail], 200);

        } catch (\Exception $e) {
            return response()->json(['response' => 'ORACLE: [CATASTROPHIC] ' . $e->getMessage()], 200);
        }
    }
}
