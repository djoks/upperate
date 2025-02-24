<div>
    <x-header>
        <x-slot name="title" class="text-xl ">Home: Upperate Crypto Exchange</x-slot>
        <x-slot name="actions">
            <livewire:digital-clock />
        </x-slot>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$exchanges" wire:model="expanded" expandable no-headers show-empty-text>

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
</div>
