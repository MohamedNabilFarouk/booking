<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/30/2019
 * Time: 1:56 PM
 */
namespace Modules\Space\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Core\Events\CreatedServicesEvent;
use Modules\Core\Events\UpdatedServiceEvent;
use Modules\Core\Models\Attributes;
use Modules\Location\Models\Location;
use Modules\Location\Models\LocationCategory;
use Modules\Space\Models\Space;
use Modules\Space\Models\SpaceTerm;
use Modules\Space\Models\SpaceTranslation;

class SpaceController extends AdminController
{
    protected $space;
    protected $space_translation;
    protected $space_term;
    protected $attributes;
    protected $location;
    /**
     * @var string
     */
    private $locationCategoryClass;

    public function __construct()
    {
        parent::__construct();
        $this->setActiveMenu('admin/module/space');
        $this->space = Space::class;
        $this->space_translation = SpaceTranslation::class;
        $this->space_term = SpaceTerm::class;
        $this->attributes = Attributes::class;
        $this->location = Location::class;
        $this->locationCategoryClass = LocationCategory::class;
    }

    public function callAction($method, $parameters)
    {
        if(!Space::isEnable())
        {
            return redirect('/');
        }
        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function index(Request $request)
    {
        $this->checkPermission('space_view');
        $query = $this->space::query() ;
        $query->orderBy('id', 'desc');
        if (!empty($space_name = $request->input('s'))) {
            $query->where('title', 'LIKE', '%' . $space_name . '%');
            $query->orderBy('title', 'asc');
        }

        if ($this->hasPermission('space_manage_others')) {
            if (!empty($author = $request->input('vendor_id'))) {
                $query->where('create_user', $author);
            }
        } else {
            $query->where('create_user', Auth::id());
        }
        $data = [
            'rows'               => $query->with(['author'])->paginate(20),
            'space_manage_others' => $this->hasPermission('space_manage_others'),
            'breadcrumbs'        => [
                [
                    'name' => __('Spaces'),
                    'url'  => 'admin/module/space'
                ],
                [
                    'name'  => __('All'),
                    'class' => 'active'
                ],
            ],
            'page_title'=>__("Space Management")
        ];
        return view('Space::admin.index', $data);
    }

    public function recovery(Request $request)
    {
        $this->checkPermission('space_view');
        $query = $this->space::onlyTrashed() ;
        $query->orderBy('id', 'desc');
        if (!empty($space_name = $request->input('s'))) {
            $query->where('title', 'LIKE', '%' . $space_name . '%');
            $query->orderBy('title', 'asc');
        }

        if ($this->hasPermission('space_manage_others')) {
            if (!empty($author = $request->input('vendor_id'))) {
                $query->where('create_user', $author);
            }
        } else {
            $query->where('create_user', Auth::id());
        }
        $data = [
            'rows'               => $query->with(['author'])->paginate(20),
            'space_manage_others' => $this->hasPermission('space_manage_others'),
            'recovery'           => 1,
            'breadcrumbs'        => [
                [
                    'name' => __('Spaces'),
                    'url'  => 'admin/module/space'
                ],
                [
                    'name'  => __('Recovery'),
                    'class' => 'active'
                ],
            ],
            'page_title'=>__("Recovery Space Management")
        ];
        return view('Space::admin.index', $data);
    }

    public function create(Request $request)
    {
        $this->checkPermission('space_create');
        $row = new $this->space();
        $row->fill([
            'status' => 'publish'
        ]);
        $data = [
            'row'            => $row,
            'attributes'     => $this->attributes::where('service', 'space')->get(),
            'space_location' => $this->location::where('status', 'publish')->get()->toTree(),
            'location_category' => $this->locationCategoryClass::where('status', 'publish')->get(),
            'translation'    => new $this->space_translation(),
            'breadcrumbs'    => [
                [
                    'name' => __('Spaces'),
                    'url'  => 'admin/module/space'
                ],
                [
                    'name'  => __('Add Space'),
                    'class' => 'active'
                ],
            ],
            'page_title'     => __("Add new Space")
        ];
        return view('Space::admin.detail', $data);
    }

    public function edit(Request $request, $id)
    {
        $this->checkPermission('space_update');
        $row = $this->space::find($id);
        if (empty($row)) {
            return redirect(route('space.admin.index'));
        }
        $translation = $row->translateOrOrigin($request->query('lang'));
        if (!$this->hasPermission('space_manage_others')) {
            if ($row->create_user != Auth::id()) {
                return redirect(route('space.admin.index'));
            }
        }
        $data = [
            'row'            => $row,
            'translation'    => $translation,
            "selected_terms" => $row->terms->pluck('term_id'),
            'attributes'     => $this->attributes::where('service', 'space')->get(),
            'space_location'  => $this->location::where('status', 'publish')->get()->toTree(),
            'location_category' => $this->locationCategoryClass::where('status', 'publish')->get(),
            'enable_multi_lang'=>true,
            'breadcrumbs'    => [
                [
                    'name' => __('Spaces'),
                    'url'  => 'admin/module/space'
                ],
                [
                    'name'  => __('Edit Space'),
                    'class' => 'active'
                ],
            ],
            'page_title'=>__("Edit: :name",['name'=>$row->title])
        ];
        return view('Space::admin.detail', $data);
    }

    public function store( Request $request, $id ){

        if($id>0){
            $this->checkPermission('space_update');
            $row = $this->space::find($id);
            if (empty($row)) {
                return redirect(route('space.admin.index'));
            }

            if($row->create_user != Auth::id() and !$this->hasPermission('space_manage_others'))
            {
                return redirect(route('space.admin.index'));
            }
        }else{
            $this->checkPermission('space_create');
            $row = new $this->space();
            $row->status = "publish";
        }
        $dataKeys = [
            'title',
            'content',

            'price',
            'is_instant',
            'status',
            'video',
            'faqs',
            'image_id',
            'banner_image_id',
            'gallery',
            'bed',
            'bathroom',
            'spaces_no',
            'square',
            'location_id',
            'address',
            'map_lat',
            'map_lng',
            'map_zoom',
            'price',
            'sale_price',
            'max_guests',
            'enable_extra_price',
            'extra_price',
            'is_featured',
            'default_state',
            'min_day_before_booking',
            'min_day_stays',
            'surrounding',

        ];
        if($this->hasPermission('space_manage_others')){
            $dataKeys[] = 'create_user';
        }

        $row->fillByAttr($dataKeys,$request->input());
        if($request->input('slug')){
            $row->slug = $request->input('slug');
        }
	    $row->ical_import_url  = $request->ical_import_url;
        $row->enable_service_fee = $request->input('enable_service_fee');
        $row->service_fee = $request->input('service_fee');

        $res = $row->saveOriginOrTranslation($request->input('lang'),true);

        if ($res) {
            if(!$request->input('lang') or is_default_lang($request->input('lang'))) {
                $this->saveTerms($row, $request);
            }

            if($id > 0 ){
                event(new UpdatedServiceEvent($row));

                return back()->with('success',  __('Space updated') );
            }else{
                event(new CreatedServicesEvent($row));

                return redirect(route('space.admin.edit',$row->id))->with('success', __('Space created') );
            }
        }
    }

    public function saveTerms($row, $request)
    {
        $this->checkPermission('space_manage_attributes');
        if (empty($request->input('terms'))) {
            $this->space_term::where('target_id', $row->id)->delete();
        } else {
            $term_ids = $request->input('terms');
            foreach ($term_ids as $term_id) {
                $this->space_term::firstOrCreate([
                    'term_id' => $term_id,
                    'target_id' => $row->id
                ]);
            }
            $this->space_term::where('target_id', $row->id)->whereNotIn('term_id', $term_ids)->delete();
        }
    }

    public function bulkEdit(Request $request)
    {

        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __('No items selected!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select an action!'));
        }

        switch ($action){
            case "delete":
                foreach ($ids as $id) {
                    $query = $this->space::where("id", $id);
                    if (!$this->hasPermission('space_manage_others')) {
                        $query->where("create_user", Auth::id());
                        $this->checkPermission('space_delete');
                    }
                    $row  =  $query->first();
                    if(!empty($row)){
                        $row->delete();
                        event(new UpdatedServiceEvent($row));

                    }
                }
                return redirect()->back()->with('success', __('Deleted success!'));
                break;
            case "permanently_delete":
                foreach ($ids as $id) {
                    $query = $this->space::where("id", $id);
                    if (!$this->hasPermission('space_manage_others')) {
                        $query->where("create_user", Auth::id());
                        $this->checkPermission('space_delete');
                    }
                    $row  =  $query->withTrashed()->first();
                    if($row){
                        $row->forceDelete();
                    }
                }
                return redirect()->back()->with('success', __('Permanently delete success!'));
                break;
            case "recovery":
                foreach ($ids as $id) {
                    $query = $this->space::withTrashed()->where("id", $id);
                    if (!$this->hasPermission('space_manage_others')) {
                        $query->where("create_user", Auth::id());
                        $this->checkPermission('space_delete');
                    }
                    $row = $query->first();
                    if(!empty($row)){
                        $row->restore();
                        event(new UpdatedServiceEvent($row));

                    }
                }
                return redirect()->back()->with('success', __('Recovery success!'));
                break;
            case "clone":
                $this->checkPermission('space_create');
                foreach ($ids as $id) {
                    (new $this->space())->saveCloneByID($id);
                }
                return redirect()->back()->with('success', __('Clone success!'));
                break;
            default:
                // Change status
                foreach ($ids as $id) {
                    $query = $this->space::where("id", $id);
                    if (!$this->hasPermission('space_manage_others')) {
                        $query->where("create_user", Auth::id());
                        $this->checkPermission('space_update');
                    }
                    $row = $query->first();
                    $row->status  = $action;
                    $row->save();
                        event(new UpdatedServiceEvent($row));
                }
                return redirect()->back()->with('success', __('Update success!'));
                break;
        }


    }
}
