<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create the pivot table
        Schema::create('biome_mob', function (Blueprint $table) {
            $table->id();
            $table->foreignId('biome_id')->constrained()->onDelete('cascade');
            $table->foreignId('mob_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // 2. Migrate existing data
        $mobs = DB::table('mobs')->whereNotNull('biome_id')->get();
        foreach ($mobs as $mob) {
            DB::table('biome_mob')->insert([
                'biome_id' => $mob->biome_id,
                'mob_id' => $mob->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Drop the old column (remove foreign key first)
        Schema::table('mobs', function (Blueprint $table) {
            // Explicitly drop foreign key first
            $table->dropForeign(['biome_id']);
            $table->dropColumn('biome_id');
        });
    }

    public function down(): void
    {
        Schema::table('mobs', function (Blueprint $table) {
            $table->foreignId('biome_id')->nullable()->constrained()->onDelete('set null');
        });

        // Migrate back (if possible, takes the first biome found in pivot)
        $pivots = DB::table('biome_mob')->orderBy('created_at')->get();
        foreach ($pivots as $pivot) {
            DB::table('mobs')->where('id', $pivot->mob_id)
                ->whereNull('biome_id') // Avoid overwriting if multiple
                ->update(['biome_id' => $pivot->biome_id]);
        }

        Schema::dropIfExists('biome_mob');
    }
};
