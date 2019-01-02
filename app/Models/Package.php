<?php

namespace App\Models;

class Package extends BaseModel
{
    protected $table = 'packages';
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class,'order_packages');
    }

}
