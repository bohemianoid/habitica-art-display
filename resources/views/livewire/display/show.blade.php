<?php

use App\Jobs\ProcessQuest;
use App\Jobs\ProcessRemainingDailys;
use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\layout;

layout('layouts.app');

$init = function () {
    $user = Auth::user();

    $party = $user->anonymized->get('party');
    $stats = $user->anonymized->get('stats');

    $user->fill([
        'party_id' => $party['_id'],
        'hp' => $stats['hp'],
        'exp' => $stats['exp'],
        'lvl' => $stats['lvl'],
        'to_next_level' => $stats['toNextLevel'],
        'max_health' => $stats['maxHealth'],
    ]);

    $user->save();

    ProcessRemainingDailys::dispatch($user);
    ProcessQuest::dispatch($user, $party['quest']['key']);
};

?>

<div wire:init="init">
    <livewire:display.artwork />
    <livewire:display.quest />
    <livewire:display.stats />
</div>
