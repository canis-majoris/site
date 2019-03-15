<?php

namespace App\DataTables;

use App\Permission;
use App\Role;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class PermissionsDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function ajax() {
        $role_id = $this->request()->get('role_id');
        $db = $this->datatables
            ->eloquent($this->query())
            ->editColumn('display_name', function ($permission) { 
                return '<span><b>'.$permission->display_name.'</b></span>';
            })
            ->addColumn('action', function ($permission) use ($role_id) { 
                $disable_delete = '';
                $detach_from_role = ($role_id ? '<a data-id="'.$permission->id.'" data-display_name="'.$permission->display_name.'" class="btn-xs btn-flat waves-effect waves-red remove_from_role-btn right"><i class="material-icons" style="font-size:20px;">remove_circle</i></a>' : '');
                $delete_permission = (!$role_id ? '<a data-id="'.$permission->id.'" data-type="mobile_services" '.$disable_delete.' class="btn-xs btn-flat waves-effect waves-red permissions-delete-btn right"><i class="material-icons" style="font-size:20px;">delete_forever</i></a>' : '');

                $html = '<div class="right">'.$delete_permission.''.$detach_from_role.'<a data-id="'.$permission->id.'" class="btn-xs btn-flat waves-effect waves-teal permissions-edit-btn right"><i class="material-icons" style="font-size:20px;">mode_edit</i></a></div>';
                
                return $html;
            });

            return $db->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query() {
        $role_id = null;

        $permissions = null;

        if ($role_id = $this->request()->get('role_id')) {
            $role = Role::find($role_id);

            $permissions = $role->permissions()->select();
        } else {
            $permissions = Permission::select();
        }

        if ($search_input = $this->request()->get('search_input')) {
            $permissions->where(function ($query) use ($search_input) {
                $query->where('id', 'LIKE', '%'.$search_input.'%')
                ->orWhere('name', 'LIKE', '%'.$search_input.'%')
                ->orWhere('display_name', 'LIKE', '%'.$search_input.'%')
                ->orWhere('description', 'LIKE', '%'.$search_input.'%')
                ->orWhere('created_at', 'LIKE', '%'.$search_input.'%');
            });  
        }
        
        return $permissions;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html() {
        return $this->builder()
            ->columns([
                'id',
                'name',
                'display_name',
                'description',
                'created_at',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload', 'delete'],
            ]);
    }
}
