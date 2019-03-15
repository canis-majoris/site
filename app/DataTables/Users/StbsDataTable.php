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

class StbsDataTable extends DataTable
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
            ->addColumn('color', function ($stb) { 
                $status = $stb->products_statuse_id;
                $background = '#9E9E9E';
                switch ($status) {
                    case 1:
                    case 5:
                    case 11: $background = '#4CAF50'; break;
                    case 6:
                    case 13: $background = '#FFC107'; break;
                    case 3:
                    case 14: $background = '#9E9E9E'; break;
                }
                return '<div style="background-color: '.$background.';" class="filler-circle-1"></div>';
            })
            ->editColumn('name', function ($stb) {
                $name = '';
                $st = '';
                $product = $stb->product;
                $tr = $product->translated()/*->where('language_id', $this->default_language_id)*/->first();
                if ($tr ) {
                    $st = $tr->short_text ?  ' <small>('.$tr->short_text.')</small>' : '';
                    $name .= '<span style="font-size:1.1rem;">'.$tr->name.''.$st.'</small></span>';
                } else {
                    $st = $product->short_text ?  ' <small>('.$product->short_text.')</small>' : '';
                    $name .= '<span style="font-size:1.1rem;">'.$product->name.''.$st.'</span>';
                }

                $html = '<div class="product-name-holder-inline"><b>'.$name.'</b> </div>';
                if ($stb->order()->count() > 0) {
                    $html .= '<div>Код заказа: <b>'.$stb->order()->first()->code.'</b></div>';
                }

                $html .= '<div>Код приставки: <b>'.$stb->code.'</b></div>
                        <div><b style="color:green" data-id="'.$stb->id.'">MAC: '.strtoupper($stb->mac).'</b></div>';

                return $html;
            })
            ->editColumn('price', function ($stb) use ($region){
                $currency = Currency::where('region_id', $region)->first();
                return '<span class="price-holder-inline">'.$currency->sign.round($stb->price*$stb->quantity, 2).'</div>';
            })
            ->editColumn('service', function ($stb) {
                $attached_services = $stb->get_stb_service();
                $html = '';
                if ($service = $attached_services->first()) {
                    //foreach ($attached_services as $service) {
                    $status = $service->products_statuse_id;
                    $color = '#9E9E9E';
                    if ($status) {
                        switch ($status) {
                            case 1:
                            case 5:
                            case 11: $color = '#4CAF50'; break;
                            case 6:
                            case 13: $color = '#FFC107'; break;
                            case 3:
                            case 14: $color = '#9E9E9E'; break;
                        }
                    }
                    $html .= '<div><span style="color:'.$color.';"><b>'.$service->code.'</b>';
                    $ret = $service->get_data_end();
                    if(!$ret["error"])
                        $html .=  ' до '.$ret['ok'].'</span>';
                    //}

                    return $html.'</div>';
                }
            })
            ->addColumn('control', function ($stb) {
                $disabled = '';
                $overlay = '';
                $ser = $stb->get_stb_service()->first();
                if ($ser && $ser->products_statuse_id == 5) {
                    $disabled = 'disabled';
                    $overlay = '<span class="disabled-switch-overlay" data-message="'.trans('main.users.items.stbs.messages.stb_has_active_service').'"></span>';
                }

                if (!$stb->mac) {
                    $disabled = 'disabled';
                    $overlay = '<span class="disabled-switch-overlay" data-message="'.trans('main.users.items.stbs.messages.mac_not_assigned').'"></span>';
                }
                $status = ($stb->products_statuse_id == 1 ? 'checked' : null);
                $html = '<div class="switch m-b-md" style="position:relative;">
                    <label>
                        Деакт.
                        <input type="checkbox" name="stb_status" '.$disabled.' data-id="'.$stb->id.'" class="change-status" '.$status.' data-type="stbs">
                        <span class="lever"></span>
                        Акт.
                    </label>
                    '.$overlay.'
                </div>';
                return $html;
            })
            ->addColumn('comment', function ($stb) { 
                $html = '';
                if ($stb->comment) {
                    $html .= '<div class="costom-commen-holder-inline blue-grey" style="max-width:210px;">
                        <span class="white-text"><i class="material-icons" style="font-size:1.5rem;">format_quote</i> '.$stb->comment.'</span>
                    </div';
                }

                /*$html = '<div class="input-field col s12">
                    <button class="waves-effect waves-blue btn-flat comment_modal-trigger" data-id="'.$service->id.'" data-comment="'.$service->comment.'">comment</button>
                </div>';*/
                return $html;
            })
            ->addColumn('action', function ($stb) { 
                $disable_delete = (($stb->get_stb_service()->count() > 0 || $stb->products_statuse_id == 1) ? 'disabled' : '');
                $delete_tooltip = trans('main.misc.delete');
                if ($disable_delete == 'disabled') {
                    $delete_tooltip = trans('main.users.items.stbs.messages.stb_has_service_or_is_turned_on');
                }
                $html = '<div style="" class="right"><a data-id="'.$stb->id.'" data-type="stbs" '.$disable_delete.' class="btn-xs btn-flat waves-effect waves-red products-delete-btn right" data-position="top" data-delay="50" data-tooltip="'.$delete_tooltip.'"><i class="material-icons" style="font-size:20px;">delete_forever</i></a><a data-id="'.$stb->id.'" data-type="stb" class="btn-xs btn-flat waves-effect waves-teal products-edit-btn tooltipped right" data-position="top" data-delay="50" data-tooltip="'.trans('main.misc.edit').'"><i class="material-icons" style="font-size:20px;">mode_edit</i></a></div>';
                
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
        $stbs = null;
        if ($user_id = $this->request()->get('user_id')) {
            $stbs = User::filterRegion()->find($user_id)->get_ps2();
            $stbs->where(function ($query) {
                $query->whereHas('order', function ($query){
                    $query->where('orders.orders_status_id', '!=', 3);
                })
                ->orWhereHas('order', function ($query){}, '<', 1);
            });

            if ($orders_filtered = $this->request()->get('orders_filtered')) {
                $stbs->whereHas('order', function ($query) use ($orders_filtered){
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
        
        //return $this->applyScopes($stbs);
        return $stbs;
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
