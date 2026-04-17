<?php

use App\Http\Controllers\MobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BiomeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $stats = [
        'mobs' => \App\Models\Mob::count(),
        'biomes' => \App\Models\Biome::count(),
        'dimensions' => \App\Models\Dimension::count(),
    ];
    return view('welcome', compact('stats'));
})->name('home');

Route::get('/mobs', [MobController::class, 'index'])->name('mobs.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $favorites = $user->favorite_mobs()->with(['category', 'biome'])->latest()->get();
        $stats = [
            'favorites_count' => $favorites->count(),
            'comments_count' => $user->comments()->count(),
            'recent_comments' => $user->comments()->with('mob')->latest()->limit(5)->get(),
        ];
        return view('dashboard', compact('favorites', 'stats'));
    })->name('dashboard');

    // Create route must come before the show (wildcard) route
    Route::get('/mobs/create', [MobController::class, 'create'])->name('mobs.create');
    Route::post('/mobs', [MobController::class, 'store'])->name('mobs.store');
    Route::get('/mobs/{mob}/edit', [MobController::class, 'edit'])->name('mobs.edit');
    Route::patch('/mobs/{mob}', [MobController::class, 'update'])->name('mobs.update');
    Route::delete('/mobs/{mob}', [MobController::class, 'destroy'])->name('mobs.destroy');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Community Actions
    Route::post('/mobs/{mob}/favorite', [FavoriteController::class, 'toggle'])->name('mobs.favorite');
    Route::post('/mobs/{mob}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Comparison Tool
Route::get('/comparison', [MobController::class, 'comparison'])->name('mobs.comparison');

// Analytics & Stats
Route::get('/stats', [AnalyticsController::class, 'index'])->name('stats.index');

// Biomes Discovery
Route::get('/biomes', [BiomeController::class, 'index'])->name('biomes.index');
Route::get('/biomes/{biome}', [BiomeController::class, 'show'])->name('biomes.show');

// Show route is public but must be last to avoid catching 'create'
Route::get('/mobs/{mob}', [MobController::class, 'show'])->name('mobs.show');

// Public Researcher Profiles
Route::get('/researchers/{user}', [\App\Http\Controllers\ResearcherController::class, 'show'])->name('researchers.show');

// Admin Security Gateway
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
    
    // Biome Deployment & Management
    Route::get('/biomes/create', [\App\Http\Controllers\BiomeController::class, 'create'])->name('biomes.create');
    Route::post('/biomes', [\App\Http\Controllers\BiomeController::class, 'store'])->name('biomes.store');
    Route::get('/biomes/{biome}/edit', [\App\Http\Controllers\BiomeController::class, 'edit'])->name('biomes.edit');
    Route::put('/biomes/{biome}', [\App\Http\Controllers\BiomeController::class, 'update'])->name('biomes.update');
    Route::delete('/biomes/{biome}', [\App\Http\Controllers\BiomeController::class, 'destroy'])->name('biomes.destroy');
});

require __DIR__.'/auth.php';
