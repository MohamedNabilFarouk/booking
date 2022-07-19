<?php

namespace App\Http\Controllers;
use Modules\Hotel\Models\RoomPackage;
use Modules\Hotel\Models\RoomPackageBooking;
use Modules\Vendor\Models\VendorRequest;
use Modules\Vendor\Models\VendorTransaction;


use Modules\Booking\Models\Booking;
use App\PaymentGateway;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Guzzle\Http\Client;
use GuzzleHttp\Psr7;
use Session;
use Carbon\Carbon;
use DB;
use MTGofa\FawryPay\FawryPay;

use Modules\Space\Models\Space;

class paymentController extends Controller
{

    


    public function bankgenerate(Request $request){
                // save booking
                //  dd($request);
                
                if(!isset($request->code)){
                    // dd($request);
                    $data = $request->validate([
                        'from'=>'required',
                        'to'    =>'required',
                        'first_name'=>'required',
                        'last_name'=>'required',
                        'total'=>'string',
                
                        'email'    =>'required',
                        'phone'=>'required',
                    ]);
                    $vendor= RoomPackage::where('id',$request->package_id)->select('vendor_id')->first();
                $data = new RoomPackageBooking();
                $data->user_id = Auth::user()->id;
                $data->package_id = $request->package_id;
                $data->name = $request->first_name.'  '.$request->last_name;
                $data->email = $request->email;
                $data->phone = $request->phone;
                $data->from = $request->from;
                $data->to = $request->to;
                $data->total = $request->total;
                
                $data->vendor_id = $vendor->vendor_id;
                
                    // $data->is_paid = '1';
                // dd($data);
                $data->save();
                // $merch_ref_number= $data->id;
                Session::put('packageBooking_id', $data->id);
                    }
                else{
                        $booking = Booking::where('code', $request->code)->first();
                        // $this->bookingInst = $booking;
                        // $this->booking = Booking::class;
                
                        $booking->first_name = $request->input('first_name');
                    $booking->last_name = $request->input('last_name');
                    $booking->email = $request->input('email');
                    $booking->phone = $request->input('phone');
                    $booking->address = $request->input('address_line_1');
                    $booking->address2 = $request->input('address_line_2');
                    $booking->city = $request->input('city');
                    $booking->state = $request->input('state');
                    $booking->zip_code = $request->input('zip_code');
                    $booking->country = $request->input('country');
                    $booking->customer_notes = $request->input('customer_notes');
                    $booking->gateway = 'Alex Bank';
                    // $booking->is_paid = '1';
                    // $booking->status = 'processing';
                    // $booking->wallet_credit_used = floatval($credit);
                    // $booking->wallet_total_used = floatval($wallet_total_used);
                    $booking->pay_now = floatval($booking->deposit == null ? $booking->total : $booking->deposit);
                    $booking->save();
                    // $merch_ref_number= $booking->code;
                    Session::put('booking_id', $booking->id);
                    $booking->sendStatusUpdatedEmails();
                    
                    }

                // end booking
                
                
                
                
                
                        //  $rand_order = $booking->id;
                        $rand_order = rand(1, 3000000);
                        $rand_price = $request->total;
                        $orderid = $rand_order;
                        // $merchant = "EC055418";
                        $merchant = "EC055418"; //live
                        $apipassword = "9f83781d84f798c0093e0b19469c8d28";
                        //  $apipassword = "9f83781d84f798c0093e0b19469c8d28"; //live
                        $returnUrl = "https://hoteelsegypt.com/bankPayment/callback"; //live
                        // $returnUrl = "http://localhost:8000/bankPayment/callback"; //test
                        $currency = "EGP";
                        $amount =$rand_price.".00";
                        $apiUsername= "Merchant.EC055418";
                        //  $apiUsername= "Merchant.101116755E01"; //live
                
                        $ch = curl_init();
                        //https://eu-gateway.mastercard.com/api/nvp/version/59" //live
                        // https://test-gateway.mastercard.com/api/nvp/version/59 //test
                        curl_setopt($ch, CURLOPT_URL,"https://eu-gateway.mastercard.com/api/nvp/version/59");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, "apiOperation=CREATE_CHECKOUT_SESSION&apiPassword=$apipassword&interaction.returnUrl=$returnUrl&apiUsername=$apiUsername&merchant=$merchant&interaction.operation=PURCHASE&order.id=$orderid&order.amount=$amount&order.currency=$currency");
                        // AUTHORIZE
                        $headers = array();
                        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                
                        $result = curl_exec($ch);
                        if(curl_errno($ch)) {
                        echo 'ERROR:'. curl_error($ch);
                        }
                
                        curl_close($ch);
                
                        //     print_r($result);
                        //    die();
                        $sessionid = explode("=", explode("&", $result)[2])[1];
                        $successIndicator = explode("=", explode("&", $result)[5])[1];
                        $sessionVersion = explode("=", explode("&", $result)[4])[1];
                        Session::put('data', ['0'=>$successIndicator,'1'=>$orderid]);
                        //  dd( $successIndicator);
                        $data[]=['merchant'=>$merchant,
                        'amount'=> $amount,
                        'currency'=> $currency,
                        'orderid'=> $orderid,
                        'sessionid'=> $sessionid,
                        'sessionVersion'=> $sessionVersion,
                        'successIndicator'=> $successIndicator,
                
                ];
                
                        // dd($data);
                        return view('testPay',compact('data'));
        
        
            }
        
            // https://hoteelsegypt.com/BankPayment?code=bfb4a0513a041ad792a7683e19e933d1&first_name=demo&last_name=account&email=demo%40demo.com&phone=0123456789&city=&country=EG&customer_notes=&term_conditions=on&total=180

    
   
   
            public function updateGateway($id,Request $request){
        // dd($request);
        $gateway = PaymentGateway::find($id);
        if($gateway->status == 0){
        $gateway->status= $request->status; 
        }else{
        $gateway->status= '0'; 

        }
        $gateway->save();

        return redirect()->back();
    }



    public function bankcallback(Request $request){
                    //  dd(Session::get('data')[1]);
                // dd($request->all());
                $successIndicator = Session::get('data')[0];
                //    $orderId = Session::get('data')[1];
                $orderId = Session::get('booking_id');
                if ($request->resultIndicator) {
                    $result = ($request->resultIndicator === $successIndicator) ? "SUCCESS" : "ERROR";
                    //  dd($result);
                    if($result == 'SUCCESS'){
                        // updata is_paid and return to success page or Home Page
                        $booking= Booking::where('id', $orderId )->first();
                        $booking->is_paid = 1;
                        $booking->status = 'completed';
                        $booking->save();


                        // extra cal

                            $booking->sendNewBookingEmails();


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

                    // end extra cal


                        return view('successPage');
                    }else{
                        return view('errorPage');
                        //  return to Faild page
                    }
                    // window.location.href = "/process/hostedCheckout/" + data.orderId + "/" + result;

                }
                // dd($successIndicator);
   }

//    ************************************************************************************************************

    //
    // protected $booking;
    // // protected $enquiryClass;
    // protected $bookingInst;

    public function generate(Request $request){

        $fawryPay = new FawryPay;
        // dd($fawryPay);
    
// ********************** booking in database *************************
// Session::forget('packageBooking_id');
// Session::forget('booking_id');
if(!isset($request->code)){
    // dd($request);
    $data = $request->validate([
        'from'=>'required',
        'to'    =>'required',
        'first_name'=>'required',
        'last_name'=>'required',
        'total'=>'string',

        'email'    =>'required',
        'phone'=>'required',
    ]);
    $vendor= RoomPackage::where('id',$request->package_id)->select('vendor_id')->first();
   $data = new RoomPackageBooking();
   $data->user_id = Auth::user()->id;
   $data->package_id = $request->package_id;
   $data->name = $request->first_name.'  '.$request->last_name;
   $data->email = $request->email;
   $data->phone = $request->phone;
   $data->from = $request->from;
   $data->to = $request->to;
   $data->total = $request->total;

   $data->vendor_id = $vendor->vendor_id;
    // $data->is_paid = '1';
// dd($data);
   $data->save();
   $merch_ref_number= $data->id;
   Session::put('packageBooking_id', $data->id);
    }else{
        $booking = Booking::where('code', $request->code)->first();
        // $this->bookingInst = $booking;
        // $this->booking = Booking::class;

        $booking->first_name = $request->input('first_name');
    $booking->last_name = $request->input('last_name');
    $booking->email = $request->input('email');
    $booking->phone = $request->input('phone');
    $booking->address = $request->input('address_line_1');
    $booking->address2 = $request->input('address_line_2');
    $booking->city = $request->input('city');
    $booking->state = $request->input('state');
    $booking->zip_code = $request->input('zip_code');
    $booking->country = $request->input('country');
    $booking->customer_notes = $request->input('customer_notes');
    $booking->gateway = 'Fawry';
    
    // $booking->is_paid = '1';
    $booking->status = 'processing';
    // $booking->wallet_credit_used = floatval($credit);
    // $booking->wallet_total_used = floatval($wallet_total_used);
    $booking->pay_now = floatval($booking->deposit == null ? $booking->total : $booking->deposit);
    $booking->save();
//     if($booking->object_model == 'space'){
//        $space= Space::where('id',$booking->object_id)->first();
// $space->spaces_no = ($space->spaces_no )- 1;
// $space->save();
//     }
    $merch_ref_number= $booking->code;


      Session::put('booking_id', $booking->id);

    //    $booking->sendNewBookingEmails();
      
    //   event(new BookingUpdatedEvent($booking));
    }
    // ***************************************************

        //optional if you have the customer data
        $fawryPay->customer([
            'customerProfileId' => Auth::user()->id,
            'name'              => $request->first_name,
            'email'             => $request->email,
            'mobile'            => $request->phone,
        ]);
            
        //you can add this method info foreach if you have multible items
        $fawryPay->addItem([
            'productSKU'    => rand(1,3000000), //item id
            'description'   => 'Order for user phone '.$request->phone, //item name
            'price'         => $request->total, //item price
            'quantity'      => '1', //item quantity
        ]);
    //  dd($fawryPay);
        //generatePayURL('your order unique id','your order discription','success url','failed url')
        $pay_url = $fawryPay->generatePayURL($merch_ref_number,'Order  Invoice','https://hoteelsegypt.com/Callback','https://hoteelsegypt.com/Callback');
        //  dd($pay_url);
    //   dd($this->bookingId);
        return redirect($pay_url);
        //  return $this->callback();
    }

    public function callback(){
        // dd($bookingId);
        // failureReasonMsg
        $ref_id = NULL;
        if(request('merchantRefNum')){ //fawry failed
            $ref_id = request('merchantRefNum');
        } elseif(request('MerchantRefNo')){ //fawry ipn
            $ref_id = request('MerchantRefNo');
        } elseif(request('chargeResponse')){ //fawry response is success
            $chargeResponse = json_decode(request('chargeResponse'));
            $ref_id = isset($chargeResponse->merchantRefNumber)?$chargeResponse->merchantRefNumber:NULL;
        }
    
        if(!$ref_id) return abort('404');
    
        $response = (new FawryPay)->checkStatus($ref_id);
        //    dd($response);

        $callback=  'https://hoteelsegypt.com/Callback?chargeResponse={"merchantRefNumber":"'.$response->merchantRefNumber.'","fawryRefNumber":"'.$response->referenceNumber.'","paymentMethod":"'.$response->paymentMethod.'"}';
        if($response->paymentMethod == 'PAYATFAWRY'){
            
            $callback=  'https://hoteelsegypt.com/Callback?chargeResponse={"merchantRefNumber":"'.$response->merchantRefNumber.'","fawryRefNumber":"'.$response->referenceNumber.'","paymentMethod":"PAYATFAWRY"}';   
          
              $booking= Booking::where('code', $response->merchantRefNumber)->first();
            if(!isset($booking)){
                $booking= RoomPackageBooking::where('id', $response->merchantRefNumber)->first();
            }
           
            $booking->gateway=$response->paymentMethod;
            $booking->callback_url= $callback;
          $booking->referenceNumber = $response->referenceNumber;
            $booking->save();
          
             
              
          
          // $response->paymentStatus = 'PAID';
      //    dd($response);
      if($response->paymentStatus == 'UNPAID'){
          return __('Wating for pay at fawry machine at time and press Confirm Payment Button') ;
        //   return redirect(url('user/booking-history'))->withErrors([__('Wating for pay at fawry machine at time and press Confirm Payment Button')]);
      }    
          //   dd($callback);
              // sleep(5);
               
          }   
        if (isset($response->paymentStatus) and $response->paymentStatus=='PAID') { //paid
            // dd('Paid Successfully',$response); 
           
            $booking= Booking::where('code', $response->merchantRefNumber)->first();
            if(!isset($booking)){
                $booking= RoomPackageBooking::where('id', $response->merchantRefNumber)->first();
            }
               
            
            //   dd($booking);
            $booking->is_paid = '1';
            $booking->gateway=$response->paymentMethod;
            $booking->callback_url= $callback;
            $booking->referenceNumber = $response->referenceNumber;
            $booking->save();
            // $booking->sendStatusUpdatedEmails();
            $booking->sendNewBookingEmails();
            //   dd($booking);
             
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
            return redirect('/');
          
        } else {
            // dd(Session::get('booking_id'));
           return 'error in payment retry later';
        }

        // $booking= Booking::where('id', Session::get('booking_id'))->first();
        // // dd($booking);
        // $booking->is_paid = '1';
        // $booking->save();
    }


    public function payatfawryCallback($id){
        $booking= Booking::where('id', $id)->first();
        if(isset($booking)){
        Session::put('booking_id', $booking->id);
        }
        if(!isset($booking)){
            $booking= RoomPackageBooking::where('id', $id)->first();
            Session::put('packageBooking_id', $booking->id);
        }
        return redirect(url($booking->callback_url));
    
    }

    public function generate2(){

        $merchantCode    = '1tSa6uxz2nRJRdVmo0mNVw==';
        $merchantRefNum  = '9990d0642040';
        $merchant_cust_prof_id  = '458626698';
        $payment_method = 'CARD';
        $amount = '580.55';
        $merchant_sec_key =  'e6fd80887c4941cda6f7488d59e79878'; // For the sake of demonstration
        $signature = hash('sha256' , $merchantCode . $merchantRefNum . $merchant_cust_prof_id . $payment_method . $amount . $merchant_sec_key);
       
        $body= [
            'merchantCode' => $merchantCode,
            'merchantRefNum' => $merchantRefNum,
            'customerName' => 'Ahmed Ali',
            'customerMobile' => '01011941903',
            'customerEmail' => 'example@gmail.com',
            'customerProfileId'=> '777777',
            'amount' => '580.55',
            'paymentExpiry' => 1631138400000,
            'currencyCode' => 'EGP',
            'language' => 'en-gb',

            'signature' => $signature,
            'payment_method' => $payment_method,
            'description' => 'example description'
        ];
        $httpClient = new \GuzzleHttp\Client(); // guzzle 6.3
        // dd($merchantCode);
        $response = $httpClient->request('post', 'https://atfawry.fawrystaging.com/merchant/login<https://linkprotect.cudasvc.com/url?a=https%3a%2f%2fu4987662.ct.sendgrid.net%2fls%2fclick%3fupn%3dRAvf6h4VQS9W4qw3CuWYonS1nBe9os2zOoaevKyY805sbZDa-2FCNn2ICne8JwNk9NcaJ-2FvQWib3ILVB8APV6zrA-3D-3D9M4g_nnQDIHezMEL9V6jkTf32W87O2F-2BB-2BjjMQWZ3rp4Pl5knc7KUTyk9LTze2CiumXvl11vqUob-2BHQ-2FjGUjqVewgVp7-2FPdLhzOlRTuxoollFmQyJMAfne3p3aDvTFS-2F9ZfoIGNahiDDun2uJDFw3zSK-2FyAGj4VMf0sRCxqvjZdYdZ8IvK2q5-2FHZUD2AFMzz5H8X-2FZ-2FI6BirriK3gnRidia5pta-2F2T-2B8HQW02JPXBam9slzE-3D&c=E,1,N0Z_6yLpTtN0CK8VHfjRZaSKoXjWGb-kplNs4XvAzjfIFaskKeETwZxyHHVaKPWsoXnTB50sy4WijCLaZ71yfNBVOo4gt3_iW8i9mMdKtTNROg0PAzZNkhbgMQ,,&typo=1>', [
            'headers' => ['Content-type' => 'application/json'],
            'body' => json_encode($body),
            
        ]);
        
        $response = json_decode($response->getBody()->getContents(), true);
        $paymentStatus = $response['type']; // get response values


    }



// *************************************************PayMob***************************************

public function payMob(Request $request){

  
    $body= [
        'api_key'=>'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnVZVzFsSWpvaWFXNXBkR2xoYkNJc0ltTnNZWE56SWpvaVRXVnlZMmhoYm5RaUxDSndjbTltYVd4bFgzQnJJam94TURnME1qVjkuRkt0aGZtMmZ1SmhZUzV3a2Z1UHdmQkduc0xjLUVIUjJpRHhJSmJVYnVoZV9GTzFYeVgwbTJGX2lnZldOWjg0T1Jna2dtcVlhbGNfbVVDMnBYT19HeUE='
    ];
    $client = new \GuzzleHttp\Client();

    
    try {
        $response = $client->request('post', 'https://accept.paymobsolutions.com/api/auth/tokens', [
            'body' =>json_encode($body),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    

   $authResult= json_decode( $response->getBody());
   //dd($authResult->token);
  //echo $authResult->token;

    } catch (HttpException $ex) {
      echo $ex;
      exit();
    }
    if(!isset($request->code)){
        // dd($request);
        $data = $request->validate([
            'from'=>'required',
            'to'    =>'required',
            'first_name'=>'required',
            'last_name'=>'required',
            'total'=>'string',
            'email'    =>'required',
            'phone'=>'required',
        ]);
        $vendor= RoomPackage::where('id',$request->package_id)->select('vendor_id')->first();
        $data = new RoomPackageBooking();
        $data->user_id = Auth::user()->id;
        $data->package_id = $request->package_id;
        $data->name = $request->first_name.'  '.$request->last_name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->from = $request->from;
        $data->to = $request->to;
        $data->total = $request->total;

        $data->vendor_id = $vendor->vendor_id;

        // $data->is_paid = '1';
        // dd($data);
        $data->save();
        // $merch_ref_number= $data->id;
    }
    else{
        $booking = Booking::where('code', $request->code)->first();
        // $this->bookingInst = $booking;
        // $this->booking = Booking::class;

        $booking->first_name = $request->input('first_name');
        $booking->last_name = $request->input('last_name');
        $booking->email = $request->input('email');
        $booking->phone = $request->input('phone');
        $booking->address = $request->input('address_line_1');
        $booking->address2 = $request->input('address_line_2');
        $booking->city = $request->input('city');
        $booking->state = $request->input('state');
        $booking->zip_code = $request->input('zip_code');
        $booking->country = $request->input('country');
        $booking->customer_notes = $request->input('customer_notes');
        $booking->gateway = 'PayMob';
        // $booking->is_paid = '1';
//            $booking->status = 'processing';
        // $booking->wallet_credit_used = floatval($credit);
        // $booking->wallet_total_used = floatval($wallet_total_used);
        $booking->pay_now = floatval($booking->deposit == null ? $booking->total : $booking->deposit);
        $booking->save();
        // $merch_ref_number= $booking->code;

        $booking->sendStatusUpdatedEmails();
        Session::put('booking_id', $booking->id);
    }
    // end booking

    \Illuminate\Support\Facades\Session::put('payMob_Booking_id', $request->code);

    $body= [
        "auth_token" => $authResult->token,
        "delivery_needed"=> "false",
        "merchant_order_id"=> rand(1, 3000000),
        "items"=> [],
        "merchant_id"=> "108425",
        "amount_cents"=> $request->total*100,
        "currency"=> "EGP",
    ];


    try {
        $response = $client->request('post', 'https://accept.paymobsolutions.com/api/ecommerce/orders', [
            'body' => json_encode($body),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
        $creationResult= json_decode($response->getBody());
    //  echo $response->getBody();
    } catch (HttpException $ex) {
      echo $ex;
    }
        // Session::put('translate_id', $data->id);
        // Session::put('code', $code);
        // Session::put('payment_type', 1);
        $body =[
            "auth_token" => $authResult->token,
            "amount_cents"=>$request->total*100,
            "expiration"=> 3600,
            "order_id"=> $creationResult->id,
            "currency"=> "EGP",
            "integration_id"=>374049, //live
            // "integration_id"=>370920, //test
            "billing_data"=>[
                "apartment"=> "500",
                "email"=> $request->email,
                "floor"=> "6",
                "first_name"=> $request->first_name,
                "street"=> "street sa as",
                "building"=> "36",
                "phone_number"=> $request->phone,
                "shipping_method"=> "PKG",
                "postal_code"=> "11721",
                "city"=> "cairo", 
                 "country"=> "EG",
                 "last_name"=> $request->last_name, 
                 "state"=> "Egypt"
            ],
        ];
            try {
                $response = $client->request('post', 'https://accept.paymobsolutions.com/api/acceptance/payment_keys', [
                    'body' =>json_encode($body),
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                ]);
                $paymentResult= json_decode( $response->getBody());
            } catch (HttpException $ex) {
            echo $ex;
            }

try {
    $url = "https://accept.paymob.com/api/acceptance/iframes/250284?payment_token={$paymentResult->token}";
    
  echo "<iframe src='$url' width='100%', height='100%'></iframe>";
} catch (HttpException $ex) {
  echo $ex;
}


$bookingRequest = collect($request);
// $bookingRequest->put('translate_id', $data->id);
// dd($bookingRequest);
// Session::put('book', $bookingRequest);
    
}


public function payMobPaymentCallback(Request $request){
    // dd($request->all());
    $message="";
    if((($request->success) != null)){
        if(($request->success) == 'true') {
            if (\Illuminate\Support\Facades\Session::get('payMob_Booking_id')) {
                $booking = Booking::where('code', \Illuminate\Support\Facades\Session::get('payMob_Booking_id'))->first();
                // updata is_paid and return to success page or Home Page
                $booking->status = 'completed';
                $booking->is_paid = 1;
                $booking->save();


                // extra cal

                $booking->sendNewBookingEmails();


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

                // end extra cal
            }
        }
    }
    return redirect(url("/user/booking-history"));

}








// ***********************************************************************************************












    public function payMobOLD(Request $request){

                    
                            $body= [
                                "username"=> "mohamed_nabil_farouk",
                                "password"=> "Billy123456789",
                                'api_key'=>'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnVZVzFsSWpvaWFXNXBkR2xoYkNJc0ltTnNZWE56SWpvaVRXVnlZMmhoYm5RaUxDSndjbTltYVd4bFgzQnJJam94T1RJNU1YMC53djN0UFZEbkxYNTRhLVkwSE8tZDhyMmZJdHBoa1laYXdDOWUtZUZrVWVsQmxRSGRhalBIcDZ2cXp1am9QYTZJeW9kMGlaWjFmT2s0Znl3UEY3Uk9HUQ=='
                            ];
                            $client = new \GuzzleHttp\Client();
                        
                            
                            try {
                                $response = $client->request('post', 'https://accept.paymobsolutions.com/api/auth/tokens', [
                                    'body' =>json_encode($body),
                                    'headers' => ['Content-type' => 'application/json']
                                ]);
                            
                        // dd($response->getBody());
                        $authResult= json_decode( $response->getBody());
                        //dd($authResult->token);
                        //echo $authResult->token;
                        
                            } catch (HttpException $ex) {
                            echo $ex;
                            exit();
                            }
                    
                        
                        
                            $body= [
                                "delivery_needed"=> "false",
                                
                                "merchant_order_id"=>rand(1,3000000),
                                
                                
                                "items"=> [],
                                "merchant_id"=> "19291",
                                "amount_cents"=> "25000",
                                "currency"=> "EGP",
                            
                            ];
                            
                            try {
                                $response = $client->request('post', 'https://accept.paymobsolutions.com/api/ecommerce/orders', [
                                    'body' =>json_encode($body),
                                    'headers' => ['Content-type' => 'application/json',
                                    'Authorization'      => 'Bearer '.$authResult->token,
                                            ],
                                ]);
                            $creationResult= json_decode( $response->getBody());
                            // dd($creationResult->id);
                            //  echo $response->getBody();
                            } catch (HttpException $ex) {
                            echo $ex;
                            }
                        if(!isset($request->code)){
                            $data = $request->validate([
                                'from'=>'required',
                                'to'    =>'required',
                                'first_name'=>'required',
                                'last_name'=>'required',

                                'email'    =>'required',
                                'phone'=>'required',
                            ]);
                        $data = new RoomPackageBooking();
                        $data->user_id = Auth::user()->id;
                        $data->package_id = $request->package_id;
                        $data->name = $request->first_name.'  '.$request->last_name;
                        $data->email = $request->email;
                        $data->phone = $request->phone;
                        $data->from = $request->from;
                        $data->to = $request->to;

                        $data->save();
                            }else{
                                $booking = Booking::where('code', $request->code)->first();
                                // $this->bookingInst = $booking;
                                // $this->booking = Booking::class;

                                $booking->first_name = $request->input('first_name');
                            $booking->last_name = $request->input('last_name');
                            $booking->email = $request->input('email');
                            $booking->phone = $request->input('phone');
                            $booking->address = $request->input('address_line_1');
                            $booking->address2 = $request->input('address_line_2');
                            $booking->city = $request->input('city');
                            $booking->state = $request->input('state');
                            $booking->zip_code = $request->input('zip_code');
                            $booking->country = $request->input('country');
                            $booking->customer_notes = $request->input('customer_notes');
                            $booking->gateway = 3;
                            // $booking->wallet_credit_used = floatval($credit);
                            // $booking->wallet_total_used = floatval($wallet_total_used);
                            $booking->pay_now = floatval($booking->deposit == null ? $booking->total : $booking->deposit);
                            $booking->save();
                            }


                                        
                                    
                                        // Session::put('translate_id', $data->id);
                                        // Session::put('code', $code);
                                        // Session::put('payment_type', 1);
                        
                        
                                
                        
                                        $body =[
                                    "amount_cents"=>$request->total*100,
                                    "currency"=> "EGP",
                                    "card_integration_id"=>"37012",
                                    //"integration_id"=>"37012",
                                    "expiration"=> 3600, 
                                    "order_id"=> $creationResult->id,
                                    //   "translate_id"=> $data->id,  
                                    "billing_data"=>[
                                        "apartment"=> "11", 
                                        "email"=> $request->email, 
                                        "floor"=> "6", 
                                        "first_name"=> $request->first_name, 
                                        "street"=> "113", 
                                        "building"=> "36", 
                                        "phone_number"=> $request->phone, 
                                        "shipping_method"=> "PKG", 
                                        "postal_code"=> "01898", 
                                        "city"=> "cairo", 
                                        "country"=> "Egypt", 
                                        "last_name"=> $request->last_name, 
                                        //  "DOCTOR"=> $doctor->name,
                                        "state"=> ".."
                                        
                                    ],
                                
                                ];
                        
                                
                                    
                                    try {
                                        $response = $client->request('post', 'https://accept.paymobsolutions.com/api/acceptance/payment_keys', [
                                            'body' =>json_encode($body),
                                            'headers' => ['Content-type' => 'application/json',
                                            'Authorization'      => 'Bearer '.$authResult->token,
                                                    ],
                                        ]);
                                        $paymentResult= json_decode( $response->getBody());
                                        // echo $creationResult->id;
                                        // dd( $paymentResult->token);
                                    // dd( json_decode($response->getBody()));
                                    } catch (HttpException $ex) {
                                    echo $ex;
                                    }
                        
                                    
                        
                        try {
                        
                                        // $url = "https://accept.paymobsolutions.com/api/acceptance/iframes/56364/pay?payment_key={$paymentResult->token}";
                                        $url = "https://accept.paymob.com/api/acceptance/iframes/56389?payment_token={$paymentResult->token}";
                        
                        
                            
                        echo " <iframe src='$url' width='100%', height='100%'></iframe>";
                        } catch (HttpException $ex) {
                        echo $ex;
                        }
                        
                        
                        $bookingRequest = collect($request);
                        // $bookingRequest->put('translate_id', $data->id);
                        // dd($bookingRequest);
                        // Session::put('book', $bookingRequest);
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        }
                        
                        
                        public function getsuccess(Request $request)
                        {
                        
                            $message="";    
                            if((($request->success) != null)){
                                if(($request->success) == 'true'){
                            
                                    if(Session::get('payment_type')== 1){
                                        $translate = translate::find(Session::get('translate_id'));
                                        $translate->is_paid = 1;
                                        $translate->save();
                                        $message = 'Booking Paid Successfully  and save this code for tracking <br> '.Session::get('code');
                                        return view('paymentsuccess')->with(['message'=>$message]);
                                    }else{
                                        // dd(Session::get('orderId'));
                                        $created_order = Order::find(Session::get('orderId'));
                                        $created_order->is_paid = 1;
                                        $created_order->save();
                                        $message = 'Order Paid Successfully';
                                        return view('paymentsuccess')->with(['message'=>$message]);
                                    }
                                    }else{
                                        $message = 'Payment Failed';
                                        return view('paymentfailed')->with(['message'=>$message]);
                                    }
                                    
                                    
                        
                                
                            }
                         }








    // ***********************************************************************************************

public function oPay(Request $request){

   

    $booking = Booking::where('code', $request->code)->first();

    $booking->first_name = $request->input('first_name');
    $booking->last_name = $request->input('last_name');
    $booking->email = $request->input('email');
    $booking->phone = $request->input('phone');
    $booking->address = $request->input('address_line_1');
    $booking->address2 = $request->input('address_line_2');
    $booking->city = $request->input('city');
    $booking->state = $request->input('state');
    $booking->zip_code = $request->input('zip_code');
    $booking->country = $request->input('country');
    $booking->customer_notes = $request->input('customer_notes');
    $booking->gateway = 'OPay';
    $booking->reference    = uniqid();
//    $booking->status = 'processing';
    $booking->pay_now = floatval($booking->deposit == null ? $booking->total : $booking->deposit);
    $booking->save();


    $country = "EG";
 $reference    =   $booking->reference;

 Session::put('booking_id', $booking->id);

$amount= [
    "total"=> $request->total * 100, //$request->total
    "currency"=> 'EGP',
];
$product= [
    "name"=> 'New Booking',
    "description"=> 'xxxxxxxxxxxxxxxxx',
];
$userInfo= [
    "userEmail"=>$booking->email,
    "userId"=>'',
    "userMobile"=>$booking->phone,
    "userName"=>$booking->first_name . $booking->last_name
];
$returnUrl  = 'https://hoteelsegypt.com/';
// $callbackUrl  = 'http://opayapi-001-site1.itempurl.com/api/payloads/OpayCallback';

$cancelUrl  = 'https://hoteelsegypt.com/';
// $userClientIP = '1.1.1.1';
$expireAt = 30;
$httpClient = new \GuzzleHttp\Client();
// https://sandboxapi.opaycheckout.com/api/v1/international/cashier/create  //test mode
// https://api.opaycheckout.com/api/v1/international/cashier/create  //live mode
$response = $httpClient->request('POST', 'https://api.opaycheckout.com/api/v1/international/cashier/create', [
            'headers' => [ 
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
                // 'Authorization' => 'Bearer OPAYPUB16419035640800.28463034883201754', //test
                'Authorization' => 'Bearer OPAYPUB16419847297280.928306246565266', //live
                // 'MerchantId' => '281822011132508'
                "MerchantId"=> "281822011276020", //live
                
            ],
            'body' => json_encode( [
                            'country' => $country,
                            'reference' => $reference,
                            'amount' => $amount,
                            // 'payMethod'=> 'BankCard',
                            'product' => $product,
                            'userInfo' => $userInfo,
                            'returnUrl' => 'https://hoteelsegypt.com/user/booking-history', // re
                            'callbackUrl'=> 'https://hoteelsegypt.com/OpayCallback',
                            'cancelUrl' => $cancelUrl,
                            // 'userClientIP' => $userClientIP,
                            'expireAt' => $expireAt,
                        ] , true)
]);
// dd($response);
$response = json_decode($response->getBody()->getContents(), true);
//  return $response;
if($response['data']){
    $paymentStatus = $response['data']; // get response values
    $url=$paymentStatus['cashierUrl'];
  
     return redirect($paymentStatus['cashierUrl']);


}else{
    return $response['message'];
}

}

public function Opaycallback(Request $request)
{
    // dd($request);
//    if($request->json){
//        dd('done');
//    }
    if($request['payload']['status'] == 'SUCCESS'){
    
    // updata is_paid and return to success page or Home Page
    $booking= Booking::where('reference',$request['payload']['reference'])->first();
    $booking->status = 'completed';
    $booking->is_paid = 1;
    $booking->save();


    // extra cal

        $booking->sendNewBookingEmails();


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

// end extra cal


     return 'success';
}else{
    return 'Error';
}


}



}



// https://hoteelsegypt.com/Callback?chargeResponse={"merchantRefNumber":"18ea99cf217d37947d49b0c4fd819c87","fawryRefNumber":"9111317421","paymentMethod":"CARD"}