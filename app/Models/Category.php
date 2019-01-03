<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $table = 'categories';
    protected $guarded = ['id'];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function getImageAttribute()
    {
        return $this->getImage($this->attributes['image']);
    }

}
