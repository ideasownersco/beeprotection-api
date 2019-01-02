<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
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
          'name' => $this->name,
          'mobile' => $this->mobile,
          'email'  => $this->email,
          'image' => $this->image,
          'active' => $this->active,
          'type'   => $this->type,
          'profile' => $this->when($this->type === 10, function () {
              return new DriverResource($this->driver);
          }),
          'addresses' => AddressResource::collection($this->whenLoaded('addresses'))
        ];
    }
}
