<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasHabitica;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, HasHabitica, InteractsWithMedia, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'habitica_user_id',
        'habitica_api_token',
        'openai_api_key',
        'party_id',
        'hp',
        'exp',
        'lvl',
        'to_next_level',
        'max_health',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'habitica_api_token',
        'openai_api_key',
    ];

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
            'habitica_api_token' => 'encrypted',
            'openai_api_key' => 'encrypted',
        ];
    }

    /**
     * Get the quest associated with the user.
     */
    public function quest(): HasOne
    {
        return $this->hasOne(Quest::class);
    }

    /**
     * Register user's media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('art')->onlyKeepLatest(3);
    }
}
