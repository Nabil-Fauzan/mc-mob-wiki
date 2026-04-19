<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add XP reward to mobs table
        Schema::table('mobs', function (Blueprint $table) {
            $table->string('xp_reward')->nullable()->after('drops');
        });

        // Create structured drops table
        Schema::create('mob_drops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mob_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->string('quantity')->default('1');
            $table->string('chance')->default('100%');
            $table->string('rarity')->default('Common'); // Common, Uncommon, Rare, Legendary
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mob_drops');
        Schema::table('mobs', function (Blueprint $table) {
            $table->dropColumn('xp_reward');
        });
    }
};
