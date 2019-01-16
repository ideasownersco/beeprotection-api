<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const ADMIN_CODE = 100;
    const DRIVER_CODE = 10;
    const GUEST_CODE = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'api_token','active','registration_code','push_token','blocked'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

//    public function getTypeAttribute()
//    {
//        if($this->admin) {
//            return 'admin';
//        }
//        if($this->driver) {
//            return 'driver';
//        }
//        return 'customer';
//
//    }

    public function isActive()
    {
        return $this->active;
    }

    public function getTypeAttribute()
    {

        if($this->admin) {
            return self::ADMIN_CODE;
        }

        if($this->driver) {
            return self::DRIVER_CODE;
        }

        return self::GUEST_CODE;

    }

    public function getTypeFormattedAttribute()
    {
        switch ($this->type) {
            case 100:
                return 'admin';
            case 10:
                return 'driver';
            default:
                return 'customer';
        }
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function push_tokens()
    {
        return $this->hasMany(PushToken::class);
    }

    /**
     * @return array
     */
    public function getAdminTokens()
    {
        $users = $this->with(['push_tokens'])->where('admin',1)->get();

        $tokens = $users->flatMap(function($admin) {
            return $admin->push_tokens->pluck('token');
        })->toArray();

        return $tokens;
    }

    public function scopeCustomers($query)
    {
        $drivers = Driver::pluck('user_id')->toArray();
        $query->where('admin', 0)->whereNotIn('id',$drivers);
    }

    public function createFakeUser()
    {

        $user = User::create(
            [
                'name' => uniqid(), 'email' => uniqid().'@guest.com','mobile' => rand(10000000,99999999),'password' => bcrypt('secret'),
                'guest'=>1,'api_token' => uniqid()
            ]);

    }

}
