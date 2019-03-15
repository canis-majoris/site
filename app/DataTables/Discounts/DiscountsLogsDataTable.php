<?php

namespace App\DataTables\Discounts;

use App\Models\Language;
use App\Models\Discount\DiscountUsed;
use App\Models\Discount\Discount;
use App\Models\Product\Product;
use App\Models\Region;
use App\Models\Order\Order;
use App\Models\Currency;
use App\Models\Tvoyo\User;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class DiscountsLogsDataTable extends DataTable
{
    // protected $printPreview  = 'path-to-print-preview-view';
    // protected $exportColumns = ['id', 'name'];
    // protected $printColumns  = '*';
    private $default_language_id = 1;
    private $default_region_id = 1;
    private $region;

   /* public function __construct() {
        parent::__construct();
        $region_id = session('region_id') ? session('region_id') : $this->default_region_id;
        $this->domain = config('database.region.'.$this->region.'.domain');
        $this->region = Region::find($region_id);
        //$this->remote_connection = config('database.region.'.$this->region.'.database_remote');
    }*/

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function ajax()
    {
        $region = session('region_id') ? session('region_id') : 1;
       /* $discount_id = $this->request()->get('discount_id');
        $discount = Discount::find($discount_id);*/
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('user_id', function ($log) use ($region) {
                $user = User::where('region_id', $region)->find($log->user_id);
                $val = '';
                if ($user) {
                    $val = '<a href="'.route('users-show', $user->id).'" data-id="'.$user->id.'" class="btn-flat blue-text btn-extra-content" target="_blank"><b>'.$user->code.'</b>
                        <div class="clear"></div>
                        <div><small class="grey-text"><b>' . $user->username . '</b></small></div>
                        </a>';
                }
                return $val;
            })
            ->editColumn('order_id', function ($log) use ($region) {
                $order = Order::where('region_id', $region)->find($log->order_id);
                $html = '';
                if ($order) {
                    $html = '<a class="btn-flat waves-effect blue-text show-order-btn" data-id="'.$order->id.'"><b>'.$order->code.'</b></a>';
                }
                return $html;
            })
            ->editColumn('created_at', function ($log) use ($region) {
                return '<div class="deep-purple lighten-5 wrapper-custom-2 inline-flex-1"><i class="material-icons" style="">date_range</i> ' . $log->created_at . '</div>';
            })
            
            /*->editColumn('select', function ($product) use ($discount) {
                $checked = '';
                $disabled = 'disabled';
                $active_label = null;
                $percent_val = null;
                if ($discount) {
                    $product_discounts = ($discount->products_percent != null ? json_decode($discount->products_percent, true) : null);
                    if ($product_discounts) {
                       $percent_val = (isset($product_discounts[$product->id]) ? $product_discounts[$product->id] : null);

                        if(isset($product_discounts[$product->id])) {
                            $checked = "checked";
                            $disabled = '';
                        }

                        if ($percent_val) {
                            $active_label = 'active';
                        }
                    }
                }

                $html = '<div class="col s12 percent_select_holder"><div class="input-field col pull-left" style="width:60px;">
                    <span class="input-wrapper-span-1">
                        <input type="checkbox" id="d_product_'.$product->id.'" name="d_products['.$product->id.']"  class="product_percent_check" '.$checked.'>
                        <label for="d_product_'.$product->id.'"></label>
                    </span>
                </div>';

                $html .= '<div class="input-field col l8 m6 s6">
                    <input id="d_percent_'.$product->id.'" type="number" class="validate product_percent_value" name="d_percents['.$product->id.']" value="'.$percent_val.'" '.$disabled.' max="100" min="-100" style="margin-bottom:0;">
                    <label for="d_percent_'.$product->id.'" class="'.$active_label.'">percent</label>
                </div></div>';
                return $html;
            })*/
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query() {
        $region = session('region_id') ? session('region_id') : 1;
        $log = null;
        if ($discount_id = $this->request()->get('discount_id')) {
            $log = Discount::where('region_id', $region)->find($discount_id)->log()->select();
        }

        if ($date_start = $this->request()->get('date_start')) {
            $log->where('created_at', '>=', $date_start);
        }
        if ($date_end = $this->request()->get('date_end')) {
            $log->where('created_at', '<=', $date_end);
        }

        if ($search_input = $this->request()->get('search_input')) {
            $log->where(function ($query) use ($search_input) {
                $query->where('id', 'LIKE', '%'.$search_input.'%')
                ->orWhere('user_id', 'LIKE', '%'.$search_input.'%')
                ->orWhere('order_id', 'LIKE', '%'.$search_input.'%')
                ->orWhere('discount_id', 'LIKE', '%'.$search_input.'%')
                ->orWhere('created_at', 'LIKE', '%'.$search_input.'%');
            }); 
        }

        if ($log_user = $this->request()->get('log_user')) {
            $log->where(function ($query) use ($log_user) {
                $query->where('user_id', 'LIKE', '%'.$log_user.'%')
                ->orWhereHas('user', function ($query) use ($log_user) {
                    $query->where('users.name', 'LIKE', '%'.$log_user.'%')
                        ->orWhere('users.email', 'LIKE', '%'.$log_user.'%')
                        ->orWhere('users.code', 'LIKE', '%'.$log_user.'%');
                });
            }); 
        }

        if ($log_order = $this->request()->get('log_order')) {
            $log->where(function ($query) use ($log_order) {
                $query->where('order_id', 'LIKE', '%'.$log_order.'%')
                ->orWhereHas('order', function ($query) use ($log_order) {
                    $query->where('orders.code', 'LIKE', '%'.$log_order.'%')
                        ->orWhere('orders.price', 'LIKE', '%'.$log_order.'%');
                });
            }); 
        }

        return $this->applyScopes($log);
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
                'region_id',
                'discount_id',
                'order_id',
                'user_id',
                'created_at'
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload', 'delete'],
            ]);
    }
}
 