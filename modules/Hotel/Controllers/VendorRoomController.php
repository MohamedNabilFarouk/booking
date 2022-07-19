<?php
namespace Modules\Hotel\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\FrontendController;
use Modules\Core\Models\Attributes;
use Modules\Hotel\Models\HotelRoom;
use Modules\Hotel\Models\HotelRoomTerm;
use Modules\Hotel\Models\HotelRoomTranslation;
use Modules\Location\Models\Location;
use Modules\Hotel\Models\Hotel;
use Modules\Hotel\Models\HotelTerm;
use Modules\Hotel\Models\HotelTranslation;
use Modules\Hotel\Models\PackageTerms;
use Modules\Hotel\Models\RoomPackage;
use Modules\Core\Models\Terms;
use Illuminate\Support\Facades\DB;
use Modules\Hotel\Models\RoomPackageBooking;

use Modules\Hotel\Models\RoomPackagePrices;
use Modules\Hotel\Models\RoomPrices;


use Stevebauman\Location\Facades\Location as Locations;


class VendorRoomController extends FrontendController
{
    protected $hotelClass;
    protected $roomTermClass;
    protected $attributesClass;
    protected $locationClass;
    /**
     * @var HotelRoom
     */
    protected $roomClass;
    protected $currentHotel;
    protected $roomTranslationClass;

    public function __construct()
    {
        parent::__construct();
        $this->hotelClass = Hotel::class;
        $this->roomTermClass = HotelRoomTerm::class;
        $this->attributesClass = Attributes::class;
        $this->locationClass = Location::class;
        $this->roomClass = HotelRoom::class;
        $this->roomTranslationClass = HotelRoomTranslation::class;
    }

    protected function hasHotelPermission($hotel_id = false){
        if(empty($hotel_id)) return false;
        $hotel = $this->hotelClass::find($hotel_id);
        if(empty($hotel)) return false;
        if(!$this->hasPermission('hotel_update') and $hotel->create_user != Auth::id()){
            return false;
        }
        $this->currentHotel = $hotel;
        return true;
    }
    public function index(Request $request,$hotel_id)
    {
        $this->checkPermission('hotel_view');

        if(!$this->hasHotelPermission($hotel_id))
        {
            abort(403);
        }
        $query = $this->roomClass::query() ;
        $query->orderBy('id', 'desc');
        if (!empty($hotel_name = $request->input('s'))) {
            $query->where('title', 'LIKE', '%' . $hotel_name . '%');
            $query->orderBy('title', 'asc');
        }
        $query->where('parent_id',$hotel_id);
        $data = [
            'rows'               => $query->with(['author'])->paginate(20),
            'breadcrumbs'        => [
                [
                    'name' => __('Hotels'),
                    'url'  => route('hotel.vendor.index')
                ],
                [
                    'name' => __('Hotel: :name',['name'=>$this->currentHotel->title]),
                    'url'  => route('hotel.vendor.edit',[$this->currentHotel->id])
                ],
                [
                    'name'  => __('All Rooms'),
                    'class' => 'active'
                ],
            ],
            'page_title'=>__("Room Management"),
            'hotel'=>$this->currentHotel,
            'row'=> new $this->roomClass(),
            'translation'=>new $this->roomTranslationClass(),
            'attributes'     => $this->attributesClass::where('service', 'hotel_room')->get(),
        ];
        return view('Hotel::frontend.vendorHotel.room.index', $data);
    }

    public function create($hotel_id)
    {
        $this->checkPermission('hotel_update');

        if(!$this->hasHotelPermission($hotel_id))
        {
            abort(403);
        }
        $row = new $this->roomClass();
        $translation = new $this->roomTranslationClass();
        $data = [
            'row'            => $row,
            'translation'    => $translation,
            'attributes'     => $this->attributesClass::where('service', 'hotel_room')->get(),
            'enable_multi_lang'=>true,
            'breadcrumbs'    => [
                [
                    'name' => __('Hotels'),
                    'url'  => route('hotel.vendor.index')
                ],
                [
                    'name' => __('Hotel: :name',['name'=>$this->currentHotel->title]),
                    'url'  => route('hotel.vendor.edit',[$this->currentHotel->id])
                ],
                [
                    'name' => __('All Rooms'),
                    'url'  => route("hotel.vendor.room.index",['hotel_id'=>$this->currentHotel->id])
                ],
                [
                    'name'  => __('Create'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Create Room"),
            'hotel'=>$this->currentHotel
        ];
        return view('Hotel::frontend.vendorHotel.room.detail', $data);
    }

    public function edit(Request $request, $hotel_id,$id)
    {
        $this->checkPermission('hotel_update');

        if(!$this->hasHotelPermission($hotel_id))
        {
            abort(403);
        }

        $row = $this->roomClass::find($id);
        if (empty($row) or $row->parent_id != $hotel_id) {
            return redirect(route('hotel.vendor.room.index',['hotel_id'=>$hotel_id]));
        }

        $translation = $row->translateOrOrigin($request->query('lang'));

        $data = [
            'row'            => $row,
            'translation'    => $translation,
            "selected_terms" => $row->terms->pluck('term_id'),
            'attributes'     => $this->attributesClass::where('service', 'hotel_room')->get(),
            'enable_multi_lang'=>true,
            'breadcrumbs'    => [
                [
                    'name' => __('Hotels'),
                    'url'  => route('hotel.vendor.index')
                ],
                [
                    'name' => __('Hotel: :name',['name'=>$this->currentHotel->title]),
                    'url'  => route('hotel.vendor.edit',[$this->currentHotel->id])
                ],
                [
                    'name' => __('All Rooms'),
                    'url'  => route("hotel.vendor.room.index",['hotel_id'=>$this->currentHotel->id])
                ],
                [
                    'name' => __('Edit room: :name',['name'=>$row->title]),
                    'class' => 'active'
                ],
            ],
            'page_title'=>__("Edit: :name",['name'=>$row->title]),
            'hotel'=>$this->currentHotel
        ];
        return view('Hotel::frontend.vendorHotel.room.detail', $data);
    }

    public function store( Request $request, $hotel_id,$id ){
//  dd($id);
//  $row = $this->roomClass::find($id);
//  dd($row);
        if(!$this->hasHotelPermission($hotel_id))
        {
            abort(403);
        }
      
        if($id>0){
            $this->checkPermission('hotel_update');
            $row = $this->roomClass::find($id);
            if (empty($row)) {
                return redirect(route('hotel.vendor.index'));
            }
            if($row->parent_id != $hotel_id)
            {
                return redirect(route('hotel.vendor.room.index'));
            }
        }else{
            $this->checkPermission('hotel_create');
            $row = new $this->roomClass();
            $row->status = "publish";
                // dd($row->id);
               
        }
        // dd($row->id);
        $dataKeys = [
            'title',
            'content',
            'image_id',
            'gallery',
            'price',
            'number',
            'beds',
            'size',
            'adults',
            'children',
            'min_day_stays',
        ];
        
        $row->fillByAttr($dataKeys,$request->input());
        $row->ical_import_url  = $request->ical_import_url;
      
        if(!empty($id) and $id == "-1"){
            // dd($id);
            $row->parent_id = $hotel_id;
        }
    
        
        // dd($room_prices);

        $res = $row->saveOriginOrTranslation($request->input('lang'),true);

        if ($res) {
            if(!$request->input('lang') or is_default_lang($request->input('lang'))) {
                $this->saveTerms($row, $request);
            }

            if($id > 0 ){
                
                if(isset($request->arr)){
                    RoomPrices::where('room_id',$id)->delete();

                    foreach($request->arr as $r){
                        $p = $r['price'];
                        foreach ($r['country'] as $rr){
                            $room_prices= new RoomPrices();
                            $room_prices->ip = $rr;
                            $room_prices->price = $p;
                            $room_prices->room_id = $row->id;
                            $room_prices->save();
                        }

                    }

                   }


                return redirect()->back()->with('success',  __('Room updated') );
            }else{
                // dd($row->id);
                if(isset($request->arr)){   
                foreach($request->arr as $r){
                    $room_prices= new RoomPrices();
                    $room_prices->ip = $r['country'];
                    $room_prices->price = $r['price'];
                    $room_prices->room_id = $row->id;
                    $room_prices->save();
                }
            }
                return redirect(route('hotel.vendor.room.edit',['hotel_id'=>$hotel_id,'id'=>$row->id]))->with('success', __('Room created') );
            }
        }
    }

    public function saveTerms($row, $request)
    {
        if (empty($request->input('terms'))) {
            $this->roomTermClass::where('target_id', $row->id)->delete();
        } else {
            $term_ids = $request->input('terms');
            foreach ($term_ids as $term_id) {
                $this->roomTermClass::firstOrCreate([
                    'term_id' => $term_id,
                    'target_id' => $row->id
                ]);
            }
            $this->roomTermClass::where('target_id', $row->id)->whereNotIn('term_id', $term_ids)->delete();
        }
    }

    public function delete($hotel_id,$id )
    {
        $this->checkPermission('hotel_delete');
        $user_id = Auth::id();
        $query = $this->roomClass::where("parent_id", $hotel_id)->where("id", $id)->first();
        if(!empty($query)){
            $query->delete();
        }
        return redirect()->back()->with('success', __('Delete room success!'));
    }

    public function bulkEdit(Request $request , $hotel_id , $id)
    {
        $this->checkPermission('hotel_update');
        $action = $request->input('action');
        $user_id = Auth::id();
        $query = $this->roomClass::where("parent_id", $hotel_id)->where("id", $id)->first();
        if (empty($id)) {
            return redirect()->back()->with('error', __('No item!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select an action!'));
        }
        if(empty($query)){
            return redirect()->back()->with('error', __('Not Found'));
        }
        switch ($action){
            case "make-hide":
                $query->status = "draft";
                break;
            case "make-publish":
                $query->status = "publish";
                break;
        }
        $query->save();
        return redirect()->back()->with('success', __('Update success!'));
    }

    public function createRoomPackage($id){
$terms = Terms::where('attr_id','12')->get();
// dd($terms);
            return view('Hotel::frontend.vendorHotel.room.createPackage',['id'=>$id,'terms'=>$terms]);
    }


  public function storeRoomPackage(Request $request)
    {
        //   dd($request->arr);
        // $country_prices=[];
        
     
        // dd($country_prices);
        $data = $request->validate([
            'name'             => 'required|string',
            'des'             => 'required|string',
            'price'            =>'required',
                        
        ]);
        // dd( Auth::user()->id);
       
        $data = $request->all();
        $data['vendor_id'] = Auth::user()->id;
        DB::beginTransaction();
        try {
       $pack= RoomPackage::create($data);
    //    dd($pack);
       if(isset($pack)){
           foreach($data['terms'] as $t){
               $package_term = new PackageTerms();
               $package_term->package_id = $pack->id;
               $package_term->term_id = $t;
                $package_term->save();
           }

            if(!empty($request->arr )){
        
            foreach($request->arr as $r){
                $package_prices= new RoomPackagePrices();
                $package_prices->ip = $r['country'];
                $package_prices->price = $r['price'];
                $package_prices->room_package_id = $pack->id;
                $package_prices->save();
            }
           
        }
        DB::commit();
        return redirect()->back()->with('success',  __('Room Package Created') );
       }
       
      
    }catch(\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error',  __('Error in Create Room Package') );
    }
    }
    public function getRoomPackage($id){
    $packages =  RoomPackage::where('room_id',$id)->with('prices')->get();
          $ip = '41.233.122.119'; //test for local
        //  $ip = '66.102.0.0';
        //   $ip = \Request::ip();  //online
        //  dd($_SERVER);
            $data = Locations::get($ip);
            // foreach($packages as $p){
            //     $pack[]= RoomPackagePrices::where([['ip',$data->countryCode],['room_package_id',$p->id]])->get();
            // }  
            // dd($pack); 
             $packages->countryCode = $data->countryCode;
            //  dd($packages->countryCode);
// $p = RoomPackagePrices::where('ip',$data->countryCode)->get();
// dd($packages[8]->prices);
    return view('Hotel::frontend.vendorHotel.room.getPackages',['packages'=>$packages]);
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
       $data = new RoomPackageBooking();
       
       $package = RoomPackage::find($request->package_id);  //get specific package

       $data->user_id = Auth::user()->id;
       $data->package_id = $request->package_id;
       $data->name = $request->first_name;
       $data->email = $request->email;
       $data->phone = $request->phone;
       $data->from = $request->from;
       $data->to = $request->to;
       $data->vendor_id = $package->vendor_id;   //vendor_id

       $data->save();
       return redirect()->back()->with('success',  __('Room Package Booked Successfully') );
    }
    public function getPackageCheckout($id){
        $package = RoomPackage::find($id);
        return view('Hotel::frontend.vendorHotel.room.packageCheckout',['package'=>$package]);
    }

    public function getVendorPackages(){
        $packages = RoomPackage::where('vendor_id',Auth::user()->id)->get();
        return view('Hotel::frontend.vendorHotel.vendorPackages',['packages'=>$packages]);
    }

    public function editRoomPackage($id){
        $terms = Terms::where('attr_id','12')->get();
        // $prices = Terms::where('package_id',$id)->get();
        $package = RoomPackage::find($id);
        return view('Hotel::frontend.vendorHotel.updatePackage',['package'=>$package,'terms'=>$terms]);
    }


    public function updateRoomPackage($id,Request $request)
    {
        //   dd($request->arr);
        $data = $request->validate([
            'name'             => 'required|string',
            'des'             => 'required|string',
            'price'             => 'required|numeric',            
        ]);
            $pack = RoomPackage::findOrFail($id);

        $data['vendor_id'] = Auth::user()->id;
        $data = $request->all();

        DB::beginTransaction();
        // try {
       $pack->update($data);
    //    dd($pack);
       if(isset($pack)){
           if(isset($data['terms'])){
            PackageTerms::where('package_id',$id)->delete();
           }
           foreach($data['terms'] as $t){
               $package_term = new PackageTerms();
               $package_term->package_id = $pack->id;
               $package_term->term_id = $t;
                $package_term->save();
           }
           if(isset($data['arr'])){
            RoomPackagePrices::where('room_package_id',$id)->delete();
           }
           foreach($data['arr'] as $r){
            $package_prices= new RoomPackagePrices();
            $package_prices->ip = $r['country'];
            $package_prices->price = $r['price'];
            $package_prices->room_package_id = $pack->id;
            $package_prices->save();
        }
       }
       DB::commit();
       return redirect()->back()->with('success',  __('Room Package Updated') );
    // }catch(\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error',  __('Error in Update Room Package') );
    // }
    }

    public function deleteRoomPackage($id){
        $package = RoomPackage::where('id',$id)->delete();
         return redirect()->back();

    }

    public function bookingPackageReport(){
        // $report = RoomPackageBooking::where('package_id',$id)
             $package = RoomPackage::where('vendor_id',Auth::user()->id)->orderBy('id','DESC')->get();
        return view('Hotel::frontend.vendorHotel.PackagebookingReport',['package'=>$package]);
                
        // dd($package[1]->booking);
    }

    }
