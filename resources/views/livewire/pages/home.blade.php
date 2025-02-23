<?php

use Illuminate\Support\Carbon;

?>

<div>
    <x-header>
        <x-slot name="title" class="text-xl">Home: Upperate Crypto Exchange</x-slot>
        <x-slot name="actions">
            <livewire:digital-clock />
        </x-slot>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$exchanges" no-headers expandable show-empty-text>
            @scope('cell_exchange', $exchanges)
            <div class="flex items-center space-x-2 capitalize">
                <livewire:exchange-logo :exchange="$exchanges['exchange']" />
                <div>{{ $exchanges['exchange'] }}</div>
            </div>
            @endscope

            @scope('expansion', $exchange, $subHeaders)
            <div class="bg-base-200 p-8 font-bold">
                <x-table :headers="$subHeaders" :rows="$exchange['prices']" show-empty-text>
                    @scope('cell_average_price', $price)
                    <div class="flex items-center">
                        @if ($price['change_direction'] === 'up')
                        <img alt="logo" src="{{ asset('assets/icons/up.svg') }}" class="h-4">
                        @else
                        <img alt="logo" src="{{ asset('assets/icons/down.svg') }}" class="h-4">
                        @endif

                        {{ number_format($price['average_price'], 2, '.', ',') }}
                    </div>
                    @endscope

                    @scope('cell_updated_at', $price)
                    {{ Carbon::parse($price['updated_at'])->diffForHumans() }}
                    @endscope

                </x-table>
            </div>
            @endscope

        </x-table>
    </x-card>
</div>
