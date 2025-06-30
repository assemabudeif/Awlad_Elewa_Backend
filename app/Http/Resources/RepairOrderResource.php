<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepairOrderResource extends JsonResource
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
            'user' => $this->user,
            'description' => $this->description,
            'photo' => $this->photo,
            'audio' => $this->audio,
            'phone1' => $this->phone1,
            'phone2' => $this->phone2,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
