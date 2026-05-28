<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mobs', function (Blueprint $table) {
            $table->text('description_id')->nullable()->after('description');
        });

        Schema::table('biomes', function (Blueprint $table) {
            $table->text('description_id')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('mobs', function (Blueprint $table) {
            $table->dropColumn('description_id');
        });

        Schema::table('biomes', function (Blueprint $table) {
            $table->dropColumn('description_id');
        });
    }
};;
