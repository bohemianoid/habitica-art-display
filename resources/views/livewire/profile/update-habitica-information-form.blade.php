<?php

use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\rules;
use function Livewire\Volt\state;

state([
    "habitica_user_id" => fn() => auth()->user()->habitica_user_id,
    "habitica_api_token" => fn() => auth()->user()->habitica_api_token,
]);

rules([
    "habitica_user_id" => ["required", "string", "uuid"],
    "habitica_api_token" => ["required", "string"],
]);

$updateHabiticaInformation = function () {
    $user = Auth::user();

    $validated = $this->validate();

    $user->fill($validated);

    $user->save();

    $this->dispatch("habitica-updated", name: $user->name);
};
?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Habitica Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's Habitica information.") }}
        </p>
    </header>

    <form wire:submit="updateHabiticaInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="habitica_user_id" :value="__('Habitica User ID')" />
            <x-text-input wire:model="habitica_user_id" id="habitica_user_id" name="habitica_user_id" type="text" class="mt-1 block w-full" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('habitica_user_id')" />
        </div>

        <div>
            <x-input-label for="habitica_api_token" :value="__('Habitica API Token')" />
            <x-text-input wire:model="habitica_api_token" id="habitica_api_token" name="habitica_api_token" type="password" class="mt-1 block w-full" required />
            <x-input-error class="mt-2" :messages="$errors->get('habitica_api_token')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="habitica-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
