<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class JobPhotoResource extends Resource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'url' => $this->url
        ];
    }

}
