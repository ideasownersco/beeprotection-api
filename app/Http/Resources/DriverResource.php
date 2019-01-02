<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DriverResource extends Resource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'schema'        => 'drivers',
            'user' => new UserResource($this->whenLoaded('user')),
            'online' => (bool)!$this->offline,
            'active' => $this->active,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'latitude' => (float)$this->latitude,
            'longitude' => (float)$this->longitude,
        ];
    }

}
