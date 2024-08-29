<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

trait HasHabitica
{
    /**
     * Get the Habitica client.
     */
    public function client(): PendingRequest
    {
        return Http::habitica()
            ->withHeaders([
                'X-API-User' => $this->habitica_user_id,
                'X-API-Key' => $this->habitica_api_token,
            ]);
    }

    /**
     * Get the anonymized Habitica user.
     */
    protected function anonymized(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->client()
                ->get('/user/anonymized')
                ->throw()
                ->collect('data.user')
        )->shouldCache();
    }

    /**
     * Get the user's due dailys.
     */
    protected function dailys(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->client()
                ->get('/tasks/user?type=dailys')
                ->throw()
                ->collect('data')
                ->filter(fn (array $daily) => $daily['isDue'])
        )->shouldCache();
    }

    /**
     * Get the user's habits.
     */
    protected function habits(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->client()
                ->get('/tasks/user?type=habits')
                ->throw()
                ->collect('data')
        )->shouldCache();
    }

    /**
     * Get the user's party.
     */
    protected function party(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->client()
                ->get("/groups/{$this->party_id}")
                ->throw()
                ->collect('data')
        )->shouldCache();
    }
}
