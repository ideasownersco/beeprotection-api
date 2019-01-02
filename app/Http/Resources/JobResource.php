<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class JobResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'driver' => new DriverResource($this->whenLoaded('driver')),
            'photos' => JobPhotoResource::collection($this->whenLoaded('photos')),
            'started_working_at' => $this->started_working_at,
            'ended_working_at' => $this->ended_working_at,
            'started_driving_at' => $this->started_driving_at,
            'ended_driving_at' => $this->ended_driving_at,
            'photos_approved' => $this->photos_approved,
            'status' => $this->status,
            'cancelled' => $this->cancelled
        ];
    }
}
