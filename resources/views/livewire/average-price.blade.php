<div class="flex items-center">
    @if ($price['change_direction'] === 'up')
    <img alt="logo" src="{{ asset('assets/icons/up.svg') }}" class="h-4">
    @else
    <img alt="logo" src="{{ asset('assets/icons/down.svg') }}" class="h-4">
    @endif

    {{ number_format($price['average_price'], 2, '.', ',') }}
</div>
