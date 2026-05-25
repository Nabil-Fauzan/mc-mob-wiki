<?php

namespace App\Http\Controllers;

use App\Models\Mob;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OracleController extends Controller
{
    private function getApiKey(): ?string
    {
        return env('GROQ_API_KEY');
    }

    public function ask(Request $request)
    {
        $validated = $request->validate([
            'query' => ['required', 'string', 'max:500'],
            'lang' => ['nullable', 'in:id,en'],
            'mode' => ['nullable', 'in:lore,data'],
        ]);

        $query = trim($validated['query']);
        $lang = $validated['lang'] ?? 'id';
        $mode = $validated['mode'] ?? 'lore';

        if (!$this->allowRateLimitedRequest($request->ip())) {
            return response()->json([
                'response' => $lang === 'id'
                    ? 'ORACLE: [RATE LIMITED] Terlalu banyak permintaan. Coba lagi dalam satu menit.'
                    : 'ORACLE: [RATE LIMITED] Too many requests. Try again in a minute.',
            ], 429);
        }

        $cacheKey = $this->cacheKey($query, $lang, $mode);
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return response()->json([
                'response' => $cached,
                'meta' => ['cached' => true, 'lang' => $lang, 'mode' => $mode],
            ]);
        }

        $knowledge = $this->retrieveKnowledge($query);



        $systemPrompt = $this->buildSystemPrompt($knowledge['context'], $lang, $mode);
        $apiKey = $this->getApiKey();

        if (!$apiKey) {
            return response()->json(['response' => 'ORACLE: [ERROR] GROQ API Key Missing.'], 200);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $query],
                ],
                'temperature' => 0.5,
                'max_tokens' => 250,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data['choices'][0]['message']['content'] ?? '[SIGNAL LOST] Unable to parse multiverse data.';
                $final = 'ORACLE: ' . $aiResponse;

                Cache::put($cacheKey, $final, now()->addMinutes(10));

                return response()->json([
                    'response' => $final,
                    'meta' => [
                        'cached' => false,
                        'lang' => $lang,
                        'mode' => $mode,
                        'source_count' => $knowledge['source_count'],
                    ],
                ]);
            }

            $errorDetail = $response->json()['error']['message'] ?? 'Unknown Groq Error';
            return response()->json(['response' => 'ORACLE: [UNSTABLE] Status: ' . $response->status() . ' - Msg: ' . $errorDetail], 200);
        } catch (\Exception $e) {
            return response()->json(['response' => 'ORACLE: [CATASTROPHIC] ' . $e->getMessage()], 200);
        }
    }

    /**
     * AI Content Moderation for Contributions
     */
    public function moderateContent(string $mobName, string $field, string $proposedValue)
    {
        $apiKey = $this->getApiKey();
        if (!$apiKey) {
            return ['score' => 50, 'assessment' => 'No API Key configured.'];
        }

        $systemPrompt = "You are an expert AI moderator for a Minecraft Wiki. Your job is to review a proposed edit.
The user is suggesting to update the '{$field}' field of the mob '{$mobName}'.
Proposed value: \"{$proposedValue}\"
Analyze if this edit is accurate in Minecraft, safe, and not spam.
IMPORTANT: The proposed value can be in English OR Indonesian. Do NOT reject it just because it is not English.
Respond ONLY with a valid JSON object in this exact format:
{
  \"score\": <integer 0 to 100 representing trust/accuracy>,
  \"assessment\": \"<short 1-sentence reason explaining why, written in Indonesian>\"
}";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                ],
                'temperature' => 0.1,
                'max_tokens' => 150,
                'response_format' => ['type' => 'json_object'],
            ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'];
                $data = json_decode($content, true);
                return [
                    'score' => $data['score'] ?? 50,
                    'assessment' => $data['assessment'] ?? 'Unable to parse AI response.',
                ];
            }
            return ['score' => 50, 'assessment' => 'API Error: ' . $response->status()];
        } catch (\Exception $e) {
            return ['score' => 50, 'assessment' => 'Exception: ' . $e->getMessage()];
        }
    }

    /**
     * Smart Auto-Tagging for Creating Mobs
     */
    public function autoTag(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200']
        ]);

        $name = $validated['name'];
        $apiKey = $this->getApiKey();
        
        if (!$apiKey) {
            return response()->json(['error' => 'API Key missing'], 500);
        }

        $categories = \App\Models\Category::pluck('name', 'id')->toArray();
        $biomes = \App\Models\Biome::pluck('name', 'id')->toArray();

        $catJson = json_encode($categories);
        $bioJson = json_encode($biomes);

        $systemPrompt = "You are a Minecraft data assistant.
Given a mob name: '{$name}'
Return ONLY a valid JSON object matching this structure with your best estimates based on standard Minecraft lore.
{
  \"category_id\": <integer ID from this mapping: {$catJson}>,
  \"biome_ids\": [<array of integer IDs from this mapping: {$bioJson}>],
  \"health\": \"<string number>\",
  \"damage\": \"<string number>\",
  \"description\": \"<a short 2-3 sentence lore/description>\"
}";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                ],
                'temperature' => 0.1,
                'max_tokens' => 200,
                'response_format' => ['type' => 'json_object'],
            ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'];
                return response()->json(json_decode($content, true));
            }
            return response()->json(['error' => 'API Error'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * SURVIVAL GUIDE RAG: Contextual Ask specific to a mob
     */
    public function contextualAsk(Request $request)
    {
        $validated = $request->validate([
            'query' => ['required', 'string', 'max:500'],
            'mob_id' => ['required', 'exists:mobs,id'],
        ]);

        $mob = Mob::with(['category', 'biomes', 'loot'])->findOrFail($validated['mob_id']);
        $query = trim($validated['query']);
        $cacheKey = 'oracle:ctx:' . sha1($mob->id . '|' . $query);

        if ($cached = Cache::get($cacheKey)) {
            return response()->json(['response' => $cached, 'cached' => true]);
        }

        $drops = $mob->loot->pluck('item_name')->implode(', ') ?: 'None';
        $biomes = $mob->biomes->pluck('name')->implode(', ') ?: 'Unknown';
        
        $context = "Entity Profile:\n";
        $context .= "- Name: {$mob->name}\n";
        $context .= "- Category: {$mob->category->name}\n";
        $context .= "- Health: {$mob->health_normal} (Easy: {$mob->health_easy}, Hard: {$mob->health_hard})\n";
        $context .= "- Damage: {$mob->damage_normal} (Easy: {$mob->damage_easy}, Hard: {$mob->damage_hard})\n";
        $context .= "- Biomes: {$biomes}\n";
        $context .= "- Drops: {$drops}\n";
        $context .= "- Description: {$mob->description}\n";
        
        $systemPrompt = "You are ORACLE, the Aether Ocean tactical advisor. Based ONLY on the following entity profile, answer the researcher's query in Indonesian with a cinematic, highly tactical tone. Limit your response to 2-3 sentences max. If the profile lacks info, state it clearly.\n\n" . $context;

        $apiKey = $this->getApiKey();
        if (!$apiKey) return response()->json(['response' => 'ORACLE: [ERROR] API Key Missing.'], 200);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $query],
                ],
                'temperature' => 0.4,
                'max_tokens' => 200,
            ]);

            if ($response->successful()) {
                $aiResponse = $response->json()['choices'][0]['message']['content'] ?? '';
                Cache::put($cacheKey, $aiResponse, now()->addMinutes(30));
                return response()->json(['response' => $aiResponse, 'cached' => false]);
            }
            return response()->json(['response' => 'ORACLE: [UNSTABLE] Connection failure.'], 200);
        } catch (\Exception $e) {
            return response()->json(['response' => 'ORACLE: [ERROR] ' . $e->getMessage()], 200);
        }
    }

    public function extractSemanticName(string $query): ?string
    {
        $cacheKey = 'oracle:semantic:' . sha1($query);
        if ($cached = Cache::get($cacheKey)) {
            return $cached;
        }

        $apiKey = $this->getApiKey();
        if (!$apiKey) return null;

        $systemPrompt = "You are a Minecraft entity typo-corrector and semantic extractor. Given a user query, identify the single most likely Minecraft Mob or Biome it refers to. Fix any typos (e.g. 'ghats' -> 'Ghast', 'zomebi' -> 'Zombie'). Reply with ONLY the exact official name of the entity/biome. No punctuation, no extra words. Example: 'green exploding thing' -> 'Creeper'.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $query],
                ],
                'temperature' => 0.1,
                'max_tokens' => 20,
            ]);

            if ($response->successful()) {
                $name = trim($response->json()['choices'][0]['message']['content'] ?? '');
                if ($name) {
                    Cache::put($cacheKey, $name, now()->addHours(24));
                    return $name;
                }
            }
        } catch (\Exception $e) {
            // Ignore silently
        }
        return null;
    }

    /**
     * TOXICITY SHIELD: Check if a comment is toxic or spam
     */
    public function checkToxicity(string $text): bool
    {
        $apiKey = $this->getApiKey();
        if (!$apiKey) return false; // Fail open if no API key

        $systemPrompt = "You are a toxicity and spam filter for a Minecraft Wiki community. 
Analyze the following text. If it contains highly offensive language, hate speech, severe toxicity, or obvious spam, reply with exactly 'TOXIC'. 
Otherwise, reply with exactly 'SAFE'. Do not add any other words.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $text],
                ],
                'temperature' => 0.0,
                'max_tokens' => 10,
            ]);

            if ($response->successful()) {
                $result = trim($response->json()['choices'][0]['message']['content'] ?? '');
                return strtoupper($result) === 'TOXIC';
            }
        } catch (\Exception $e) {
            // Ignore silently
        }
        return false;
    }

    /**
     * AI CLUSTERING: Get related entities based on lore and stats
     */
    public function getRelatedEntities(Mob $mob)
    {
        $cacheKey = 'oracle:related:' . $mob->id;
        if ($cached = Cache::get($cacheKey)) {
            return Mob::whereIn('id', $cached)->get();
        }

        $apiKey = $this->getApiKey();
        if (!$apiKey) return collect();

        // Get all other mobs (id and name)
        $otherMobs = Mob::where('id', '!=', $mob->id)->select('id', 'name')->get();
        if ($otherMobs->isEmpty()) return collect();
        
        $mobsList = $otherMobs->map(function($m) { return $m->id . ':' . $m->name; })->implode(', ');

        $systemPrompt = "You are an AI clustering system. Find up to 4 related Minecraft mobs for '{$mob->name}' (Category: {$mob->category->name}, Biomes: {$mob->biomes->pluck('name')->implode(', ')}).
Choose from this list: {$mobsList}.
Base your relation on shared lore, combat style, biology, or ecosystem.
Reply ONLY with a valid JSON array of the integer IDs of the related mobs. Example: [1, 5, 12]";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                ],
                'temperature' => 0.2,
                'max_tokens' => 50,
                'response_format' => ['type' => 'json_object'],
            ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'] ?? '[]';
                // the json_object format forces {} wrapping usually, but let's parse safely
                $data = json_decode($content, true);
                $ids = [];
                if (is_array($data)) {
                    // if it returned {"related": [1,2]} or just an array
                    foreach($data as $val) {
                        if (is_array($val)) {
                            $ids = $val;
                            break;
                        }
                    }
                    if (empty($ids) && isset($data[0])) {
                        $ids = $data;
                    }
                }
                
                $ids = array_filter((array)$ids, 'is_numeric');
                $ids = array_slice($ids, 0, 4);

                if (!empty($ids)) {
                    Cache::put($cacheKey, $ids, now()->addDays(7));
                    return Mob::whereIn('id', $ids)->get();
                }
            }
        } catch (\Exception $e) {
            // Ignore
        }
        
        return collect();
    }

    /**
     * AI LORE TRANSLATION: Translate mob description
     */
    public function translateLore(Request $request, Mob $mob)
    {
        $validated = $request->validate([
            'target_lang' => ['required', 'in:en,id'],
        ]);
        
        $lang = $validated['target_lang'];
        $cacheKey = 'oracle:translate:' . $mob->id . ':' . $lang;

        if ($cached = Cache::get($cacheKey)) {
            return response()->json(['translation' => $cached]);
        }

        $apiKey = $this->getApiKey();
        if (!$apiKey) return response()->json(['error' => 'API Key Missing'], 500);

        $targetName = $lang === 'en' ? 'English' : 'Indonesian';
        $systemPrompt = "You are a professional localizer for Minecraft. Translate the following entity lore into highly immersive {$targetName}. Maintain the cinematic, scientific tone. Return ONLY the translated text, no quotation marks or extra chat.";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $mob->description],
                ],
                'temperature' => 0.3,
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                $translation = trim($response->json()['choices'][0]['message']['content'] ?? '');
                if ($translation) {
                    Cache::put($cacheKey, $translation, now()->addDays(30));
                    return response()->json(['translation' => $translation]);
                }
            }
            return response()->json(['error' => 'Failed to translate'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * DYNAMIC THREAT ASSESSMENT: Analyze user profile and give a status report
     */
    public function threatAssessment(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) return response()->json(['response' => 'Unauthorized'], 401);

        $cacheKey = 'oracle:threat:' . $user->id;
        if ($cached = Cache::get($cacheKey)) {
            return response()->json(['response' => $cached, 'cached' => true]);
        }

        $favorites = $user->favorite_mobs()->with('category')->latest()->take(5)->get();
        if ($favorites->isEmpty()) {
            return response()->json(['response' => "Pola penjelajahan masih kosong. Kunjungi beberapa entitas untuk mendapatkan Analisis Ancaman dari Oracle.", 'cached' => false]);
        }

        $entities = $favorites->map(fn($f) => $f->name . ' (' . $f->category->name . ')')->implode(', ');

        $systemPrompt = "You are ORACLE, giving a daily Threat Assessment for a Minecraft researcher. Based on their recently researched entities: [{$entities}]. Write a 2-sentence cinematic threat assessment in Indonesian, warning them of specific environmental hazards or tactical preparations needed based on those specific mobs. Do not greet them, just output the assessment.";

        $apiKey = $this->getApiKey();
        if (!$apiKey) return response()->json(['response' => 'AI System Offline.'], 200);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => 'Generate threat assessment.'],
                ],
                'temperature' => 0.6,
                'max_tokens' => 150,
            ]);

            if ($response->successful()) {
                $aiResponse = $response->json()['choices'][0]['message']['content'] ?? '';
                Cache::put($cacheKey, $aiResponse, now()->addMinutes(60)); // Cache for 1 hour
                return response()->json(['response' => $aiResponse, 'cached' => false]);
            }
            return response()->json(['response' => '[UNSTABLE] Gagal membuat analisis ancaman.'], 200);
        } catch (\Exception $e) {
            return response()->json(['response' => '[ERROR] Sensor offline.'], 200);
        }
    }


    // Existing Helpers
    private function retrieveKnowledge(string $query): array
    {
        $mobCount = Mob::count();
        $topCategory = Category::withCount('mobs')->orderBy('mobs_count', 'desc')->first();
        $latestMob = Mob::latest()->first();
        $uncategorized = Mob::whereNull('category_id')->count();

        $matches = Mob::query()
            ->with(['category', 'biomes', 'loot'])
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->limit(5)
            ->get();

        if ($matches->isEmpty()) {
            $semanticName = $this->extractSemanticName($query);
            if ($semanticName) {
                $matches = Mob::query()
                    ->with(['category', 'biomes', 'loot'])
                    ->where('name', 'like', '%' . $semanticName . '%')
                    ->limit(5)
                    ->get();
            }
        }

        $biomeMatches = \App\Models\Biome::query()
            ->with('dimension')
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->limit(3)
            ->get();

        $lines = [];
        foreach ($matches as $mob) {
            $biomes = $mob->biomes->pluck('name')->take(3)->implode(', ');
            $category = $mob->category?->name ?? 'Unknown';
            $lines[] = "Mob={$mob->name}; Category={$category}; Health={$mob->health_normal}; Damage={$mob->damage_normal}; XP={$mob->xp_reward}; Biomes={$biomes}";
        }
        foreach ($biomeMatches as $biome) {
            $dim = $biome->dimension?->name ?? 'Unknown';
            $lines[] = "Biome={$biome->name}; Dimension={$dim}; Description={$biome->description}";
        }

        $globalContext = "Wiki Stats: Total Entities={$mobCount}; ";
        if ($topCategory) {
            $globalContext .= "Top Category={$topCategory->name} ({$topCategory->mobs_count}); ";
        }
        if ($latestMob) {
            $globalContext .= "Newest Discovery={$latestMob->name}; ";
        }
        $globalContext .= "Uncategorized={$uncategorized}.";

        return [
            'source_count' => $matches->count() + $biomeMatches->count(),
            'context' => $globalContext . ' Matched Records: ' . implode(' | ', $lines),
        ];
    }

    private function buildSystemPrompt(string $context, string $lang, string $mode): string
    {
        $languageRule = $lang === 'id'
            ? 'Answer strictly in Indonesian.'
            : 'Answer strictly in English.';

        $modeRule = $mode === 'data'
            ? 'Use concise, factual bullets with numbers and direct comparisons. Avoid dramatic style.'
            : 'Use immersive lore style, but keep facts accurate and grounded in provided data.';

        return "You are 'The Oracle' for Aether Ocean Minecraft Mob Wiki. {$languageRule} {$modeRule} Use ONLY provided internal context. If data is missing, explicitly say data is unavailable. Max 4 sentences. Internal Context: {$context}";
    }

    private function allowRateLimitedRequest(string $ip): bool
    {
        $key = 'oracle:rl:' . sha1($ip);
        $attempts = Cache::increment($key);

        if ($attempts === 1) {
            Cache::put($key, 1, now()->addMinute());
        }

        return $attempts <= 20;
    }

    private function cacheKey(string $query, string $lang, string $mode): string
    {
        return 'oracle:resp:' . sha1(strtolower($query) . '|' . $lang . '|' . $mode);
    }

    /**
     * Generate Changelog from raw git commits
     */
    public function generateChangelog(string $rawCommits, ?string $lastVersion = null): array
    {
        $apiKey = $this->getApiKey();
        if (!$apiKey) {
            throw new \Exception("GROQ_API_KEY missing.");
        }

        $systemPrompt = "You are an AI generating Release Notes for 'Aether Protocol', a sci-fi wiki web application for a Minecraft project. 
Given the following raw developer git commits, summarize them into a beautiful, user-friendly Release Notes document formatted in Markdown.
Group them by '🚀 New Features', '🐛 Bug Fixes', and '✨ UI/UX Improvements'. 
Also, based on the magnitude of the changes, propose a semantic version number. The last version was: " . ($lastVersion ?? "None") . ".
Respond ONLY in valid JSON format with three keys: 'version' (string), 'title' (string, a catchy name for this update), and 'markdown_content' (string).";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.3-70b-versatile',
            'response_format' => ['type' => 'json_object'],
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => "Raw commits:\n" . $rawCommits],
            ],
            'temperature' => 0.5,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? '{}';
            return json_decode($content, true);
        }

        throw new \Exception("Oracle API Error: " . $response->body());
    }
}
