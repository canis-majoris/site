<?php
namespace App\Http\Controllers; //admin add

//use App\Repositories\LanguageRepository;
use App\DataTables\PermissionsDataTable;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;
use App\Models\Language;
use App\Models\Region;
use App\Models\Role;
use Config;
use Validator;

class RoleController extends TypeMenuController {

    public $role;

    public function __construct() {
        parent::__construct();
        $this->view_directory = 'permissions';
        $this->item = new Role;
        View::share('for_all_regions', true);
    }

    /*public function __construct() {
        parent::__construct();
        
    }*/

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(PermissionsDataTable $dataTable)
    {
        return $dataTable->render('permissions.index', []);

        //return view('main.languages.index', compact('languages'/*, 'links'*/));
    }
    
    public function create() {
        return view('permissions.create');
    }

    public function show() {
        return view('permissions.create');
    }
    
    public function loadTypeSettings(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['id']) {
                //$region_id = session('region_id') ? session('region_id') : $this->default_region_id;
                $role = Role::find($data['id']);
                $select_data = Permission::whereNotIn('id', $role->permissions()->pluck('id')->toArray())->get();
                return response()->json(['success' => true, 'status' => 1, 'message' => 'role settings loaded', 'item' => $role, 'select_data' => $select_data, 'items_type' => 'permissions']);
            }
            return response()->json(['success' => false, 'status' => 0, 'message' => 'could not load role settings']);
        }
    }

    public function deleteRoleItem(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['id']) {
                $role = Role::find($data['id']);
                if (count($role)) {
                    foreach ($role->permissions()->get() as $p) {
                        if ($p->roles()->count() == 1 && $p->roles()->first()->id == $role->id) {
                            $p->delete();
                        }
                    }

                    $role->permissions()->detach();
                    $role->users()->detach();
                    $role->delete();
                }

                $roles = Role::all();

                $view = View::make('permissions.parts.form_right', compact('roles'))->render();

                return response()->json(['success' => true, 'status' => 1, 'message' => 'region changed', 'html' => $view, 'itemCount' => count($role)]);
            }
            return response()->json(['success' => false, 'status' => 0, 'message' => 'could not change region']);
        }
    }

    public function edit(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if (isset($data['id']) && $id = $data['id']) {
                $menu_item = Role::find($data['id']);
                if ($menu_item) {
                    $view = View::make('permissions.manage.menu_item', compact('menu_item'))->render();
                    return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
                }
            } else {
                $menu_item = null;
                $view = View::make('permissions.manage.menu_item', compact('menu_item'))->render();
                return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
            }
        }
        return response()->json(['success' => true, 'status' => 1, 'message' => 'something went wrong..']);
    }

    public function addPermission(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if (isset($data['permission_id']) && isset($data['role_id'])) {
                $permission = Permission::find($data['permission_id']);
                $role = Role::find($data['role_id']);
                if ($permission && $role) {
                    $role->attachPermission($permission);
                    return response()->json(['success' => true, 'status' => 1, 'message' => 'permission added']);
                }
            }
        }
        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong..']);
    }

    public function removePermission(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if (isset($data['permission_id']) && isset($data['role_id'])) {
                $permission = Permission::find($data['permission_id']);
                $role = Role::find($data['role_id']);
                if ($permission && $role) {
                    $role->permissions()->detach($permission->id);
                    return response()->json(['success' => true, 'status' => 1, 'message' => 'permission removed']);
                }
            }
        }
        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong..']);
    }

}