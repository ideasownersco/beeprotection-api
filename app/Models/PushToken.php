<?php

namespace App\Models;

class PushToken extends BaseModel
{
    protected $table = 'push_tokens';
    protected $guarded = ['id'];

    public function scopeIos($query)
    {
        return $query->where('os','ios');
    }

    public function scopeAndroid($query)
    {
        return $query->where('os','android');
    }

}
