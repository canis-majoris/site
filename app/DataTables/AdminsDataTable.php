<?php

namespace App\DataTables;

use App\User;
use App\Models\Region;
use Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class AdminsDataTable extends DataTable
{
    // protected $printPreview  = 'path-to-print-preview-view';
    // protected $exportColumns = ['id', 'name'];
    // protected $printColumns  = '*';
    private $default_language_id = 1;
    private $default_region_id = 1;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('avatar', function ($admin) {
                if ($admin->avatar) {
                    return '<div class="table_image_holder-small"><img src="'.url('/img/admins/avatars').'/'.$admin->avatar.'"  alt="'.$admin->name.'" class="materialboxed responsive-img" data-caption="'.$admin->name.'"></div>';
                }
                return null;
            })
            ->editColumn('status', function ($admin) { 
                $activated_class = $admin->status ? 'active' : null;
                return '<button data-id="'.$admin->id.'" class="btn btn-xs stat-change '.$activated_class.'"><i class="material-icons">check</i></button>';})
            ->addColumn('action', function ($admin) {
                $html = '';
                if (Auth::user()->can('admins-update-all') || $admin->id == Auth::user()->id) {
                   $html = '<a data-id="'.$admin->id.'" class="btn-xs btn-flat waves-effect waves-teal admin-edit-btn right"><i class="material-icons" style="font-size:20px;">mode_edit</i></a>';
                }
                return $html;
            })
            ->addColumn('region', function ($admin) {
                $html = '';
                if ($admin->region && $admin->region != 'all') {
                    $regions = json_decode($admin->region, true);
                    foreach ($regions as $region) {
                        $html .= '<div class="chip">'.Region::find($region)->name.'</div>';
                    }
                } elseif ($admin->region == 'all') {
                    $html .= '<div class="chip purple lighten-5">Все регионы</div>';
                }
                return $html;
            })
            ->addColumn('roles', function ($admin) {
                $html = '';
                foreach ($admin->roles()->get() as $role) {
                    $html .= '<div class="chip">'.$role->display_name.'</div>';
                }

                return $html;
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        //$users = User::changeConnection('mysql_billing_remote_eu')->get();
        //$users = DB::connection('mysql_billing_remote_eu')->select('users')->get();

        if ($this->request()->get('region') != null) {
            $regions = $this->request()->get('region');
            if ($regions != 'any') {
                $ids = [];
                foreach (User::with('roles')->get() as $u) {
                    //var_dump($u->region);
                    if ($u->region && $u->region != 'all') {
                        $reg_arr = json_decode($u->region, true);
                        if (!array_diff($regions, $reg_arr)) {
                            $ids []= $u->id;
                        }
                    } elseif ($u->region == 'all') {
                        $ids []= $u->id;
                    }
                }
                $users = User::with('roles')->whereIn('id', $ids)->select();
            } else $users = User::with('roles')->select();
        } else $users = User::with('roles')->select();

        if ($this->request()->get('status') != null) {
            $act = $this->request()->get('status');
            if ($act != 'any') {
                $users->where('status', '=', $act);
            }
        }





        return $this->applyScopes($users);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns([
                'id',
                'username',
                'status',
                'email',
                'last_login',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
