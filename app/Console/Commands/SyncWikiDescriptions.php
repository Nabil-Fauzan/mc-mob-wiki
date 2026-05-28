<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mob;
use App\Models\Biome;
use Illuminate\Support\Facades\Http;
use Stichoza\GoogleTranslate\GoogleTranslate;

class SyncWikiDescriptions extends Command
{
    protected $signature = 'wiki:sync-descriptions';
    protected $description = 'Fetch English lore from Minecraft Wiki API and translate it to Indonesian using Google Translate.';

    public function handle()
    {
        $this->info('Initializing Wikipedia Description Scraper...');
        
        $translator = new GoogleTranslate('id');
        $translator->setSource('en');

        // Target models
        $entities = collect([
            ['type' => 'Mob', 'models' => Mob::all()],
            ['type' => 'Biome', 'models' => Biome::all()]
        ]);

        foreach ($entities as $entityType) {
            $type = $entityType['type'];
            $models = $entityType['models'];

            $this->info("Scanning {$type}s for descriptions...");
            
            if ($models->isEmpty()) {
                $this->warn("No {$type}s found. Skipping.");
                continue;
            }

            $bar = $this->output->createProgressBar($models->count());
            $bar->start();

            foreach ($models as $model) {
                // Ensure proper Wiki casing: e.g. "Trader Llama" -> "Trader Llama" 
                $title = urlencode($model->name);
                $url = "https://minecraft.wiki/api.php?action=query&prop=extracts&exintro=1&explaintext=1&titles={$title}&format=json";
                
                try {
                    $response = Http::timeout(10)->get($url);
                    if ($response->successful()) {
                        $pages = $response->json('query.pages');
                        $firstPage = is_array($pages) ? reset($pages) : null;
                        
                        if ($firstPage && isset($firstPage['extract']) && !empty($firstPage['extract'])) {
                            // Extract first paragraph only
                            $englishText = explode("\n", $firstPage['extract'])[0];
                            
                            // Translate to Indonesian
                            $indoText = $translator->translate($englishText);
                            
                            // Save both
                            $model->update([
                                'description' => $englishText,
                                'description_id' => $indoText
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    // Log or ignore timeout
                }
                
                $bar->advance();
            }
            $bar->finish();
            $this->newLine();
            $this->info("Finished processing {$type}s.");
        }

        $this->info('All description operations completed!');
    }
}
