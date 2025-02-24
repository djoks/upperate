<div wire:poll.1000ms="updateTime" x-data="{
         currentIso: @entangle('currentTime'),
         localHours: '00',
         localMinutes: '00',
         localSeconds: '00',
         updateClock() {
             const d = new Date(this.currentIso);
             const formatter = new Intl.DateTimeFormat(undefined, {
                 hour: '2-digit',
                 minute: '2-digit',
                 second: '2-digit'
             });
             const parts = formatter.formatToParts(d);
             this.localHours = parts.find(part => part.type === 'hour')?.value || '00';
             this.localMinutes = parts.find(part => part.type === 'minute')?.value || '00';
             this.localSeconds = parts.find(part => part.type === 'second')?.value || '00';
         }
     }" x-init="
         updateClock();
         $watch('currentIso', () => { updateClock(); });
         setInterval(() => { updateClock(); }, 1000);
     " class="flex">
    <x-card class="w-48 self-center rounded-box bg-transparent items-center justify-center">
        <div class="flex justify-center items-center gap-2">
            <div class="relative overflow-hidden bg-black/50 rounded-lg p-4 w-[50px] text-base font-bold hover:scale-105 transition-transform duration-200 ease-out" id="hours" x-text="localHours">
                <div class="absolute bottom-[-25px] left-0 w-full text-base text-[0.8rem] uppercase">Hours</div>
            </div>
            :
            <div class="relative overflow-hidden bg-black/50 rounded-lg p-4 w-[50px] text-base font-bold hover:scale-105 transition-transform duration-200 ease-out" id="hours" x-text="localMinutes">
                <div class="absolute bottom-[-25px] left-0 w-full text-base text-[0.8rem] uppercase">Minutes</div>
            </div>
            :
            <div class="relative overflow-hidden bg-black/50 rounded-lg p-4 w-[50px] text-base font-bold hover:scale-105 transition-transform duration-200 ease-out" id="hours" x-text="localSeconds">

                <div class="absolute bottom-[-25px] left-0 w-full text-base text-[0.8rem] uppercase">Seconds</div>
            </div>
        </div>
    </x-card>
</div>
