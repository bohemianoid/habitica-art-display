<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

use function Livewire\Volt\rules;
use function Livewire\Volt\state;

state([
    "openai_api_key" => fn() => auth()->user()->openai_api_key,
]);

rules([
    "openai_api_key" => ["required", "string"],
]);

$updateOpenAiInformation = function () {
    $user = Auth::user();

    $validated = $this->validate();

    $user->fill($validated);

    $user->save();

    $this->dispatch("openai-updated", name: $user->name);
};
?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('OpenAI Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's OpenAI information") }}
        </p>
    </header>

    <form wire:submit="updateOpenAiInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="openai_api_key" :value="__('Open AI API Key')" />
            <x-text-input wire:model="openai_api_key" id="openai_api_key" name="openai_api_key" type="password" class="mt-1 block w-full" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('openai_api_key')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="openai-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
