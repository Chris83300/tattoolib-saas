<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PierceurResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'specialization' => $this->specialization,
            'specialization_label' => $this->specialization_label,
            'bio' => $this->bio,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
            'email' => $this->email,
            'instagram' => $this->instagram,
            'facebook' => $this->facebook,
            'tiktok' => $this->tiktok,
            'website' => $this->website,
            'avatar_url' => $this->user->avatar_url ?? null,
            'studio_name' => $this->studio_name,
            'studio' => $this->when($this->studio_id, function () {
                return [
                    'id' => $this->studio->id,
                    'name' => $this->studio->name,
                    'slug' => $this->studio->slug,
                ];
            }),
            'verified' => $this->siret_verified,
            'is_pro' => $this->isPro(),
            'is_pierceur' => $this->isPierceur(),
            'is_bodemodeur' => $this->isBodemodeur(),
            'portfolio_count' => $this->getMedia('portfolio')->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
