<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BaseModel extends Model
{

    public function localizeAttribute($attribute)
    {
        return $this->{$attribute . '_' . app()->getLocale()} ? : $this->{$attribute . '_' . config('app.fallback_locale')};
    }

    public function getNameAttribute()
    {
        return $this->localizeAttribute('name');
    }

    public function getDescriptionAttribute()
    {
        return $this->localizeAttribute('description');
    }

    public function scopeOfStatus($query, $value)
    {
        $query->where('status', $value);
    }

    public function scopeActive($query)
    {
        $query->where('active', 1);
    }

    public function getImage($image)
    {
        $image = config('filesystems.disks.s3.bucket').'/'.$image;
        return Storage::disk('s3')->url($image);
    }
}
