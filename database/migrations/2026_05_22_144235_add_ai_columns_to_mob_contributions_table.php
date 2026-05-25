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
        Schema::table('mob_contributions', function (Blueprint $table) {
            $table->string('ai_assessment')->nullable();
            $table->integer('ai_trust_score')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mob_contributions', function (Blueprint $table) {
            $table->dropColumn(['ai_assessment', 'ai_trust_score']);
        });
    }
};
