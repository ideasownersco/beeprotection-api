<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AddressResource extends Resource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'area' => new AreaResource($this->whenLoaded('area')),
            'latitude' => (float)$this->latitude,
            'longitude' => (float)$this->longitude,
            'block' => $this->block,
            'street' => $this->street,
            'avenue' => $this->avenue,
            'building' => $this->building,
        ];
    }

}
