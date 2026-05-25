<?php

use App\Http\Controllers\MobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BiomeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentVoteController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\OracleController;
use App\Http\Controllers\LeaderboardController;
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
        $favorites = $user->favorite_mobs()->with(['category', 'biomes'])->latest()->get();

        $commentsCount = $user->comments()->count();
        $favoritesCount = $favorites->count();
        $xp = ($favoritesCount * 125) + ($commentsCount * 350);
        $level = floor(sqrt($xp / 100)) + 1;
        $nextLevelXp = pow($level, 2) * 100;
        $progress = min(100, round(($xp / $nextLevelXp) * 100));

        $stats = [
            'favorites_count' => $favoritesCount,
            'comments_count' => $commentsCount,
            'recent_comments' => $user->comments()->with('mob')->latest()->limit(5)->get(),
            'xp' => $xp,
            'level' => $level,
            'progress' => $progress,
            'skills' => [
                'combat' => min(100, ($user->favorite_mobs()->whereHas('category', fn($q) => $q->where('name', 'Hostile'))->count() * 15) + ($user->comments()->whereHas('mob.category', fn($q) => $q->where('name', 'Hostile'))->count() * 10)),
                'survival' => min(100, ($user->favorite_mobs()->whereHas('category', fn($q) => $q->where('name', '!=', 'Hostile'))->count() * 15) + ($user->comments()->whereHas('mob.category', fn($q) => $q->where('name', '!=', 'Hostile'))->count() * 10)),
                'explorer' => min(100, $user->favorite_mobs()->with('biomes')->get()->pluck('biomes')->flatten()->unique('id')->count() * 20),
            ]
        ];
        
        $followingIds = $user->following()->pluck('users.id');
        
        $networkComments = \App\Models\Comment::whereIn('user_id', $followingIds)
            ->with(['user', 'mob'])
            ->latest()
            ->take(10)
            ->get();

        $networkFavorites = \Illuminate\Support\Facades\DB::table('favorites')
            ->whereIn('user_id', $followingIds)
            ->join('users', 'favorites.user_id', '=', 'users.id')
            ->join('mobs', 'favorites.mob_id', '=', 'mobs.id')
            ->select('favorites.created_at', 'users.name as user_name', 'users.public_slug', 'mobs.name as mob_name', 'mobs.id as mob_id')
            ->orderBy('favorites.created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($fav) {
                return (object)[
                    'type' => 'favorite',
                    'user' => (object)['name' => $fav->user_name, 'public_slug' => $fav->public_slug],
                    'mob' => (object)['id' => $fav->mob_id, 'name' => $fav->mob_name],
                    'created_at' => \Carbon\Carbon::parse($fav->created_at),
                ];
            });

        $networkCommentsMapped = $networkComments->map(function ($comment) {
            return (object)[
                'type' => 'comment',
                'user' => $comment->user,
                'mob' => $comment->mob,
                'content' => $comment->body,
                'created_at' => $comment->created_at,
            ];
        });

        $networkFeed = $networkCommentsMapped->concat($networkFavorites)->sortByDesc('created_at')->take(15);

        return view('dashboard', compact('favorites', 'stats', 'networkFeed'));
    })->name('dashboard');

    // Create route must come before the show (wildcard) route
    Route::get('/mobs/create', [MobController::class, 'create'])->name('mobs.create');
    Route::post('/mobs', [MobController::class, 'store'])->name('mobs.store');
    Route::get('/mobs/{mob}/edit', [MobController::class, 'edit'])->name('mobs.edit');
    Route::patch('/mobs/{mob}', [MobController::class, 'update'])->name('mobs.update');
    Route::delete('/mobs/{mob}', [MobController::class, 'destroy'])->name('mobs.destroy');
    Route::get('/mobs/{mob}/history', [MobController::class, 'history'])->name('mobs.history');
    Route::post('/mobs/{mob}/revert/{revision}', [MobController::class, 'revert'])->name('mobs.revert');

    Route::get('/api/oracle/threat-assessment', [OracleController::class, 'threatAssessment'])->name('api.oracle.threat');
    Route::post('/api/oracle/auto-tag', [OracleController::class, 'autoTag'])->name('api.oracle.autotag');
    Route::get('/api/oracle/translate/{mob}', [OracleController::class, 'translateLore'])->name('api.oracle.translate');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/sessions/{id}', [ProfileController::class, 'revokeSession'])->name('profile.sessions.destroy');

    Route::post('/notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.mark-read');

    // Community Actions
    Route::post('/mobs/{mob}/favorite', [FavoriteController::class, 'toggle'])->name('mobs.favorite');
    Route::post('/mobs/{mob}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/mobs/{mob}/contribute', [ContributionController::class, 'store'])->name('mobs.contribute');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/vote', [CommentVoteController::class, 'toggle'])->name('comments.vote');
});

// Comparison Tool
Route::get('/comparison', [MobController::class, 'comparison'])->name('mobs.comparison');

// Hall of Fame
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

// Analytics & Stats
Route::get('/stats', [AnalyticsController::class, 'index'])->name('stats.index');

// Biomes Discovery
Route::get('/biomes', [\App\Http\Controllers\BiomeController::class, 'index'])->name('biomes.index');
Route::get('/biomes/{biome}', [\App\Http\Controllers\BiomeController::class, 'show'])->name('biomes.show');

// Dimension Hub
Route::get('/dimensions', [\App\Http\Controllers\DimensionController::class, 'index'])->name('dimensions.index');

// Show route is public but must be last to avoid catching 'create'
Route::get('/mobs/{mob}', [MobController::class, 'show'])->name('mobs.show');

    // Public Researcher Profiles
    Route::get('/researchers/{user:public_slug}', [\App\Http\Controllers\ResearcherController::class, 'show'])->name('researchers.show');
    Route::post('/researchers/{user:public_slug}/connect', [\App\Http\Controllers\ConnectionController::class, 'toggle'])->name('researchers.connect');
Route::get('/api/search', [MobController::class, 'apiSearch'])->name('api.mobs.search');

// Oracle AI Endpoints
Route::post('/api/oracle', [OracleController::class, 'ask'])->middleware('throttle:oracle')->name('api.oracle');
Route::get('/api/oracle', [OracleController::class, 'ask'])->name('api.oracle.get');
Route::post('/api/oracle/contextual', [OracleController::class, 'contextualAsk'])->middleware('throttle:oracle')->name('api.oracle.contextual');

// Public Researcher Profiles
Route::get('/researchers/{user:public_slug}', [\App\Http\Controllers\ResearcherController::class, 'show'])->name('researchers.show');

// Impersonation feature
Route::post('/impersonate/leave', [\App\Http\Controllers\ImpersonationController::class, 'leave'])->name('impersonate.leave');

// Admin Security Gateway
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/impersonate/{user}', [\App\Http\Controllers\ImpersonationController::class, 'impersonate'])->name('impersonate');
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\AdminController::class, 'moderateComment'])->name('comments.destroy');
    Route::delete('/mobs/bulk-delete', [\App\Http\Controllers\AdminController::class, 'bulkDeleteMobs'])->name('mobs.bulk-delete');

    // Contributions
    Route::get('/contributions', [ContributionController::class, 'index'])->name('contributions.index');
    Route::post('/contributions/{contribution}/approve', [ContributionController::class, 'approve'])->name('contributions.approve');
    Route::post('/contributions/{contribution}/reject', [ContributionController::class, 'reject'])->name('contributions.reject');
    Route::post('/contributions/bulk-approve', [ContributionController::class, 'bulkApprove'])->name('contributions.bulk_approve');
    Route::post('/contributions/bulk-reject', [ContributionController::class, 'bulkReject'])->name('contributions.bulk_reject');

    // Biome Deployment & Management
    Route::get('/biomes/create', [\App\Http\Controllers\BiomeController::class, 'create'])->name('biomes.create');
    Route::post('/biomes', [\App\Http\Controllers\BiomeController::class, 'store'])->name('biomes.store');
    Route::get('/biomes/{biome}/edit', [\App\Http\Controllers\BiomeController::class, 'edit'])->name('biomes.edit');
    Route::put('/biomes/{biome}', [\App\Http\Controllers\BiomeController::class, 'update'])->name('biomes.update');
    Route::delete('/biomes/{biome}', [\App\Http\Controllers\BiomeController::class, 'destroy'])->name('biomes.destroy');
});

require __DIR__.'/auth.php';
