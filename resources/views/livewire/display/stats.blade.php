<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

use function Livewire\Volt\computed;
use function Livewire\Volt\state;

state([
    'hp' => fn () => auth()->user()->hp,
    'exp' => fn () => auth()->user()->exp,
    'lvl' => fn () => auth()->user()->lvl,
    'to_next_level' => fn () => auth()->user()->to_next_level,
    'max_health' => fn () => auth()->user()->max_health,
]);

$loadStats = function () {
    $user = Auth::user();

    $stats = $user->stats;

    $user->fill([
        'hp' => $stats->get('hp'),
        'exp' => $stats->get('exp'),
        'lvl' => $stats->get('lvl'),
        'to_next_level' => $stats->get('toNextLevel'),
        'max_health' => $stats->get('maxHealth'),
    ]);

    $user->save();
};

$refreshStats = function () {
    $user = Auth::user();

    if ($user->exp > $this->exp || $user->lvl > $this->lvl) {
        $this->dispatch('experience-gained');
    }

    $this->hp = $user->hp;
    $this->exp = $user->exp;
    $this->lvl = $user->lvl;
    $this->to_next_level = $user->to_next_level;
    $this->max_health = $user->max_health;
};

$hpProgress = computed(
    fn () => $this->max_health ? ($this->hp / $this->max_health) * 100 : 0
);

$expProgress = computed(
    fn () => $this->to_next_level ? ($this->exp / $this->to_next_level) * 100 : 0
);
?>

<div
    class="absolute bottom-8 right-8"
    wire:init="loadStats"
    wire:poll="refreshStats"
>
    <div class="grid grid-cols-[1fr_4fr]">
        <div class="col-start-2 border-r-8 border-transparent text-center">
            @isset($this->lvl)
                {{-- format-ignore-start --}}
                    <span class="text-sm">L</span>{{ $this->lvl }}
                {{-- format-ignore-end --}}
            @endisset
        </div>
        <div class="rounded-tl border-b-2 border-gray-100 bg-gray-100 pl-1.5 pt-0.5 text-xs text-yellow-800">HP:</div>
        <div class="flex h-full items-end rounded-tr-lg border-b-2 border-r-8 border-gray-100 pb-1">
            <div class="h-2 w-full">
                <div
                    @class([
                        'h-full',
                        'bg-red-600' => $this->hpProgress <= 20,
                        'bg-yellow-600' => $this->hpProgress > 20 && $this->hpProgress <= 50,
                        'bg-green-600' => $this->hpProgress > 50,
                    ])
                    style="width: {{ $this->hpProgress }}%"
                ></div>
            </div>
        </div>
        <div class="col-start-2 border-r-8 border-gray-100 pb-2 pt-0.5 text-center">
            @if ($this->hp && $this->max_health)
                {{ floor($this->hp) }}/{{ $this->max_health }}
            @else
                &nbsp;
            @endif
        </div>
        <div class="col-span-2 rounded-bl rounded-br-lg border-x-8 border-b-2 border-gray-100 pb-1">
            <div class="h-2">
                <div
                    class="ml-auto h-full bg-blue-600"
                    style="width: {{ $this->expProgress }}%"
                ></div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        $wire.on('experience-gained', () => {
            window.jsConfetti.addConfetti();
        });
    </script>
@endscript
