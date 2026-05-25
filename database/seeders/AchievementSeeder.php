<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            ['name' => 'First Blood', 'description' => 'Record first field note', 'icon' => '🩸', 'criteria_type' => 'comments', 'criteria_value' => 1, 'reward_title' => 'Novice Field Guide'],
            ['name' => 'Archivist', 'description' => '5 species favorited', 'icon' => '📚', 'criteria_type' => 'favorites', 'criteria_value' => 5, 'reward_title' => 'The Archivist'],
            ['name' => 'Elite Agent', 'description' => 'Reach level 3', 'icon' => '🛡️', 'criteria_type' => 'level', 'criteria_value' => 3, 'reward_title' => 'Elite Vanguard'],
            ['name' => 'Ghost Hunter', 'description' => 'Analyze 5 Nether mobs', 'icon' => '👻', 'criteria_type' => 'nether', 'criteria_value' => 5, 'reward_title' => 'Nether Ghost Hunter'],
            ['name' => 'Dimension Hopper', 'description' => 'Visit all dimension hubs', 'icon' => '🌀', 'criteria_type' => 'manual', 'criteria_value' => 0, 'reward_title' => 'Dimension Hopper'],
            ['name' => 'Community Signal', 'description' => 'Earned 5 reputation from comment upvotes.', 'icon' => '📡', 'criteria_type' => 'reputation', 'criteria_value' => 5, 'reward_title' => 'Respected Voice'],
            ['name' => 'Seasoned Tracker', 'description' => 'Recorded at least 10 field notes.', 'icon' => '🗺️', 'criteria_type' => 'comments', 'criteria_value' => 10, 'reward_title' => 'Master Tracker'],
            ['name' => 'Curator', 'description' => 'Saved 10 favorite entities.', 'icon' => '💎', 'criteria_type' => 'favorites', 'criteria_value' => 10, 'reward_title' => 'The Curator'],
            ['name' => 'Respected Voice', 'description' => 'Reached 25 total reputation.', 'icon' => '👑', 'criteria_type' => 'reputation', 'criteria_value' => 25, 'reward_title' => 'Wiki Legend'],
            ['name' => 'Contributor', 'description' => 'Have 1 edit suggestion approved.', 'icon' => '✍️', 'criteria_type' => 'contributions', 'criteria_value' => 1, 'reward_title' => 'Wiki Contributor'],
        ];

        foreach ($achievements as $ach) {
            \App\Models\Achievement::firstOrCreate(
                ['name' => $ach['name']],
                $ach
            );
        }

        // Migrate users XP
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            $favs = $user->favorite_mobs()->count();
            $coms = $user->comments()->count();
            // Old calculation was ($favs * 125) + ($coms * 350)
            $oldXp = ($favs * 125) + ($coms * 350);
            
            if ($user->xp == 0) {
                $user->addXp($oldXp); // This will also calculate level
            }
        }
    }
}
