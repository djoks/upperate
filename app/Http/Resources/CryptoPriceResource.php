<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CryptoPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'pair' => $this->resource['pair'],
            'exchange' => ucfirst($this->resource['exchange']),
            'average_price' => number_format($this->resource['average_price'], 2, '.', ','),
            'price_change' => number_format($this->resource['price_change'], 8, '.', ','),
            'change_direction' => $this->resource['change_direction'],
            'created_at' => Carbon::parse($this->resource['created_at'])->toDateTimeString(),
            'updated_at' => Carbon::parse($this->resource['updated_at'])->toDateTimeString()
        ];
    }
}
