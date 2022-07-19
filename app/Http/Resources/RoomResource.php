<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Stevebauman\Location\Facades\Location as Locations;
use Modules\Hotel\Models\RoomPackagePrices;


class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ip = '66.102.0.0'; //test for local
        // $price = $this->price;
         $data = Locations::get($ip);
         
       $row = RoomPackagePrices::where([['id',$this->id],['ip',$data->countryCode]])->first();
    //    dd($row);
       if(!empty($row)){
        $price = $row->price;
       }else{
          
          $price = $this->price;
       } 
       $terms=[];
       foreach($this->packageTerms as $t){
            if(isset($t->term)){
            $terms[] = $t->term->name;
            }
       }
    
    //    dd($this->price);

        // $this->roomPackage[0];
        return [
            'id'=>$this->id,
        'name'=>$this->name,
        'des'=>$this->des,
        'price'=>$price,
        'terms'=>$terms,
        ];



        

        // return parent::toArray($request);
    }
}
