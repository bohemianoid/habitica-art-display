<?php

use App\Jobs\ProcessRemainingDailys;
use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\state;

state([
    'url' => fn () => optional(
        auth()->user()->getMedia('art')->last()
    )->getUrl(),
]);

$loadArtwork = function () {
    $user = Auth::user();

    ProcessRemainingDailys::dispatch($user);
};

$refreshArtwork = function () {
    $user = Auth::user();

    $this->url = optional($user->getMedia('art')->last())->getUrl();
};
?>

<div
    class="flex h-dvh w-full items-center justify-center"
    wire:init="loadArtwork"
    wire:poll="refreshArtwork"
>
    @if ($this->url)
        <img
            class="size-full object-cover"
            src="{{ $this->url }}"
        />
    @else
        <div class="size-7 animate-spin rounded-full border-4 border-b-gray-700 border-l-gray-700 border-r-gray-700 border-t-gray-200"></div>
    @endif
</div>
