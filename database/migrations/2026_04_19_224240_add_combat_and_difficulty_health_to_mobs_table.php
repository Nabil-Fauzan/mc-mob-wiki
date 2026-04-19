<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mobs', function (Blueprint $table) {
            $table->string('health_easy')->nullable()->after('health');
            $table->string('health_normal')->nullable()->after('health_easy');
            $table->string('health_hard')->nullable()->after('health_normal');
            $table->boolean('is_melee')->default(false)->after('spawning_conditions');
            $table->boolean('is_ranged')->default(false)->after('is_melee');
        });
    }

    public function down(): void
    {
        Schema::table('mobs', function (Blueprint $table) {
            $table->dropColumn(['health_easy', 'health_normal', 'health_hard', 'is_melee', 'is_ranged']);
        });
    }
};
