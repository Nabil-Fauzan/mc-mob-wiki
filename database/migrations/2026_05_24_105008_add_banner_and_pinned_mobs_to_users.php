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
        Schema::table('users', function (Blueprint $table) {
            $table->string('banner', 500)->nullable()->after('avatar');
        });

        Schema::create('user_pinned_mobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mob_id')->constrained()->cascadeOnDelete();
            $table->integer('slot_index')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_pinned_mobs');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('banner');
        });
    }
};
