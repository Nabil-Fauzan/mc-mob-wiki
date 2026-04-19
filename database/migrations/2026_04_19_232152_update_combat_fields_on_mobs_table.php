<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mobs', function (Blueprint $table) {
            $table->string('damage_easy')->nullable()->after('damage');
            $table->string('damage_normal')->nullable()->after('damage_easy');
            $table->string('damage_hard')->nullable()->after('damage_normal');
            $table->text('melee_attack')->nullable()->after('is_ranged');
            $table->text('ranged_attack')->nullable()->after('melee_attack');
        });
    }

    public function down(): void
    {
        Schema::table('mobs', function (Blueprint $table) {
            $table->dropColumn(['damage_easy', 'damage_normal', 'damage_hard', 'melee_attack', 'ranged_attack']);
        });
    }
};
