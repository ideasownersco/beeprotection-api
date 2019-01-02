<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoriesCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'success' => true,
            'data'    => $this->collection,
        ];
    }

}
