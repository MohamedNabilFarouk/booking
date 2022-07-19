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

class mobilePaymentController extends Controller
{

    public function bankgenerate(Request $request){
                // save booking
                 
                
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
                        $apipassword = "9f83781d84f798c0093e0b19469c8d28"; //live
                        //  $apipassword = "0f655bd0fa82a2927fcf656fa857587c"; //test
                        $returnUrl = "https://hoteelsegypt.com/bankPayment/callback"; //live
                        // $returnUrl = "http://localhost:8000/bankPayment/callback"; //test
                        $currency = "EGP";
                        $amount =$rand_price;
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
                        // dd( $result );
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
                
                        //    dd($data);
                        return view('testPay',compact('data'));
        
        
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
}