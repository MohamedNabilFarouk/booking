<?php
namespace Modules\User\Models;

class User extends \App\User
{


    public function vendorRequest(){
        return $this->hasOne(VendorRequest::class,'user_id', 'id' );
    }
}
