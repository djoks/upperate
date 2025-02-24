<div>
    @if (in_array($exchange, ['binance', 'mexc', 'huobi']))
        <img src="{{ asset("assets/logos/{$exchange}.png") }}" alt="{{ ucfirst($exchange) }} Logo"
            class="w-8 h-8 object-fit" />
    @else
        <!-- Placeholder: a simple circle with a question mark -->
        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
            <span class="text-gray-600">?</span>
        </div>
    @endif
</div>
