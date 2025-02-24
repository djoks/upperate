<div wire:poll.1000ms="updateTime" x-data="{ 
        currentIso: @entangle('currentTime'),
        localTime: new Date(@entangle('currentTime')).toLocaleTimeString()
    }" x-init="
        // Update localTime when currentIso changes
        $watch('currentIso', (value) => { 
            localTime = new Date(value).toLocaleTimeString(); 
        });
        // Update localTime every second for smooth transitions
        setInterval(() => { 
            localTime = new Date(currentIso).toLocaleTimeString(); 
        }, 1000);
    " class="p-4">

    <x-card class="w-48 rounded-box items-center justify-center">
        <div class="flex space-x-2 items-center text-center">
            <img src="{{ asset('assets/icons/clock.svg') }}" alt="Clock" class="w-5 h-5 opacity-60">
            <h2 class="text-xl" x-text="localTime"></h2>
        </div>
    </x-card>
</div>
