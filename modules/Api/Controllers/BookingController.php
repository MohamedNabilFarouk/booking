<?php
namespace Modules\Api\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Models\Booking;
use Modules\Template\Models\Template;
use Illuminate\Support\Facades\Validator;
use App\PaymentGateway;
use Modules\Hotel\Models\RoomPackageBooking;
use Modules\Hotel\Models\RoomPackage;
use Modules\Vendor\Models\VendorRequest;
use Modules\Vendor\Models\VendorTransaction;

use Modules\Hotel\Models\HotelRoomBooking; 
use Carbon\Carbon;
use DB;

class BookingController extends \Modules\Booking\Controllers\BookingController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except([
            'detail','getConfigs','getHomeLayout','getTypes','checkout','doCheckout','checkStatusCheckout','confirmPayment','getGatewaysForApi',
            'thankyou','getActiveGateway',
        ]);
    }
    public function getTypes(){
        $types = get_bookable_services();

        $res = [];
        foreach ($types as $type=>$class) {
            $obj = new $class();
            $res[$type] = [
                'icon'=>call_user_func([$obj,'getServiceIconFeatured']),
                'name'=>call_user_func([$obj,'getModelName']),
                'search_fields'=>[

                ],
            ];
        }
        return $res;
    }

    public function getConfigs(){
        $languages = \Modules\Language\Models\Language::getActive();
        $template = Template::find(setting_item('api_app_layout'));
        $res = [
            'languages'=>$languages->map(function($lang){
                return $lang->only(['locale','name']);
            }),
            'booking_types'=>$this->getTypes(),
            'country'=>get_country_lists(),
            'app_layout'=>$template? json_decode($template->content,true) : [],
            'is_enable_guest_checkout'=>(int)is_enable_guest_checkout()
        ];
        return $this->sendSuccess($res);
    }

    public function getHomeLayout(){
        $res = [];
        $template = Template::find(setting_item('api_app_layout'));
        if(!empty($template)){
            $res = $template->getProcessedContentAPI();
        }
        return $this->sendSuccess(
            [
                "data"=>$res
            ]
        );
    }


    protected function validateCheckout($code){

        $booking = $this->booking::where('code', $code)->first();

        $this->bookingInst = $booking;

        if (empty($booking)) {
            abort(404);
        }

        return true;
    }

    public function detail(Request $request, $code)
    {

        $booking = Booking::where('code', $code)->first();
        if (empty($booking)) {
            return $this->sendError(__("Booking not found!"))->setStatusCode(404);
        }

        if ($booking->status == 'draft') {
            return $this->sendError(__("You do not have permission to access"))->setStatusCode(404);
        }
        $data = [
            'booking'    => $booking,
            'service'    => $booking->service,
        ];
        if ($booking->gateway) {
            $data['gateway'] = get_payment_gateway_obj($booking->gateway);
        }
        return $this->sendSuccess(
            $data
        );
    }

    protected function validateDoCheckout(){

        $request = \request();
        /**
         * @param Booking $booking
         */
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('', ['errors' => $validator->errors()]);
        }
        $code = $request->input('code');
        $booking = $this->booking::where('code', $code)->first();
        $this->bookingInst = $booking;

        if (empty($booking)) {
            abort(404);
        }

        return true;
    }

    public function checkStatusCheckout($code)
    {
        $booking = $this->booking::where('code', $code)->first();
        $data = [
            'error'    => false,
            'message'  => '',
            'redirect' => ''
        ];
        if (empty($booking)) {
            $data = [
                'error'    => true,
                'redirect' => url('/')
            ];
        }

        if ($booking->status != 'draft') {
            $data = [
                'error'    => true,
                'redirect' => url('/')
            ];
        }
        return response()->json($data, 200);
    }

    public function getGatewaysForApi(){
        $res = [];
        $gateways = $this->getGateways();
        foreach ($gateways as $gateway=>$obj){
            $res[$gateway] = [
                'logo'=>$obj->getDisplayLogo(),
                'name'=>$obj->getDisplayName(),
                'desc'=>$obj->getApiDisplayHtml(),
            ];
            if($option = $obj->getForm()){
                $res[$gateway]['form'] = $option;
            }
            if($options = $obj->getApiOptions()){
                $res[$gateway]['options'] = $options;
            }
        }

        return $this->sendSuccess($res);
    }

    public function thankyou(Request $request, $code)
    {

        $booking = Booking::where('code', $code)->first();
        if (empty($booking)) {
            abort(404);
        }

        if ($booking->status == 'draft') {
            return redirect($booking->getCheckoutUrl());
        }

        $data = [
            'page_title' => __('Booking Details'),
            'booking'    => $booking,
            'service'    => $booking->service,
        ];
        if ($booking->gateway) {
            $data['gateway'] = get_payment_gateway_obj($booking->gateway);
        }
        return view('Booking::frontend/detail', $data);
    }



    public function checkout($code)
    {
        $res = $this->validateCheckout($code);
        if($res !== true) return $res;

        $booking = $this->bookingInst;

        if($booking->status != 'draft'){
            return redirect('/');
        }

        $is_api = request()->segment(1) == 'api';
        // dd($booking);
        $data = [
            'page_title' => __('Checkout'),
            'booking'    => $booking,
            'service'    => $booking->service,
            'gateways'   => $this->getGateways(),
            'user'       => Auth::user(),
            'is_api'    =>  $is_api
        ];
    return response()->json(['success'=>'true','data'=>$booking->code]);
    }


    public function getActiveGateway(){
        $gateway = PaymentGateway::where('status','1')->get();
        return response()->json(['success'=>'true' ,'data'=>$gateway]);
    }


    public function bookRoomPackage(Request $request){
        $data = $request->validate([
            'from'=>'required',
            'to'    =>'required',
            'first_name'=>'required',
            // 'last_name'=>'required',
            'email'    =>'required',
            'phone'=>'required',
        ]);
        $package = RoomPackage::find($request->package_id);  //get specific package
       $data = new RoomPackageBooking();
       $data->user_id = Auth::user()->id;
       $data->package_id = $request->package_id;
       $data->name = $request->first_name;
       $data->email = $request->email;
       $data->phone = $request->phone;
       $data->from = $request->from;
       $data->to = $request->to;
       $data->gateway = $request->gateway;
       $data->callback_url = $request->callback_url;
       $data->vendor_id = $package->vendor_id;   //vendor_id
       $data->total = $request->total;

       $data->save();
       
       return response()->json(['success'=>'true' ,'data'=>$data]);
    }

    public function updateBookingPayment(Request $request){
        if(($request->code) != 'null'){
            // dd('here 1');
        $booking = Booking::where('code', $request->code)->first();
            $booking->status = 'completed';

            $booking->first_name = $request->first_name;
            $booking->last_name = $request->last_name;
            $booking->phone = $request->phone;
            $booking->email = $request->email;
            $booking->address = $request->address;

        }else{
            // dd('here2');
            $booking = RoomPackageBooking::where('id', $request->booking_id)->first();

            $booking->name = $request->first_name.'  '.$request->last_name;
            $booking->email = $request->email;
            $booking->phone = $request->phone;
        }
        $booking->is_paid = $request->is_paid;
        $booking->callback_url = $request->callback_url;
        $booking->gateway = $request->gateway;
       

        $booking->save();
        // $booking->sendStatusUpdatedEmails();
        if($booking->is_paid == 1){
            $booking->sendNewBookingEmails();
        }
        

        // $booking->sendStatusUpdatedEmails();
        // calculate balance
        $vendor_id = $booking->vendor_id;
        $trans = VendorTransaction::where('vendor_id',$vendor_id)->orderBy('updated_at', 'desc')->first();
        if(isset($trans)){
        $withdrow_date= $trans->updated_at;
        $booked_packages_total = RoomPackageBooking::where([['vendor_id',$vendor_id],['is_paid','1'],['updated_at','>',$withdrow_date]])->pluck('total')->sum();
        $booked_hotel_total = Booking::where([['vendor_id',$vendor_id],['is_paid','1'],['updated_at','>',$withdrow_date]])->pluck('total')->sum();

}else{
    // dd('here');
     $withdrow_date = Carbon::now();
    $booked_packages_total = RoomPackageBooking::where([['vendor_id',$vendor_id],['is_paid','1']])->pluck('total')->sum();
    $booked_hotel_total = Booking::where([['vendor_id',$vendor_id],['is_paid','1']])->pluck('total')->sum();
}

 $balance_before =  $booked_packages_total + $booked_hotel_total;
$balance_after = $balance_before - ($balance_before * .1);
            $vendor= VendorRequest::where('user_id',$vendor_id)->first();
            if(isset($vendor)){
                $vendor->balance = $balance_after;
                $vendor->save();
               }
// end calculate balance

        return response()->json(['success'=>'true']);
    }

public function updateUserData(Request $request){
    if(($request->code) != 'null'){
        // dd('here 1');
    $booking = Booking::where('code', $request->code)->first();
        $booking->first_name = $request->first_name;
        $booking->last_name = $request->last_name;
        $booking->phone = $request->phone;
        $booking->email = $request->email;
        $booking->address = $request->address;
        $booking->save();
        return response()->json(['success'=>'true']);
    }else{
        return response()->json(['success'=>'false']);
    }
}


}
