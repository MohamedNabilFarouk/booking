<?php
namespace Modules\Report\Admin;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Booking\Emails\NewBookingEmail;
use Modules\Booking\Events\BookingUpdatedEvent;
use Modules\Booking\Models\Booking;
use Modules\Email\Emails\TestEmail;
use Modules\Location\Models\Location;
use Modules\Media\Helpers\FileHelper;
use Illuminate\Support\Facades\Mail;
use Modules\Report\Emails\orderCancelEmail;
use Modules\Report\Emails\orderConfirmEmail;

class BookingController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->setActiveMenu('admin/module/report/booking');
    }

    public function index(Request $request)
    {
        $this->checkPermission('booking_view');
        $query = Booking::where('status', '!=', 'draft');

//        if (!empty($request->s)) {
//            if( is_numeric($request->s) ){
//                $query->Where('id', '=', $request->s);
//            }else{
//                $query->where(function ($query) use ($request) {
//                    $query->where('first_name', 'like', '%' . $request->s . '%')
//                        ->orWhere('last_name', 'like', '%' . $request->s . '%')
//                        ->orWhere('email', 'like', '%' . $request->s . '%')
//                        ->orWhere('phone', 'like', '%' . $request->s . '%')
//                        ->orWhere('address', 'like', '%' . $request->s . '%')
//                        ->orWhere('address2', 'like', '%' . $request->s . '%');
//                });
//            }
//        }

//        if(!empty($request->vendor_id)){
//            $query->where('vendor_id', '=', $request->vendor_id);
//        }

        if (isset($request->is_paid)){
            if(isset($request->is_paid) && $request->is_paid == '1'){
                $query->where('is_paid', '=', $request->is_paid);
            }else{
                $query->where('is_paid', '!=', 1);
            }

        }
        if(!empty($request->object_model)){
            $query->where('object_model', '=', $request->object_model);
        }



//        if ($this->hasPermission('booking_manage_others')) {
            if (!empty($request->vendor_id)) {
                $query->where('vendor_id', $request->vendor_id);
            }
//        }
//        else {
//            $query->where('vendor_id', Auth::id());
//        }

        $query->whereIn('object_model', array_keys(get_bookable_services()));
        $query->orderBy('id','desc');


//        dd($query);
        $data = [
            'rows'                  => $query->paginate(10)->withQueryString(),
            'page_title'            => __("All Bookings"),
            'booking_manage_others' => $this->hasPermission('booking_manage_others'),
            'booking_update'        => $this->hasPermission('booking_update'),
            'statues'               => config('booking.statuses')
        ];
//        dd($data['rows'][0]->service);
        return view('Report::admin.booking.index', $data);
    }

    public function bulkEdit(Request $request)
    {
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('No items selected'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select action'));
        }
        if ($action == "delete") {
            foreach ($ids as $id) {
                $query = Booking::where("id", $id);
                if (!$this->hasPermission('booking_manage_others')) {
                    $query->where("vendor_id", Auth::id());
                }
                $row = $query->first();
                if(!empty($row)){
                    $row->delete();
                    event(new BookingUpdatedEvent($row));

                }
            }
        } else {
            foreach ($ids as $id) {
                $query = Booking::where("id", $id);
                if (!$this->hasPermission('booking_manage_others')) {
                    $query->where("vendor_id", Auth::id());
                    $this->checkPermission('booking_update');
                }
                $item = $query->first();
                if(!empty($item)){
                    $item->status = $action;
                    $item->save();

                    if($action == Booking::CANCELLED) $item->tryRefundToWallet();
                    event(new BookingUpdatedEvent($item));
                }
            }
        }
        return redirect()->back()->with('success', __('Update success'));
    }

    public function email_preview(Request $request, $id)
    {
        $booking = Booking::find($id);
        return (new NewBookingEmail($booking))->render();
    }


    public function orders(Request $request){
//        dd($request);
        $this->checkPermission('booking_view');
        $query = Booking::where('is_confirmed', '=', 0);
        if(!empty($request->object_model)){
            $query->where('object_model', '=', $request->object_model);
        }
        $query->whereIn('object_model', array_keys(get_bookable_services()));
        $query->orderBy('id','asc');


//        dd($query);
        $data = [
            'rows'                  => $query->paginate(100)->withQueryString(),
            'page_title'            => __("All Bookings"),
            'booking_manage_others' => $this->hasPermission('booking_manage_others'),
            'booking_update'        => $this->hasPermission('booking_update'),
            'statues'               => config('booking.statuses')
        ];
//        dd($data['rows'][0]->service);
        return view('Report::admin.booking.orders', $data);
    }

    public function orderConfirm(Booking $booking){

//        $to = ;
        $to = $booking->customer ? $booking->customer->email :  'soli.edh4an@gmail.com';
        try {
            Mail::to($to)->send(new orderConfirmEmail($booking));
            $booking->is_confirmed = 1;
            $booking->confirmed_by = \auth()->user()->id;
            $booking->confirmed_at = Carbon::now();
            $booking->save();
            return redirect()->back()->with('success',  __('order Confirmed') );
        } catch (\Exception $e) {
            return redirect()->back()->with('error',  __('some thing went wrong') );
        }
    }

    public function orderCancelConfirm(Booking $booking, Request $request){
        $request->validate([
            'hotels' => ['required', 'array']
        ]);

        $hotels = $request->hotels;


        $to = $booking->customer ? $booking->customer->email :  'soli.edh4an@gmail.com';

        try {
            Mail::to($to)->send(new orderCancelEmail($booking, $hotels));
            $booking->is_confirmed = 0;
            $booking->confirmed_by = \auth()->user()->id;
            $booking->confirmed_at = Carbon::now();
            $booking->deleted_at = Carbon::now();
            $booking->save();
            return redirect()->back()->with('success',  __('order canceled') );
        } catch (\Exception $e) {
            return redirect()->back()->with('error',  $e->getMessage() );
        }
    }

}
