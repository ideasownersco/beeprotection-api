<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CategoryResource extends Resource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'active' => $this->active,
            'order' => $this->order,
            'packages' => PackageResource::collection($this->whenLoaded('packages'))
        ];
    }

}
