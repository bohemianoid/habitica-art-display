<?php

use App\Jobs\ProcessRemainingDailys;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use function Livewire\Volt\on;
use function Livewire\Volt\state;

state([
    'id' => fn () => auth()->user()->id,
    'url' => fn () => optional(
        auth()->user()->getMedia('art')->last()
    )->getUrl(),
]);

$loadArtwork = function () {
    $user = Auth::user();

    ProcessRemainingDailys::dispatch($user);
};

on(['echo-private:App.Models.User.{id},ArtworkUpdated' => function ($event) {
    $artwork = Media::findByUuid($event['artwork']['uuid']);

    $this->url = $artwork->getUrl();
}]);

?>

<div
    class="flex h-dvh w-full items-center justify-center"
    wire:init="loadArtwork"
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
