<div>
    <x-header>
        <x-slot name="title" class="text-xl ">Home: Upperate Crypto Exchange</x-slot>
        <x-slot name="actions">
            <livewire:digital-clock />
        </x-slot>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$headers" :rows="$exchanges" expandable no-headers show-empty-text>

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
                    <livewire:price-change :change="$price['price_change']" :key="uniqid()" />

                    @endscope

                    @scope('cell_updated_at', $price)
                    <livewire:time-ago :date="$price['updated_at']" :key="uniqid()" />
                    @endscope
                </x-table>
            </div>
            @endscope

        </x-table>
    </x-card>
</div>
