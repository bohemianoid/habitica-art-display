<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessRemainingDailys implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $daily = $this->user->dailys
            ->filter(fn (array $daily) => ! $daily['completed'])
            ->first();

        $dailyId = $daily['_id'] ?? null;

        $currentTaskId = optional(
            $this->user->getMedia('art')->last()
        )->getCustomProperty('taskId');

        if ($dailyId == $currentTaskId) {
            return;
        }

        $title = $daily['text'] ?? 'All done today!';

        $images = Http::openai()
            ->withToken($this->user->openai_api_key)
            ->post('/images/generations', [
                'prompt' => "digital illustration suitable as wallpaper, digital art, trending on ArtStation, full size, borderless, with the theme \"{$title}\"",
                'model' => 'dall-e-3',
                'size' => '1792x1024',
            ])
            ->collect('data');

        $this->user
            ->addMediaFromUrl($images->first()['url'])
            ->withCustomProperties([
                'taskId' => $dailyId,
                'taskTitle' => $title,
            ])
            ->toMediaCollection('art');
    }
}
