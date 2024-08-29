<?php

namespace App\Jobs;

use App\Models\Quest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessQuest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user, public string $questKey)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $hasQuest = $this->user->quest()->exists();

        if (is_null($this->questKey)) {
            if ($hasQuest) {
                $this->user->quest()->delete();
            }

            return;
        }

        $questStatus = $this->user->party->get('quest');

        if ($hasQuest) {
            $quest = $this->user->quest;
        } else {
            $quest = new Quest();
        }

        if ($questStatus['key'] !== $quest->key) {
            $questContent = Http::habitica()
                ->get('/content')
                ->throw()
                ->collect("data.quests.{$questStatus['key']}");

            $quest->boss_name = $questContent['boss']['name'];
            $quest->boss_max_health = $questContent['boss']['hp'];
        }

        $quest->key = $questStatus['key'];
        $quest->boss_hp = $questStatus['progress']['hp'];
        $quest->active = $questStatus['active'];

        $this->user->quest()->save($quest);
    }
}
