<?php

namespace App\Models;

class Area extends BaseModel
{

    protected $table= 'areas';
    protected $guarded = ['id'];

    public function children()
    {
        $sortOrder = session()->has('locale') && session()->get('locale')  == 'ar' ? 'name_ar' : 'name_en';
        return $this->hasMany(Area::class,'parent_id','id')
            ->orderBy($sortOrder,'ASC');
    }

    public function parent()
    {
        return $this->belongsTo(Area::class,'parent_id');
    }


    public function orders()
    {
        return $this->hasManyThrough(Order::class,Address::class)
            ->where('status','success');
    }
}
