<div>
    <x-header class="flex-col justify-center items-center w-full md:flex-row">
        <x-slot name="title" class="text-xl font-regular">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('assets/logos/app.png') }}" alt="Upperate Logo" class="h-10 w-10">
                <div class="flex flex-col">
                    <span>Upperate Crypto Exchange</span>
                    <span class="text-xs font-light text-green-600 dark:text-amber-500">Currently viewing ({{ count($exchanges) }}) Exchanges</span>
                </div>
            </div>
        </x-slot>
        <x-slot name="actions">
            <livewire:digital-clock />
        </x-slot>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$exchanges" wire:model="expanded" expandable expandable-key="exchange" no-headers show-empty-text no-hover :key="uniqid()">
            @scope('cell_exchange', $exchanges)
            <div class="flex items-center space-x-2 capitalize">
                <livewire:exchange-logo :exchange="$exchanges['exchange']" />
                <div>{{ $exchanges['exchange'] }}</div>
            </div>
            @endscope

            @scope('expansion', $exchange, $subHeaders)
            <div class="bg-base-200 p-8">
                <x-table :headers="$subHeaders" :rows="$exchange['prices']" show-empty-text :key="uniqid()">
                    @scope('cell_average_price', $price)
                    <livewire:average-price :price="$price" :key="uniqid()" />
                    @endscope
                    @scope('cell_price_change', $price)
                    <livewire:price-change :price="$price" :key="uniqid()" />
                    @endscope

                    @scope('cell_updated_at', $price)
                    <livewire:date-changed :price="$price" :key="uniqid()" />
                    @endscope
                </x-table>
            </div>
            @endscope

        </x-table>
    </x-card>

    <div>
        <div class="text-center text-xs py-5">This is project was built by Philip.</div>
    </div>
</div>
