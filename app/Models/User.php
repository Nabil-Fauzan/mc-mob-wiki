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
    use HasFactory, Notifiable, \App\Models\Traits\HasExperience, \App\Models\Traits\Sluggable;

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

    // Scopes
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    public function scopePublicProfiles($query)
    {
        return $query->where('profile_is_public', true);
    }

    // Relationships
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

    public function mobRevisions()
    {
        return $this->hasMany(MobRevision::class);
    }

    public function adminContributions()
    {
        return $this->hasMany(MobContribution::class, 'admin_id');
    }
}
