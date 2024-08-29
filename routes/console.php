<?php

use Illuminate\Support\Facades\Schedule;
use Spatie\WebhookClient\Models\WebhookCall;

/**
 * Run the queue worker.
 */
Schedule::command('queue:work --max-time=59')
    ->everyMinute()
    ->withoutOverlapping()
    ->sentryMonitor();

/**
 * Delete old webhook calls.
 */
Schedule::command('model:prune', [
    '--model' => [WebhookCall::class],
])
    ->daily()
    ->sentryMonitor();
