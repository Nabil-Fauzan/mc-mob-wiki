<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Biome;
use App\Models\Mob;
use App\Models\Category;
use App\Models\Dimension;

class SyncMinecraftData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wiki:sync-minecraft-data {--mc-version=1.20.2 : Minecraft version to fetch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Mobs and Biomes from PrismarineJS minecraft-data repository';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $version = $this->option('mc-version');
        $this->info("Initializing Aether Protocol Auto-Sync for Minecraft v{$version}...");

        $this->syncBiomes($version);
        $this->syncMobs($version);

        $this->info("Sync completed successfully!");
    }

    private function syncBiomes($version)
    {
        $this->info("Fetching Biomes data...");
        $response = Http::get("https://raw.githubusercontent.com/PrismarineJS/minecraft-data/master/data/pc/{$version}/biomes.json");

        if (!$response->successful()) {
            $this->error("Failed to fetch biomes data.");
            return;
        }

        $biomes = $response->json();
        $bar = $this->output->createProgressBar(count($biomes));

        foreach ($biomes as $b) {
            $dimensionName = ucfirst($b['dimension'] ?? 'Overworld');
            $dimension = Dimension::firstOrCreate(['name' => $dimensionName]);

            Biome::updateOrCreate(
                ['name' => $b['displayName']],
                [
                    'dimension_id' => $dimension->id,
                    'description' => 'Temperature: ' . ($b['temperature'] ?? 'N/A') . ', Precipitation: ' . (isset($b['has_precipitation']) && $b['has_precipitation'] ? 'Yes' : 'No')
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Biomes sync done!");
    }

    private function syncMobs($version)
    {
        $this->info("Fetching Mobs data...");
        $response = Http::get("https://raw.githubusercontent.com/PrismarineJS/minecraft-data/master/data/pc/{$version}/entities.json");

        if (!$response->successful()) {
            $this->error("Failed to fetch entities data.");
            return;
        }

        $entities = $response->json();
        
        $mobs = array_filter($entities, function ($entity) {
            return in_array($entity['type'] ?? '', ['mob', 'animal', 'hostile']);
        });

        $bar = $this->output->createProgressBar(count($mobs));

        foreach ($mobs as $m) {
            $categoryName = $m['category'] ?? 'Unknown';
            if ($categoryName === 'UNKNOWN') {
                $categoryName = 'Neutral';
            }
            
            $category = Category::firstOrCreate(['name' => $categoryName]);

            Mob::updateOrCreate(
                ['name' => $m['displayName']],
                [
                    'category_id' => $category->id,
                    'description' => "A " . strtolower($categoryName) . " mob in Minecraft.",
                    'health' => 20, // Default fallback if not provided
                    'damage' => 0
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Mobs sync done!");
    }
}
