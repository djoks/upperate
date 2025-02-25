<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CryptoExchangeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'exchange' => ucfirst($this->resource['exchange']),
            'prices' => CryptoPriceResource::collection($this->resource['prices'])
        ];
    }
}
