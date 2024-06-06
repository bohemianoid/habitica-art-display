<?php

use Illuminate\Support\Facades\Schedule;
use Spatie\WebhookClient\Models\WebhookCall;

/**
 * Run the queue worker.
 */
Schedule::command('queue:work --stop-when-empty')
    ->everyMinute()
    ->withoutOverlapping();

/**
 * Delete old webhook calls.
 */
Schedule::command('model:prune', [
    '--model' => [WebhookCall::class],
])->daily();
