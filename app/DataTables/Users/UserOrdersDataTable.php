<?php

namespace App\DataTables\Users;

use App\Models\Language;
use App\Models\Product\Product;
use App\Models\Product\ProductStatus;
use App\Models\Region;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Currency;
use App\Models\Tvoyo\User;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class UserOrdersDataTable extends DataTable
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
        $dt = $this->datatables
            ->eloquent($this->query())
            ->addColumn('color', function ($order) { 
                $status = $order->status()->first();
                $background = '';
                switch ($status->id) {
                    case 1: $background = '#03A9F4'; break;
                    case 2: $background = '#9E9E9E'; break;
                    case 3: $background = '#F44336'; break;
                    case 5: $background = '#9E9E9E'; break;
                    case 6: $background = '#9E9E9E'; break;
                    default: $background = '#9E9E9E'; break;
                }
                return '<div style="background-color: '.$background.';" class="filler-circle-1"></div>';
            })
            ->editColumn('code', function ($order) {
                return '<div class="product-name-holder-inline"><b>'.$order->code.'</b></div>';
            })
            ->editColumn('price', function ($order) use ($region){
                $currency = Currency::where('region_id', $region)->first();
                return '<span class="price-holder-inline">'.$currency->sign.$order->get_order_price().'</div>';
            })
            ->addColumn('action', function ($order) { 
                $disable_delete = '';
                $html = '<div style="max-width:100px;" class="pull-right"><a data-id="'.$order->id.'" data-type="order" class="btn-xs btn-flat waves-effect waves-teal show-order-btn"><i class="material-icons" style="font-size:20px;">mode_edit</i></a>';
                
                return $html;
            });

            return $dt->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $region = session('region_id') ? session('region_id') : 1;
        $orders = null;
        if ($user_id = $this->request()->get('user_id')) {
            $orders = User::where('region_id', $region)->find($user_id)->check_orders();
        }

        /*$menu_item_id = null;
        if ($this->request()->get('menu_item_id')) {
            $menu_item_id = $this->request()->get('menu_item_id');
            $menus_products_id = ProductMenu::find($menu_item_id)->id;
            $region_id = session('region_id') ? session('region_id') : $this->default_region_id;
            $products = Product::whereHas('menu_item', function($q) use($menus_products_id, $region_id){
                $q->where(config('database.region.'.$region_id.'.eshop_menus_products').'.eshop_menu_id', $menus_products_id);
            })->select();
        } else {
            $products = Product::select();
        }

        $language_id = $this->default_language_id;
        if ($this->request()->get('language_id')) {
            $language_id = $this->request()->get('language_id');
        }

        $products->where('language_id', $language_id)->where('deleted_at', null)->select();*/
        
        //return $this->applyScopes($orders);
        return $this->applyScopes($orders);
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
                'created_at',
                'code',
                'promoskidka',
                'user_id',
                'prolong_id',
            ])
            /*->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload', 'delete'],
            ])*/;
    }
}
