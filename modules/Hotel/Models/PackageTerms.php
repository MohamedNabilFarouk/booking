<?php

namespace Modules\Hotel\Models;

use Illuminate\Database\Eloquent\Model;

class PackageTerms extends Model
{
    //
    protected $table  = 'package_terms';

    protected $fillable=[
        'package_id',
        'term_id',
        
    ];

    public function term(){
        return $this->belongsTo('Modules\Core\Models\Terms' , 'term_id','id');
    }
}
