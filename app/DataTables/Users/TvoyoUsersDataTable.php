<?php

namespace App\DataTables\Users;

use App\Models\Tvoyo\User;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;


class TvoyoUsersDataTable extends DataTable
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
        $region = session('region_id') ? session('region_id') : 1;
        $type = $this->request()->get('type');
        $db = $this->datatables
            ->eloquent($this->query())
            ->editColumn('status', function ($user) { 
                $activated_class = $user->activated ? 'active' : null;
                return '<button data-id="'.$user->id.'" class="btn btn-xs stat-change '.$activated_class.'"><i class="material-icons">check</i></button>';})
            ->editColumn('activity', function ($user) { 
                return ($user->active_service == 1 ? 'Активный' : 'Не активный');
            })
            ->editColumn('code', function ($user) { 
                return '<a href="'.route('users-show', $user->id).'" data-id="'.$user->id.'" class="btn-flat blue-text" target="_blank"><b>'.$user->code.'</b></a>';
            })
            ->editColumn('dealer', function ($user) { 
                if ($u = User::find($user->dealer_id)) {
                    return '<a href="'.route('users-show', $u->id).'" data-id="'.$u->id.'" class="btn-flat blue-text" target="_blank"><b>'.$u->code.'</b></a>';
                }
                return null;
            })
            ->addColumn('statistics', function ($user) {
                $html = null;
                if ($user->is_diller == 1 || $user->score_user == 1) {
                    $html = '<a data-id="'.$user->id.'" class="btn-xs btn-flat waves-effect waves-teal show-user-statistics-btn tooltipped" data-position="top" data-delay="50" data-tooltip="' . trans('main.misc.show_statistics') . '"><i class="material-icons" style="font-size:20px;">insert_chart</i></a>
                    <a data-id="'.$user->id.'" class="btn-xs btn-flat waves-effect waves-teal show-user-balance-btn tooltipped" data-position="top" data-delay="50" data-tooltip="' . trans('main.misc.show_balance') . '"><i class="material-icons" style="font-size:20px;">account_balance_wallet</i></a>';
                }
                return $html;
            })
            ->addColumn('action', function ($user) {

                return '<div style="max-width:100px;" class="pull-right"><a data-id="'.$user->id.'" class="btn-xs btn-flat waves-effect waves-teal show-user-btn"><i class="material-icons" style="font-size:20px;">mode_edit</i></a></div>';
            })
            ->addColumn('remove', function ($user) use($type) {
                $html = '';
                if ($type == 'cash_payers') {
                    $html = '<a data-id="'.$user->id.'" class="btn-xs btn-flat waves-effect waves-red remove_from_cash_payers-user-btn"><i class="material-icons" style="font-size:20px;">remove_circle</i></a>';
                }

                return $html;
            });

        return $db->make(true);
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
        $region = session('region_id') ? session('region_id') : 1;
        if ($type = $this->request()->get('type')) {
            switch ($type) {
                case 'all':
                    $users = User::where('region_id', $region)->select(); break;
                case 'cash_payers':
                    $users = User::where('region_id', $region)->where('cash_payment_status', 1)->select()/*->where('multir_ind', 1)*/; break;
            }
        }

        $dates = $this->request()->get('date');

        // $users->whereHas('orders_products', function($query) {

        //     $query->where("is_service", 1)
        //     ->where("stoped", "!=", 1)
        //     ->where("date_end", ">", time())
        //     ->whereHas('product', function ($query) {
        //         $query->where('products.for_mobile', '=', 0)
        //         ->where('products.is_goods', '!=', 1);
        //     });
        // }, '=', 0);

        //var_dump($dates['date_start']);

        if ($date_start = $dates['date_start']) {
            $users->where('reg_date', '>=', $date_start);
        }
        if ($date_end = $dates['date_end']) {
            $users->where('reg_date', '<=', $date_end);
        }

        if ($this->request()->has('status')) {
            $status = $this->request()->get('status');
            if ($status != 2) {
                $users->where('activated', '=', $status);
            }
        }

        if ($only_dealers = $this->request()->get('only_dealers')) {
                $users->where('is_diller', '=', 1);
            }

        if ($this->request()->has('activated')) {
            $activated = $this->request()->get('activated');
            if ($activated != 2) {
                $users->where('active_service', $activated);
            } 
        }

        if ($this->request()->has('custom1')) {
            $custom1 = $this->request()->get('custom1');
            if ($custom1 != 'any') {
                $users->whereIn('active_service', $custom1);
            }
        }

        if ($this->request()->has('active_service_type_id')) {
            $active_service_type_id = $this->request()->get('active_service_type_id');
            //var_dump($active_service_type_id); die;
            if ($active_service_type_id) {
                $users->whereHas('orders_products', function($query) use($active_service_type_id) {
                    $query->whereHas('product',  function($query) use($active_service_type_id) {
                        $query->whereIn('id', $active_service_type_id);
                    });
                });
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
                'activated',
                'code',
                'name',
                'city',
                'email',
                'phone',
                'active_service',
                'created_at',
                'updated_at',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
