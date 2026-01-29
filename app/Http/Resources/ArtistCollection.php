<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArtistCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => ArtistResource::collection($this->collection),
            'meta' => [
                'count' => $this->collection->count(),
                'total' => $this->collection->count(), // Pour les collections simples
            ],
        ];
    }
}
