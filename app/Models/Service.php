<?php

namespace App\Models;

class Service extends BaseModel
{
    protected $table = 'services';
    protected $guarded = ['id'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class,'order_services');
    }

    public function getImageAttribute()
    {
        return $this->getImage($this->attributes['image']);
    }

}
