<?php

namespace Modules\Hotel\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPackagePrices extends Model
{
    //
    protected $table  = 'room_packages_prices';

    protected $fillable=[
        'room_package_id',
        'ip',
        'price',
    ];

    public function package(){
        return $this->belongsTo(RoomPackage::class , 'room_package_id', 'id');
    }

    // public function packageTerms(){
    //     return $this->hasMany(PackageTerms::class, 'package_id','id' );
    // }
    // public function booking(){
    //     return $this->hasMany(RoomPackageBooking::class , 'package_id', 'id');
    // }
}
