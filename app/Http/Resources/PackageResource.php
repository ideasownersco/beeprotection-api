<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PackageResource extends Resource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'name' => $this->name,
            'price' =>$this->price,
            'image' => $this->image,
            'description' => $this->description,
            'active' => $this->active,
            'order' => $this->order,
            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'show_quantity' => $this->show_quantity
        ];
    }

}
