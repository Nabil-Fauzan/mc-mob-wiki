<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('public_slug')->nullable()->unique()->after('minecraft_username');
            $table->boolean('profile_is_public')->default(true)->after('public_slug');
        });

        $users = DB::table('users')->select('id', 'name')->get();
        $used = [];

        foreach ($users as $user) {
            $base = Str::slug($user->name);
            $base = $base !== '' ? $base : 'researcher';
            $slug = $base;
            $counter = 2;

            while (in_array($slug, $used, true)) {
                $slug = $base . '-' . $counter;
                $counter++;
            }

            $used[] = $slug;

            DB::table('users')
                ->where('id', $user->id)
                ->update(['public_slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['public_slug', 'profile_is_public']);
        });
    }
};
