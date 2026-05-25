<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password', 'is_admin', 'avatar', 'banner', 'minecraft_username', 'public_slug', 'profile_is_public', 'active_title'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'profile_is_public' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (blank($user->public_slug)) {
                $user->public_slug = static::generateUniqueSlug($user->name);
            }
        });
    }

    public function favorite_mobs()
    {
        return $this->belongsToMany(Mob::class, 'favorites')->withTimestamps();
    }

    public function pinned_mobs()
    {
        return $this->belongsToMany(Mob::class, 'user_pinned_mobs')
                    ->withPivot('slot_index')
                    ->orderBy('slot_index')
                    ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_connections', 'following_id', 'follower_id')->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'user_connections', 'follower_id', 'following_id')->withTimestamps();
    }

    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function commentVotes()
    {
        return $this->hasMany(CommentVote::class);
    }

    public static function generateUniqueSlug(string $name, ?int $ignoreUserId = null): string
    {
        $base = Str::slug($name);
        $base = $base !== '' ? $base : 'researcher';
        $slug = $base;
        $counter = 2;

        while (
            static::query()
                ->when($ignoreUserId, fn ($query) => $query->whereKeyNot($ignoreUserId))
                ->where('public_slug', $slug)
                ->exists()
        ) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        if ($this->minecraft_username) {
            return 'https://mc-heads.net/avatar/' . $this->minecraft_username . '/100';
        }

        return null;
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
                    ->withPivot('unlocked_at')
                    ->withTimestamps();
    }

    public function mobContributions()
    {
        return $this->hasMany(MobContribution::class);
    }

    public function addXp($amount)
    {
        $this->xp += $amount;
        
        $xpRemaining = $this->xp;
        $level = 1;
        
        // Level 1 to 15 (requires 2000 each)
        while ($xpRemaining >= 2000 && $level < 15) {
            $xpRemaining -= 2000;
            $level++;
        }
        
        // Level 16 to 30 (requires 1850 each)
        if ($level >= 15) {
            while ($xpRemaining >= 1850 && $level < 30) {
                $xpRemaining -= 1850;
                $level++;
            }
        }
        
        // Level 30+ (requires 2000 each)
        if ($level >= 30) {
            while ($xpRemaining >= 2000) {
                $xpRemaining -= 2000;
                $level++;
            }
        }
        
        $this->level = $level;
        $this->save();
        
        return $this->level;
    }

    public function xpForNextLevel()
    {
        if ($this->level < 15) return 2000;
        if ($this->level < 30) return 1850;
        return 2000;
    }
    
    public function currentLevelProgress()
    {
        $xpRemaining = $this->xp;
        $level = 1;
        
        while ($xpRemaining >= 2000 && $level < 15) {
            $xpRemaining -= 2000;
            $level++;
        }
        if ($level >= 15) {
            while ($xpRemaining >= 1850 && $level < 30) {
                $xpRemaining -= 1850;
                $level++;
            }
        }
        if ($level >= 30) {
            while ($xpRemaining >= 2000) {
                $xpRemaining -= 2000;
                $level++;
            }
        }
        
        $required = $this->xpForNextLevel();
        return min(100, round(($xpRemaining / $required) * 100));
    }
}
