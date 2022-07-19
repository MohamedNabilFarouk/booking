<?php

namespace Modules\Hotel\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPackage extends Model
{
    //
    protected $table  = 'room_packages';

    protected $fillable=[
        'name',
        'des',
        'price',
        'room_id',
        'vendor_id',
        
    ];

    public function room(){
        return $this->belongsTo(HotelRoom::class , 'room_id', 'id');
    }

    public function packageTerms(){
        return $this->hasMany(PackageTerms::class, 'package_id','id' );
    }
    public function booking(){
        return $this->hasMany(RoomPackageBooking::class , 'package_id', 'id');
    }

     public function prices(){
        return $this->hasMany(RoomPackagePrices::class, 'room_package_id', 'id' );
    }
}
