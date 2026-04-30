<?php

namespace App\Http\Controllers;

use App\Models\Mob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class OracleController extends Controller
{
    public function ask(Request $request)
    {
        $query = trim((string) $request->input('query', ''));
        $apiKey = env('GROQ_API_KEY');

        if ($query === '') {
            return response()->json(['response' => 'ORACLE: [ERROR] Query is empty.'], 422);
        }

        if (!$apiKey) {
            return response()->json(['response' => 'ORACLE: [ERROR] GROQ API Key Missing.'], 200);
        }

        $lang = $this->resolveLanguage($request->input('lang'));
        $mode = $this->resolveMode($request->input('mode'));

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

        if ($knowledge['matches']->isEmpty()) {
            return response()->json([
                'response' => $lang === 'id'
                    ? 'ORACLE: [NO DATA] Data internal tidak cukup untuk menjawab dengan aman. Coba sebut nama mob/biome spesifik.'
                    : 'ORACLE: [NO DATA] Internal data is insufficient for a safe answer. Try naming a specific mob/biome.',
                'meta' => ['guardrail' => 'no_internal_match', 'lang' => $lang, 'mode' => $mode],
            ]);
        }

        $systemPrompt = $this->buildSystemPrompt($knowledge['context'], $lang, $mode);

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
                        'source_count' => $knowledge['matches']->count(),
                    ],
                ]);
            }

            $errorDetail = $response->json()['error']['message'] ?? 'Unknown Groq Error';
            return response()->json(['response' => 'ORACLE: [UNSTABLE] Status: ' . $response->status() . ' - Msg: ' . $errorDetail], 200);
        } catch (\Exception $e) {
            return response()->json(['response' => 'ORACLE: [CATASTROPHIC] ' . $e->getMessage()], 200);
        }
    }

    private function retrieveKnowledge(string $query): array
    {
        $mobCount = Mob::count();
        $topCategory = \App\Models\Category::withCount('mobs')->orderBy('mobs_count', 'desc')->first();
        $latestMob = Mob::latest()->first();
        $uncategorized = Mob::whereNull('category_id')->count();

        $matches = Mob::query()
            ->with(['category', 'biomes', 'loot'])
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->limit(5)
            ->get();

        $lines = [];
        foreach ($matches as $mob) {
            $biomes = $mob->biomes->pluck('name')->take(3)->implode(', ');
            $category = $mob->category?->name ?? 'Unknown';
            $lines[] = "Mob={$mob->name}; Category={$category}; Health={$mob->health_normal}; Damage={$mob->damage_normal}; XP={$mob->xp_reward}; Biomes={$biomes}";
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
            'matches' => $matches,
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

    private function resolveLanguage(?string $lang): string
    {
        return in_array($lang, ['id', 'en'], true) ? $lang : 'id';
    }

    private function resolveMode(?string $mode): string
    {
        return in_array($mode, ['lore', 'data'], true) ? $mode : 'lore';
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
}
