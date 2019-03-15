<?php

namespace App\DataTables\Users;

use App\Models\Language;
use App\Models\Product\Product;
use App\Models\Region;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Currency;
use App\Models\Tvoyo\User;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class GoodsDataTable extends DataTable
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
            ->editColumn('name', function ($goods) {
                $name = '';
                $st = '';
                $product = $goods->product;
                $tr = $product->translated()/*->where('language_id', $this->default_language_id)*/->first();
                if ($tr ) {
                    $st = $tr->short_text ?  ' <small>('.$tr->short_text.')</small>' : '';
                    $name .= '<span style="font-size:1.1rem;">'.$tr->name.''.$st.'</small></span>';
                } else {
                    $st = $product->short_text ?  ' <small>('.$product->short_text.')</small>' : '';
                    $name .= '<span style="font-size:1.1rem;">'.$product->name.''.$st.'</span>';
                }

                $html = '<div class="product-name-holder-inline"><b>'.$name.'</b> </div>';
                if ($goods->order()->count() > 0) {
                    $html .= '<div>Код заказа: <b>'.$goods->order()->first()->code.'</b></div>';
                }

                $html .= '<div>Код продукта: <b>'.$goods->code.'</b></div>';

                return $html;
            })
            ->editColumn('price', function ($goods) use ($region){
                $currency = Currency::where('region_id', $region)->first();
                return '<span class="price-holder-inline">'.$currency->sign.round($goods->price*$goods->quantity, 2).'</div>';
            })
            ->addColumn('comment', function ($goods) { 
                $html = '';
                if ($goods->comment) {
                    $html .= '<div class="costom-commen-holder-inline blue-grey" style="max-width:210px;">
                        <span class="white-text"><i class="material-icons" style="font-size:1.5rem;">format_quote</i> '.$goods->comment.'</span>
                    </div';
                }

                /*$html = '<div class="input-field col s12">
                    <button class="waves-effect waves-blue btn-flat comment_modal-trigger" data-id="'.$service->id.'" data-comment="'.$service->comment.'">comment</button>
                </div>';*/
                return $html;
            })
            ->addColumn('action', function ($goods) { 
                $disable_delete = '';
                $html = '<div class="right"><a data-id="'.$goods->id.'" data-type="goods" '.$disable_delete.' class="btn-xs btn-flat waves-effect waves-red products-delete-btn right"><i class="material-icons" style="font-size:20px;">delete_forever</i></a><a data-id="'.$goods->id.'" data-type="goods" class="btn-xs btn-flat waves-effect waves-teal products-edit-btn tooltipped right" data-position="top" data-delay="50" data-tooltip="'.trans('main.misc.edit').'"><i class="material-icons" style="font-size:20px;">mode_edit</i></a></div>';
                
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
        $goods = null;
        if ($user_id = $this->request()->get('user_id')) {
            $goods = User::where('region_id', $region)->find($user_id)->get_goods();
            $goods->where(function ($query) {
                $query->whereHas('order', function ($query){
                    $query->where('orders.orders_status_id', '!=', 3);
                })
                ->orWhereHas('order', function ($query){}, '<', 1);
            });

            if ($orders_filtered = $this->request()->get('orders_filtered')) {
                $goods->whereHas('order', function ($query) use ($orders_filtered){
                    $query->whereIn('orders.id', $orders_filtered);
                });
            }
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
        
        //return $this->applyScopes($goods);
        return $goods;
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
                //'created_at',
                'code',
                'name',
                'mac',
                'comment',
                'order_id',
                'prolong_id',
                'date_stop',
                'products_statuse_id',
                'date_month_end',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload', 'delete'],
            ]);
    }
}
