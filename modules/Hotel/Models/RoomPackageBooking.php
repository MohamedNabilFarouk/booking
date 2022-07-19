<?php

namespace Modules\Hotel\Models;

use Eluceo\iCal\Component\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Illuminate\Database\Eloquent\Model;
use Modules\Hotel\Emails\NewBookingEmail;

class RoomPackageBooking extends Model
{
    //
    protected $table  = 'room_package_booking';

    protected $fillable=[
        'package_id',
        'user_id',
        'vendor_id',
        'name',
        'email',
        'phone',
        'to',
        'from',
        'total',
        'vendor_id',
        
    ];

    public function package(){
        return $this->hasMany(RoomPackage::class , 'id', 'package_id');
    }

    
    public function user(){
        return $this->belongsTo('App\User' , 'id', 'user_id');
    }


    public function sendNewBookingEmails()
    {
        try {
            // To Admin
            Mail::to(setting_item('admin_email'))->send(new NewBookingEmail($this, 'admin'));

            // to Vendor
            Mail::to(User::find($this->vendor_id))->send(new NewBookingEmail($this, 'vendor'));

            // To Customer
            Mail::to($this->email)->send(new NewBookingEmail($this, 'customer'));

        }catch (\Exception | \Swift_TransportException $exception){

            Log::warning('sendNewBookingEmails: '.$exception->getMessage());
        }
    }
}
