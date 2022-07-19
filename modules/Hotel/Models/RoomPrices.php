<?php

namespace Modules\Hotel\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPrices extends Model
{
    //
    protected $table  = 'room_prices';

    protected $fillable=[
        'room_id',
        'ip',
        'price',
    ];

    public function room(){
        return $this->belongsTo(HotelRoom::class , 'room_id', 'id');
    }

    public function ips(){
        return $this->hasMany(RoomPrices::class, 'price', 'price');
    }
    // public function packageTerms(){
    //     return $this->hasMany(PackageTerms::class, 'package_id','id' );
    // }
    // public function booking(){
    //     return $this->hasMany(RoomPackageBooking::class , 'package_id', 'id');
    // }
}
