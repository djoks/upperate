<div wire:ignore class="flex items-center" x-data="ChangeCounter('{{ $id }}', {{ $change }})"
    x-on:change-updated-{{ $id }}="updateChange($event.detail[0].newChange)">
    <span x-ref="counter">0</span>
</div>

@script
    <script>
        Alpine.data('ChangeCounter', (id, value) => {
            return {
                id: id,
                change: value,
                countUp: null,
                init() {
                    this.countUp = new CountUp(
                        this.$refs.counter, this.change, {
                            startVal: 0,
                            decimalPlaces: 2,
                            duration: 1
                        }
                    );
                    this.countUp.start();
                },
                updateChange(val) {
                    let newChange = val || 0;
                    this.countUp.update(newChange);
                }
            }
        })
    </script>
@endscript
