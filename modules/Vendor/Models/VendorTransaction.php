<?php

namespace Modules\Vendor\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;

class VendorTransaction extends Model
{
    //
    protected $guarded =[];

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
