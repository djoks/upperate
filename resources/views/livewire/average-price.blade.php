<div wire:ignore class="flex items-center" x-data="PriceCounter('{{ $id }}', {{ $price['average_price'] }})"
    x-on:price-updated-{{ $id }}="updatePrice($event.detail[0].newPrice)">
    @if ($price['change_direction'] === 'upward')
        <img alt="logo" src="{{ asset('assets/icons/up.svg') }}"
            class="h-4 transition-opacity duration-300 animate-pulse">
    @else
        <img alt="logo" src="{{ asset('assets/icons/down.svg') }}"
            class="h-4 transition-opacity duration-300 animate-pulse">
    @endif

    <span x-ref="counter">0</span>
</div>

@script
    <script>
        Alpine.data('PriceCounter', (id, value) => {
            return {
                id: id,
                price: value,
                countUp: null,
                init() {
                    this.countUp = new CountUp(
                        this.$refs.counter, this.price, {
                            startVal: 0,
                            decimalPlaces: 2,
                            duration: 1
                        }
                    );
                    this.countUp.start();
                },
                updatePrice(val) {
                    let newPrice = val || 0;
                    this.countUp.update(newPrice);
                }
            }
        })
    </script>
@endscript
