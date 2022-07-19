<?php
namespace Modules\Vendor\Controllers;

use App\Helpers\ReCaptchaEngine;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Matrix\Exception;
use Modules\FrontendController;
use Modules\User\Events\NewVendorRegistered;
use Modules\User\Events\SendMailUserRegistered;
use Modules\Vendor\Models\VendorRequest;
use Modules\Vendor\Models\VendorTransaction;
use Modules\Booking\Models\Booking;
use Modules\Hotel\Models\RoomPackageBooking;
// use App\Traits\imagesTrait;




class VendorController extends FrontendController
{
    // use imagesTrait;

    protected $bookingClass;
    public function __construct()
    {
        $this->bookingClass = Booking::class;
        parent::__construct();
    }
    public function register(Request $request)
    {
    //   dd($request);
        $rules = [
            'first_name' => [
                'required',
                'string',
                'max:255'
            ],

            'last_name'  => [
                'required',
                'string',
                'max:255'
            ],
            'business_name'  => [
                'required',
                'string',
                'max:255'
            ],
            'email'      => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users'
            ],
            'password'   => [
                'required',
                'string'
            ],
            'term'       => ['required'],
            // 'commercial_record'       => ['required'],
            // 'tax_card'       => ['required'],
            // 'bank_account'       => ['required'],
        ];
        $messages = [
            'email.required'      => __('Email is required field'),
            'email.email'         => __('Email invalidate'),
            'password.required'   => __('Password is required field'),
            'first_name.required' => __('The first name is required field'),
            'last_name.required'  => __('The last name is required field'),
            'business_name.required'  => __('The business name is required field'),
            'term.required'       => __('The terms and conditions field is required'),
            // 'bank_account'=>__('bank account is required field'),
            // 'commercial_record'=>__('commercial record is required field'),
            // 'tax_card'=>__('tax card is required field'),
        ];
        if (ReCaptchaEngine::isEnable() and setting_item("user_enable_register_recaptcha")) {
            $messages['g-recaptcha-response.required'] = __('Please verify the captcha');
            $rules['g-recaptcha-response'] = ['required'];
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'error'    => true,
                'messages' => $validator->errors()
            ], 200);
        } else {
            if (ReCaptchaEngine::isEnable() and setting_item("user_enable_register_recaptcha")) {
                $codeCapcha = $request->input('g-recaptcha-response');
                if (!ReCaptchaEngine::verify($codeCapcha)) {
                    $errors = new MessageBag(['message_error' => __('Please verify the captcha')]);
                    return response()->json([
                        'error'    => true,
                        'messages' => $errors
                    ], 200);
                }
            }
            $user = new \App\User();

            $user = $user->fill([
                'first_name'=>$request->input('first_name'),
                'last_name'=>$request->input('last_name'),
                'email'=>$request->input('email'),
                'password'=>Hash::make($request->input('password')),
                'business_name'=>$request->input('business_name'),
                'phone'=>$request->input('phone'),
            ]);
            $user->status = 'publish';

            $user->save();
            if (empty($user)) {
                return $this->sendError(__("Can not register"));
            }

            //                check vendor auto approved
            $vendorAutoApproved = setting_item('vendor_auto_approved');
            $dataVendor['role_request'] = setting_item('vendor_role');
                // $dataVendor['tax_card']= $request->tax_card;
                // $dataVendor['commercial_record']= $request->commercial_record;

                // if ($request -> has('tax_card')) {
                //     $image = $this -> saveImages($request ->tax_card, 'images/taxcards');
                //     $dataVendor['tax_card'] = $image;
                // }

                // if ($request -> has('commercial_record')) {
                //     $image = $this -> saveImages($request ->commercial_record, 'images/commercial_record');
                //     $dataVendor['commercial_record'] = $image;
                // }

                // $dataVendor['bank_account']= $request->bank_account;
            if ($vendorAutoApproved) {
                if ($dataVendor['role_request']) {
                    $user->assignRole($dataVendor['role_request']);
                }
                $dataVendor['status'] = 'approved';
                $dataVendor['approved_time'] = now();

            } else {
                $dataVendor['status'] = 'pending';
                $user->assignRole('customer');
            }

            $vendorRequestData = $user->vendorRequest()->save(new VendorRequest($dataVendor));
            Auth::loginUsingId($user->id);
            try {
                event(new NewVendorRegistered($user, $vendorRequestData));
            } catch (Exception $exception) {
                Log::warning("NewVendorRegistered: " . $exception->getMessage());
            }
            if ($vendorAutoApproved) {
                return $this->sendSuccess([
                    'redirect' => url(app_get_locale(false, '/')),
                ]);
            } else {
                return $this->sendSuccess([
                    'redirect' => url('user/vendorData'),
                ], __("Register success. Please wait for admin approval"));
            }
        }
    }

    public function bookingReport(Request $request)
    {
        $data = [
            'bookings'    => $this->bookingClass::getBookingHistory($request->input('status'), false, Auth::id()),
            'statues'     => config('booking.statuses'),
            'breadcrumbs' => [
                [
                    'name'  => __('Booking Report'),
                    'class' => 'active'
                ],
            ],
            'page_title'  => __("Booking Report"),
        ];
        // dd($data);
        return view('Vendor::frontend.bookingReport.index', $data);
    }

    public function addTransaction($vendor_id){
        // $booked_packages_total = RoomPackageBooking::where([['vendor_id',Auth::id()],['is_paid','1']])->pluck('total')->sum();
        //  $booked_hotel_total = Booking::where([['vendor_id',Auth::id()],['is_paid','1']])->pluck('total')->sum();
        // $balance_before =  $booked_packages_total + $booked_hotel_total;
        // $balance_after = $balance_before - ($balance_before * .1);
        // //  dd($balance_after);
        // $vendor= VendorRequest::where('user_id',Auth::id())->first();
        // $vendor->balance = $balance_after;
        // $vendor->save();
        $vendor= VendorRequest::where('user_id',$vendor_id)->first();
        // dd($vendor);
        $transaction = new VendorTransaction();
        $transaction->vendor_id = $vendor_id;
        $transaction->withdrawal_amount = $vendor->balance;
        $transaction->created_by = Auth::id();
        $transaction->save();
        $vendor->balance = 0;
        $vendor->save();
        return redirect()->back();
    }

    // public function returnToZero($vendor_id){
    //     $vendor= VendorRequest::where('user_id',$vendor_id)->first();
    //     $vendor->balance = 0;
    //     $vendor->save();
    //     return redirect()->back();

    // }
}
