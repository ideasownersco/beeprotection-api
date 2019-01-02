<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AreaResource extends Resource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'active' => $this->active,
            'latitude' => (float)$this->latitude,
            'longitude' => (float)$this->longitude,
        ];
    }

}
