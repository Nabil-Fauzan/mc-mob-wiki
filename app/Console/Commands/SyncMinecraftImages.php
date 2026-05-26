<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Mob;
use App\Models\Biome;

class SyncMinecraftImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wiki:sync-images {--force : Timpa gambar yang sudah ada}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape and download high-resolution images from Minecraft Wiki API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Initializing Image Scraper Protocol...");
        
        $force = $this->option('force');

        $this->syncModelImages(Mob::class, 'mobs', $force);
        $this->syncModelImages(Biome::class, 'biomes', $force);

        $this->info("All image scraping operations completed!");
    }

    private function syncModelImages($modelClass, $folder, $force)
    {
        $this->info("Scanning {$folder} for missing images...");
        
        $query = $modelClass::query();
        if (!$force) {
            $query->whereNull('image');
        }

        $records = $query->get();
        if ($records->isEmpty()) {
            $this->info("No missing images found for {$folder}. Skipping.");
            return;
        }

        $this->info("Found " . $records->count() . " records. Beginning download sequence...");
        $bar = $this->output->createProgressBar($records->count());

        foreach ($records as $record) {
            $imageUrl = $this->fetchImageUrlFromWiki($record->name);

            if ($imageUrl) {
                try {
                    $imageContent = Http::timeout(10)->get($imageUrl)->body();
                    $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
                    
                    // Sanitize filename
                    $safeName = Str::slug($record->name);
                    $filename = "{$folder}/{$safeName}.{$extension}";

                    Storage::disk('public')->put($filename, $imageContent);

                    $record->update(['image' => $filename]);
                } catch (\Exception $e) {
                    // Ignore download errors to let the loop continue
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Finished processing {$folder}.");
    }

    private function fetchImageUrlFromWiki($name)
    {
        // For biomes, we might need to exact match the title, e.g. "Badlands"
        // For mobs, "Allay", "Zombie" etc.
        $title = urlencode($name);
        $url = "https://minecraft.wiki/api.php?action=query&titles={$title}&prop=pageimages&format=json&pithumbsize=500";

        try {
            $response = Http::timeout(10)->get($url);
            if ($response->successful()) {
                $data = $response->json();
                $pages = $data['query']['pages'] ?? [];
                
                foreach ($pages as $page) {
                    if (isset($page['thumbnail']['source'])) {
                        return $page['thumbnail']['source'];
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore API timeout/errors
        }

        return null;
    }
}
