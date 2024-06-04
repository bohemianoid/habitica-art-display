<?php

namespace App\Http\Webhook;

use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;

class HabiticaWebhookProfile implements WebhookProfile
{
    public function shouldProcess(Request $request): bool
    {
        return $request->string('webhookType')->contains('taskActivity');
    }
}
