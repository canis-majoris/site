<?php

namespace App\Http\Controllers;

use App\DataTables\ActivityLogDataTable;
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
use Carbon\Carbon;
use App\User;
use App\Role;
use App\Permission;
use App\Models\Region;
use App\Models\Country;
use App\Models\ActivityLog;
use Validator;
//use App\Repositories\UserRepository;

class ActivityLogController extends AppController {

    public function __construct() {
        parent::__construct();
        View::share('for_all_regions', true);
        //$this->domain = $connection = config('database.region.'.$region.'.domain');
        //$this->prefix = $connection = config('database.region.'.$region.'.prefix');
    }

    public function index(ActivityLogDataTable $dataTable) {
        $auth_user = Auth::user();
        $countries = Country::all();
        $regions = Region::find(json_decode($auth_user->region, true));
        $roles = Role::all();
        return $dataTable->render('activity_log.index', compact('regions', 'countries', 'roles'));
    }

    public function edit(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            $languages = Language::filterRegion(false)->get();
            $countries = Country::all();
            $regions = Region::all();
            $roles = Role::all();
            if (isset($data['id']) && $id = $data['id']) {
                $admin = User::find($data['id']);
                if ($admin) {
                    $view = View::make('admins.manage.form', compact('admin', 'regions', 'countries', 'roles'))->render();
                    return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
                }
            } else {
                $admin = null;
                $view = View::make('admins.manage.form', compact('admin', 'regions', 'countries', 'roles'))->render();
                return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
            }
        }
        return response()->json(['success' => true, 'status' => 1, 'message' => 'something went wrong..']);
    }

    public function delete(Request $request) {
         if ($request->ajax()) {
            $ids = $request->get('ids');

            if ($ids) {
                $message = 'Activity log deleted';
                if (count($ids) > 1) {
                    $message = count($ids).' Activity logs deleted';
                }

                $errors = [];
                $messages = [];
                $status = 1; 

                foreach ($ids as $id) {
                    $activity_log = ActivityLog::find($id);
                    if ($activity_log) {
                        $code = $activity_log->id;

                        if ($result = $activity_log->deleteHelper()) {
                            if ($result['errors']) {
                                $errors [$code]= $result['errors'];
                                $status = $result['status'];
                            } else $messages [$code]= $result['messages'];
                        }
                    }
                }

                return response()->json(['success' => true, 'status' => $status, 'messages' => $messages, 'errors' => $errors]);
            }
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'could not delete activity log..']);
    }
}