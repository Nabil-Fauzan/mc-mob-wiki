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

#[Fillable(['name', 'email', 'password', 'is_admin', 'avatar', 'minecraft_username', 'public_slug', 'profile_is_public'])]
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

    public function comments()
    {
        return $this->hasMany(Comment::class);
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
            return 'https://minotar.net/helm/' . $this->minecraft_username . '/256.png';
        }

        return null;
    }
}
