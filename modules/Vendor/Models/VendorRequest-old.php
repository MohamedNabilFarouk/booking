<?php
namespace Modules\Vendor\Models;

use App\BaseModel;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Models\SEO;
use Spatie\Permission\Models\Role;

class VendorRequestOLD extends BaseModel
{
    use SoftDeletes;
    protected $table = 'user_upgrade_request';
    protected $fillable = [
        'user_id',
        'approved_time',
        'role_request',
        'status',
        'approved_time',
        'approved_by',
        'tax_card',
        'bank_account',
        'commercial_record',
    ];

    public static function getModelName()
    {
        return __("User upgrade request");
    }
    public function user(){
        return $this->belongsTo(User::class)->withDefault();
    }
    public function approvedBy(){
        return $this->belongsTo(User::class,'approved_by')->withDefault();
    }
    public function role(){
        return $this->belongsTo(Role::class,'role_request')->withDefault();;
    }



    public function getTaxCardUrl()
    {
        if (!empty($this->tax_card)) {
            return get_file_url($this->tax_card);
        }

        return asset('images/avatar.png');
    }


    public function getCommercialRecordUrl()
    {
        if (!empty($this->commercial_record)) {
            return get_file_url($this->commercial_record);
        }

        return asset('images/avatar.png');
    }
    public function getBankAccount()
    {
        if (!empty($this->bank_account)) {
            return get_file_url($this->bank_account);
        }

    }
}
