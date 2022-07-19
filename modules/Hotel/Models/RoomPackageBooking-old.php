<?php

namespace Modules\Hotel\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPackageBookingOLD extends Model
{
    //
    protected $table  = 'room_package_booking';

    protected $fillable=[
        'package_id',
        'user_id',
        'name',
        'email',
        'phone',
        'to',
        'from',

    ];

    public function package(){
        return $this->hasMany(RoomPackage::class , 'id', 'package_id');
    }


    public function user(){
        return $this->belongsTo('App\User' , 'id', 'user_id');
    }
}
