<div wire:ignore x-data="DigitalClock()" class="flex">
    <x-card class="w-48 self-center rounded-box bg-transparent items-center justify-center">
        <div class="flex justify-center items-center gap-2">
            <div class="relative overflow-hidden bg-black/50 rounded-lg p-4 w-[50px] text-base font-bold hover:scale-105 transition-transform duration-200 ease-out"
                id="hours" x-text="localHours">
                <div class="absolute bottom-[-25px] left-0 w-full text-base text-[0.8rem] uppercase">Hours</div>
            </div>
            :
            <div class="relative overflow-hidden bg-black/50 rounded-lg p-4 w-[50px] text-base font-bold hover:scale-105 transition-transform duration-200 ease-out"
                id="hours" x-text="localMinutes">
                <div class="absolute bottom-[-25px] left-0 w-full text-base text-[0.8rem] uppercase">Minutes</div>
            </div>
            :
            <div class="relative overflow-hidden bg-black/50 rounded-lg p-4 w-[50px] text-base font-bold hover:scale-105 transition-transform duration-200 ease-out"
                id="hours" x-text="localSeconds">
                <div class="absolute bottom-[-25px] left-0 w-full text-base text-[0.8rem] uppercase">Seconds</div>
            </div>
        </div>
    </x-card>
</div>

@script
    <script>
        Alpine.data('DigitalClock', () => {
            return {
                localHours: '00',
                localMinutes: '00',
                localSeconds: '00',
                init() {
                    this.updateClock();

                    setInterval(() => {
                        this.updateClock();
                    }, 1000);
                },
                updateClock() {
                    const newDate = new Date();
                    this.localHours = newDate.getHours().toString().padStart(2, '0');
                    this.localMinutes = newDate.getMinutes().toString().padStart(2, '0');
                    this.localSeconds = newDate.getSeconds().toString().padStart(2, '0');
                }
            }
        })
    </script>
@endscript
