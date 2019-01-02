<?php

namespace App\Models;

class Vehicle extends BaseModel
{
    protected $table = 'vehicles';
    protected $guarded = ['id'];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

}
