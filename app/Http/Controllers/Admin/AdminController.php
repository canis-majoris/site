<?php

namespace App\Http\Controllers;

use App\DataTables\AdminsDataTable;
use App\Http\Controllers\Admin\AppController;
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
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Region;
use App\Models\Country;
use App\Models\Language;
use Validator;
//use App\Repositories\UserRepository;

class AdminController extends AppController {
    /**
     * Create a new MenuController instance.
     *
     * @param  App\Repositories\MenuRepository $menu
     * @return void
     */
    public function __construct() {
        parent::__construct();
        View::share('for_all_regions', true);
        //$this->domain = $connection = config('database.region.'.$region.'.domain');
        //$this->prefix = $connection = config('database.region.'.$region.'.prefix');
    }

    /**
     * Create a new MenuController instance.
     *
     * @param  App\Repositories\MenuRepository $menu
     * @return void
     */

    public function index(AdminsDataTable $dataTable) {
        $auth_user = Auth::user();
        $countries = Country::all();
        $regions = Region::find(json_decode($auth_user->region, true));
        $roles = Role::all();
        return $dataTable->render('admins.index', compact('regions', 'countries', 'roles'));
    }

    protected function create(array $data)
    {
        return User::create([
            'username'       => $data['firstname'] . ' ' . $data['lastname'],
            'email'          => $data['email'],
            'password'       => bcrypt($data['password']),
            'region'         => (isset($data['region']) ? json_encode($data['region']) : 'all'),
            'city'           => ($data['city'] ? $data['city'] : null),
            'phone'          => ($data['phone'] ? $data['phone'] : null),
            'country'        => ($data['country'] ? $data['country'] : null),
            'status'         => (isset($data['status']) && $data['status'] == "on") ? 1 : 0
        ]);
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

    public function update(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if (isset($data['id']) && $data['id'] != null) {
                $add_rules = [
                    'password' => 'confirmed|min:6'
                ];
                $validator = Validator::make($data, User::rules($data['id'], $add_rules));
            } else {
                $add_rules = [
                    'password' => 'required|confirmed|min:6'
                ];
                $validator = Validator::make($data, User::rules(0, $add_rules));
            }
            if($validator->fails()){
                return response()->json(['success' => false, 'status' => 0, 'message' => $validator->errors()->all()]);
            } else {
                if (isset($data['id']) && $data['id'] != null) {
                    //update existing user
                    $user = User::find($data['id']);
                } else {
                    //add new user
                    $user = new User;
                }

                $arr = [];
                if ($user) {
                    $arr['username'] = $data['firstname'] . ' ' . $data['lastname'];
                    $arr['email']    = ($data['email']) ? $data['email'] : null;
                    $arr['region']   = (isset($data['region']) ? json_encode($data['region']) : 'all');
                    $arr['city']     = ($data['city'] ? $data['city'] : null);
                    $arr['phone']    = ($data['phone'] ? $data['phone'] : null);
                    $arr['country']  = ($data['country'] ? $data['country'] : null);
                    $arr['role']    = (isset($data['role']) ? $data['role'] : null);
                    $arr['status']   = (isset($data['status']) && $data['status'] == "on") ? 1 : 0;
                    if (isset($data['password']) && $data['password'] != '') {
                        $arr['password'] = bcrypt($data['password']);
                    }

                    $result = $user->saveHelper($arr);
                    $message = 'administrator settings updated';
                    if ($result['errors']) {
                        $message = $result['errors'];
                    }
                }

                return response()->json(['success' => true, 'status' => 1, 'message' => 'Administrator parameters updated']);
            }
        }
        return response()->json(['success' => true, 'status' => 1, 'message' => 'could not update administrator parameters..']);
    }

    public function delete(Request $request) {

        if ($request->ajax()) {
            $ids = $request->get('ids');

            if ($ids) {
                $message = 'Administrator deleted';
                if (count($ids) > 1) {
                    $message = count($ids).' Administrator(s) deleted';
                }

                $errors = [];
                $messages = [];
                $status = 1; 

                foreach ($ids as $id) {
                    $user = User::where('activity', '!=', 1)->find($id);
                    if ($user) {
                        $code = $user->username;

                        if ($result = $user->deleteHelper()) {
                            if ($result['errors']) {
                                $errors [$code]= $result['errors'];
                                $status = $result['status'];
                            } else $messages [$code]= $result['messages'];
                        }
                    } else {
                        $errors ['Error']= 'Can not delete active user.';
                        $status = false;
                    }
                }

                return response()->json(['success' => true, 'status' => $status, 'messages' => $messages, 'errors' => $errors]);
            }
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'could not delete administrator(s)..']);


        // if ($request->ajax()) {
        //     $ids = $request->get('user_ids');
        
        //     if ($ids) {
        //         $message = 'Administrator deleted';
        //         if (count($ids) > 1) {
        //             $message = count($ids).' Administrator(s) deleted';
        //         }

        //         $errors = '';
        //         foreach ($ids as $id) {
        //            if ($result = User::find($id)->deleteHelper()) {
        //                 $errors .= ($result['errors'] ? $result['errors'] : '');
        //             }
        //         }

        //         if ($errors) {
        //             $message = $errors;
        //         }

        //         return response()->json(['success' => true, 'status' => 1, 'message' => $message]);
        //     }
        // }
        // return response()->json(['success' => false, 'status' => 0, 'message' => 'could not delete administrator..']);
    }

    public function postChangeStatus(Request $request) {
        if ($request->ajax()) {
            $ids = Input::get('ids');
            $user = User::find($ids[0]);
            $user->status = ($user->status == 1 ? 0 : 1);
            if($user->save()) {
                return response()->json(['success' => true, 'status' => $user->status, 'message' => 'Status changed']);
            }
            return response()->json(['success' => false, 'error' => true, 'status' => $user->status, 'message' => 'Oops, something went wrong']);
        }
    }

    public function update_img(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if (isset($data['action'])) {
                if ($data['action'] == 'upload') {
                    $id = $data['id'] ? $data['id'] : null;
                    $fname = User::upload_img($data, $id);
                    if ($id) {
                        $admin = User::find($id);
                        if ($admin) {
                            $admin->avatar = trim($fname);
                            $admin->save();
                        }
                    }
                    $message = 'image uploaded';
                } elseif ($data['action'] == 'delete') {
                    $id = (isset($data['id'])) ? $data['id'] : null;
                    $fname = User::find($id)->delete_img($data);
                    $message = 'image deleted';
                }
                return response()->json(['success' => true, 'status' => 1, 'message' => $message, 'newfilename' => $fname]);
            }
        }
        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong..']);
    }

    //change user status
    public function assignRole(Request $request) {
        //$role = Role::find(1);

        //$role->perms()->sync([1,2,3,4,5,6,7,8]);

        //User::find(39)->roles()->attach(1);

        /*$createPost = new Permission();
        $createPost->name         = 'catalog-create';
        $createPost->display_name = 'Create Catalog Item'; // optional
        // Allow a user to...
        $createPost->description  = 'create catalog item'; // optional
        $createPost->save();

        $editUser = new Permission();
        $editUser->name         = 'catalog-edit';
        $editUser->display_name = 'Edit Catalog Item'; // optional
        // Allow a user to...
        $editUser->description  = 'edit catalog item'; // optional
        $editUser->save();

        $pr_1 = new Permission();
        $pr_1->name         = 'catalog-delete';
        $pr_1->display_name = 'Delete Catalog Item'; // optional
        // Allow a user to...
        $pr_1->description  = 'delete catalog item'; // optional
        $pr_1->save();

        $pr_2 = new Permission();
        $pr_2->name         = 'catalog';
        $pr_2->display_name = 'Access Catalog'; // optional
        // Allow a user to...
        $pr_2->description  = 'access catalog'; // optional
        $pr_2->save();

        $pr_3 = new Permission();
        $pr_3->name         = 'users';
        $pr_3->display_name = 'Access Users'; // optional
        // Allow a user to...
        $pr_3->description  = 'access users'; // optional
        $pr_3->save();

        $pr_4 = new Permission();
        $pr_4->name         = 'users-create';
        $pr_4->display_name = 'Create User'; // optional
        // Allow a user to...
        $pr_4->description  = 'create user'; // optional
        $pr_4->save();

        $pr_5 = new Permission();
        $pr_5->name         = 'users-edit';
        $pr_5->display_name = 'Edit User'; // optional
        // Allow a user to...
        $pr_5->description  = 'edit user'; // optional
        $pr_5->save();

        $pr_6 = new Permission();
        $pr_6->name         = 'users-delete';
        $pr_6->display_name = 'Delete User'; // optional
        // Allow a user to...
        $pr_6->description  = 'delete user'; // optional
        $pr_6->save();*/

        dd('done');
        if ($request->ajax()) {
            if($user->save()) {
                return response()->json(['success' => true, 'status' => $user->activated, 'message' => 'სტატუსი შეიცვალა']);
            }
            return response()->json(['success' => false, 'error' => true, 'status' => $user->activated, 'message' => 'სტატუსის შეცვლა ვერ მოხერხდა']);
        }
    }
}