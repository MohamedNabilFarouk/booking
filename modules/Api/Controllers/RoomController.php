<?php
namespace Modules\Api\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Hotel\Models\RoomPackage;
use Modules\Hotel\Models\RoomPackageBooking;
use Stevebauman\Location\Facades\Location;
use App\Http\Resources\RoomResource;



use Illuminate\Support\Facades\Validator;

class RoomController extends \Modules\Booking\Controllers\BookingController
{
    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->middleware('auth:api')->except([
    //         'detail','getConfigs','getHomeLayout','getTypes','checkout','doCheckout','checkStatusCheckout','confirmPayment','getGatewaysForApi',
    //         'thankyou'
    //     ]);
    // }





public function getHotelRoomsPackage($room_id){
$rooms = RoomPackage::where('room_id', $room_id )->with('packageTerms')->get();
// $ip = '66.102.0.0'; //test for local
// dd($rooms[0]->roomPackage[8]->prices);
//  $data = Location::get($ip);
//   dd($data);
// $rooms->countryCode = $data->countryCode;
// $result = [];
// foreach($rooms as $r){
//     $result[]=[
//         'title' =>$r['title'],
//         'content'=>$r['content'],
//         'status'=>$r['status']
//     ];
// }
// dd($result);
// exit();
return response()->json(['success'=>'true' , 'data'=>RoomResource::collection($rooms)]);
}

public function getUserPackageBooking($user_id){
    $packages = RoomPackageBooking::where('user_id', $user_id )->orderBy('id','desc')->with('package')->get();
    if(!empty($packages)){
    return response()->json(['success'=>'true' , 'data'=>$packages]);
    }else{
    return response()->json(['success'=>'true' , 'data'=>'No Packages Booked !']);
    }
}


}