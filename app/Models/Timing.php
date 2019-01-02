<?php

namespace App\Models;

use Carbon\Carbon;

class Timing extends BaseModel
{
    protected $table = 'timings';
    protected $guarded = ['id'];
    protected $appends = ['name','name_short','period'];

    public function getNameAttribute()
    {
        return Carbon::parse($this->name_en)->format('g:ia');
    }

    public function getNameShortAttribute()
    {
        $name = Carbon::parse($this->name_en)->format('g:i');
//        return substr($name,-3);
        return substr($name,-3) == ':00' ? substr($name,0,-3) : $name;
    }

    public function getPeriodAttribute()
    {
        return strtoupper(Carbon::parse($this->name_en)->format('a'));
    }
}
