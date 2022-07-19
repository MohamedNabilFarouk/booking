<?php

namespace App\Http\Controllers;
use Modules\Hotel\Models\RoomPackage;
use Modules\Hotel\Models\RoomPackageBooking;

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


class paymentController extends Controller
{

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

    //
    // protected $booking;
    // // protected $enquiryClass;
    // protected $bookingInst;

    public function generate(Request $request){
        $fawryPay = new FawryPay;
        // dd($fawryPay);
    
// ********************** booking in database *************************
Session::forget('packageBooking_id');
            Session::forget('booking_id');
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
   $data = new RoomPackageBooking();
   $data->user_id = Auth::user()->id;
   $data->package_id = $request->package_id;
   $data->name = $request->first_name.'  '.$request->last_name;
   $data->email = $request->email;
   $data->phone = $request->phone;
   $data->from = $request->from;
   $data->to = $request->to;
   $data->total = $request->total;
    // $data->is_paid = '1';

   $data->save();
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
    $booking->status = 'completed';
    // $booking->wallet_credit_used = floatval($credit);
    // $booking->wallet_total_used = floatval($wallet_total_used);
    $booking->pay_now = floatval($booking->deposit == null ? $booking->total : $booking->deposit);
    $booking->save();
     Session::put('booking_id', $booking->id);
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
        $pay_url = $fawryPay->generatePayURL(rand(1,3000000),'Order  Invoice','https://hoteelsegypt.com/Callback','https://hoteelsegypt.com/Callback');
        // dd($pay_url);
      
        return redirect($pay_url);
        //  return $this->callback();
    }

    public function callback(){
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
        // dd($response);
        if (isset($response->paymentStatus) and $response->paymentStatus=='PAID') { //paid
            // dd('Paid Successfully',$response); 
            
            if((Session::get('packageBooking_id')) != null){
                $booking= RoomPackageBooking::where('id', Session::get('packageBooking_id'))->first();
            }else if((Session::get('booking_id'))!= null){
                $booking= Booking::where('id', Session::get('booking_id'))->first();
            }
            //  dd($booking);
            
        //    dd($booking);
            $booking->is_paid = '1';
            $booking->save();
            
            return redirect('/');

        } else {
            if((Session::get('packageBooking_id')) != null){
            $booking= Booking::where('id', Session::get('packageBooking_id'))->delete();
             }else if((Session::get('booking_id'))!= null){
            $booking= Booking::where('id', Session::get('booking_id'))->delete();
             }
           return 'error in payment retry later';
        }

        // $booking= Booking::where('id', Session::get('booking_id'))->first();
        // // dd($booking);
        // $booking->is_paid = '1';
        // $booking->save();
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
    
    
    public function getsuccess(Request $request){
    
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

}
