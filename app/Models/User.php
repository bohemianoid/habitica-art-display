<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Http;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, InteractsWithMedia, Notifiable;

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
     * Get the user's stats.
     */
    protected function stats(): Attribute
    {
        return Attribute::make(
            get: fn () => Http::habitica()
                ->withHeaders([
                    'X-API-User' => $this->habitica_user_id,
                    'X-API-Key' => $this->habitica_api_token,
                ])
                ->get('/user/anonymized')
                ->throw()
                ->collect('data.user.stats')
        );
    }

    /**
     * Get the user's due dailys.
     */
    protected function dailys(): Attribute
    {
        return Attribute::make(
            get: fn () => Http::habitica()
                ->withHeaders([
                    'X-API-User' => $this->habitica_user_id,
                    'X-API-Key' => $this->habitica_api_token,
                ])
                ->get('/tasks/user?type=dailys')
                ->throw()
                ->collect('data')
                ->filter(fn (array $daily) => $daily['isDue'])
        );
    }

    /**
     * Register user's media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('art')->onlyKeepLatest(3);
    }
}
