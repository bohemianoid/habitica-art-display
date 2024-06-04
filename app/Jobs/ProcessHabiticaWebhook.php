<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Arr;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class ProcessHabiticaWebhook extends ProcessWebhookJob
{
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::where(
            'habitica_user_id',
            Arr::get($this->webhookCall->payload, 'user._id')
        )->firstOrFail();

        $user->fill([
            'hp' => Arr::get($this->webhookCall->payload, 'user.stats.hp'),
            'exp' => Arr::get($this->webhookCall->payload, 'user.stats.exp'),
            'lvl' => Arr::get($this->webhookCall->payload, 'user.stats.lvl'),
            'to_next_level' => Arr::get(
                $this->webhookCall->payload,
                'user.stats.toNextLevel'
            ),
            'max_health' => Arr::get(
                $this->webhookCall->payload,
                'user.stats.maxHealth'
            ),
        ]);

        $user->save();

        ProcessRemainingDailys::dispatch($user);
    }
}
