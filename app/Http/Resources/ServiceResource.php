<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ServiceResource extends Resource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'package' => new PackageResource($this->whenLoaded('package')),
            'name' => $this->name,
            'price' => $this->price,
            'image' => $this->image,
            'active' => $this->active,
            'included' => $this->included
        ];
    }

}
