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
            'photo' => $this->photo_url,
            'audio' => $this->audio_url,
            'phone1' => $this->phone1,
            'phone2' => $this->phone2,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'status_color' => $this->status_color,
            'estimated_cost' => $this->estimated_cost,
            'final_cost' => $this->final_cost,
            'notes' => $this->notes,
            'can_be_edited' => $this->canBeEdited(),
            'can_be_cancelled' => $this->canBeCancelled(),
            'has_photo' => $this->hasPhoto(),
            'has_audio' => $this->hasAudio(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
