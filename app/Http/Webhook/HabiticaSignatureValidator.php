<?php

namespace App\Http\Webhook;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class HabiticaSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $userId = $request->string('user._id');

        return User::where('habitica_user_id', $userId)->exists();
    }
}
