<?php

use Illuminate\Support\Facades\Auth;

use function Livewire\Volt\computed;
use function Livewire\Volt\state;

state([
    'boss_name' => fn () => optional(auth()->user()->quest)->boss_name,
    'boss_hp' => fn () => optional(auth()->user()->quest)->boss_hp,
    'boss_max_health' => fn () => optional(auth()->user()->quest)->boss_max_health,
    'active' => fn () => optional(auth()->user()->quest)->active,
]);

$refreshQuest = function () {
    $user = Auth::user();

    if (! $user->quest) {
        return;
    }

    if ($user->quest->boss_hp < $this->boss_hp) {
        $this->dispatch('boss-damaged');
    }

    $this->boss_name = $user->quest->boss_name;
    $this->boss_hp = $user->quest->boss_hp;
    $this->boss_max_health = $user->quest->boss_max_health;
    $this->active = $user->quest->active;
};

$hpProgress = computed(
    fn () => $this->boss_max_health ? ($this->boss_hp / $this->boss_max_health) * 100 : 0
);

?>

<div
    class="absolute left-8 top-8"
    wire:poll="refreshQuest"
>
    @if ($this->active)
        <div class="grid grid-cols-[1fr_4fr]">
            <div class="col-span-2 mb-1.5 border-r-8 border-transparent text-sm uppercase">
                @isset($this->boss_name)
                    {{ $this->boss_name }}
                @endisset
            </div>
            <div class="rounded-tl border-b-2 border-gray-100 bg-gray-100 pl-1.5 pt-0.5 text-xs text-yellow-800">HP:</div>
            <div class="flex h-full items-end rounded-tr-lg border-b-2 border-r-8 border-gray-100 pb-1">
                <div class="h-2 w-full">
                    <div
                        @class(['h-full', 'bg-red-600' => $this->hpProgress <= 20, 'bg-yellow-600' => $this->hpProgress > 20 && $this->hpProgress <= 50, 'bg-green-600' => $this->hpProgress > 50])
                        style="width: {{ $this->hpProgress }}%"
                    ></div>
                </div>
            </div>
            <div class="col-start-2 border-r-8 border-gray-100 pb-2 pt-0.5 text-center">
                @if ($this->boss_hp && $this->boss_max_health)
                    {{ floor($this->boss_hp) }}/{{ $this->boss_max_health }}
                @else
                    &nbsp;
                @endif
            </div>
            <div class="col-span-2 rounded-bl rounded-br-lg border-x-8 border-b-2 border-gray-100 pb-1">
                <div class="h-2"></div>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        $wire.on('boss-damaged', () => {
            window.jsConfetti.addConfetti();
        });
    </script>
@endscript
