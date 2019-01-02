<?php

namespace App\Models;

class Address extends BaseModel
{
    protected $table = 'addresses';
    protected $fillable = ['user_id','block','street','building','avenue','latitude','longitude','city_ar','city_en','state_ar','state_en','country','address_en','address_ar','area_id'];

    protected $casts = ['latitude'=>'float','longitude'=>'float'];
    protected $with = ['area'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCityAttribute()
    {
        return $this->localizeAttribute('city');
    }

    public function getStateAttribute()
    {
        return $this->localizeAttribute('state');
    }

    public function getAddressAttribute()
    {
        return $this->localizeAttribute('address');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function getFormattedAddressAttribute()
    {
        return optional($this->area)->name . ' Block ' .$this->block . ' Street ' . $this->street . ' Avenue '. $this->avenue
        .' Building ' . $this->building;
    }

}
