<?php
namespace App\Http\Controllers;

use App\DataTables\PermissionsDataTable;
use App\Repositories\Eloquent\PermissionsRepository;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Config;

class PermissionController extends AppController {

    public $permissions;

    public function __construct(PermissionsRepository $permissions) {
        parent::__construct();

        $this->item = $permissions;

        $this->view_directory = 'permissions';

        $this->permissions = $permissions;

        View::share('for_all_regions', true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(PermissionsDataTable $dataTable) {
        $menu_items = Role::all();

        $role_permissions = $this->permissions->getAll();

        return $dataTable->render('permissions.index', compact('menu_items', 'role_permissions'));
    }

    /**
     * Create/update permission item.
     *
     * @return Response
     */
    public function update(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();

            $result = $this->permissions->getUpdate($data);

            $view = View::make('permissions.manage.form', $result)->render();

            return response()->json(['success' => true, 'status' => 1, 'message' => 'new permission', 'html' => $view]);
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong..']);
    }

    /**
     * Save permission item.
     *
     * @return Response
     */
    public function save(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();

            $result = [];

            $id = (isset($data['permission_id']) && !empty($data['permission_id'])) ? $data['permission_id'] : null;

            $validator = Validator::make($data, Permission::rules($id));
            
            if($validator->fails()){
                return response()->json(['success' => false, 'status' => 0, 'message' => $validator->errors()->all()]);
            } else {
                $result = $this->permissions->update($id, $data);

                return response()->json($result);
            }
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong']);
    }

    /**
     * Delete permission item.
     *
     * @return Response
     */
    public function delete(Request $request) {
         if ($request->ajax()) {
            $ids = $request->get('ids');

            $message = 'Permission deleted';

            if (count($ids) > 1) {
                $message = count($ids).' permissions deleted';
            }

            $result = $this->permissions->delete($ids);

            return response()->json(['success' => true, 'status' => $result['status'], 'messages' => $result['messages'], 'errors' => $result['errors']]);
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'could not delete permission(s)..']);
    }
}