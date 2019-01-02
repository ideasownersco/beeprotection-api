<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class TimingResource extends Resource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_short' => $this->name_short,
            'period' => $this->period,
            'disabled' => (bool)rand(0,1),
//            'isDay' => Carbon::parse($this->name_en)->format('H') < 12,
            'isDay' => true,
        ];
    }

}
