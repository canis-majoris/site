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

class MobileServicesDataTable extends DataTable
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
            ->addColumn('color', function ($service) { 
                $status = $service->products_statuse_id;
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
            ->editColumn('name', function ($service) {
                $name = '';
                $st = '';
                $product = $service->product;
                $tr = $product->translated()/*->where('language_id', $this->default_language_id)*/->first();
                if ($tr ) {
                    $st = $tr->short_text ?  ' <small>('.$tr->short_text.')</small>' : '';
                    $name .= '<span style="font-size:1.1rem;">'.$tr->name.''.$st.'</small></span>';
                } else {
                    $st = $product->short_text ?  ' <small>('.$product->short_text.')</small>' : '';
                    $name .= '<span style="font-size:1.1rem;">'.$product->name.''.$st.'</span>';
                }
                                    
                $html = '<div class="product-name-holder-inline"><b>'.$name.'</b></div>';
                $ret=$service->get_data_end();
                if ($service->yearly == 1) {
                    $html .= '<div>оплата за первый и последний месяц</div>';
                }
                if ($service->order()->count() > 0) {
                    $html .= '<div>Код заказа: <b>'.$service->order()->first()->code.'</b></div>';
                }

                $html .= '<div>Код сервиса: <b>'.$service->code.'</b></div>';

                if (isset($ret['result']) && $ret['result']) {
                    $html .= '<div>Сервис до: <b>'.$ret["ok"].'</b></div>';
                }
                    
                if($status = $service->products_statuse_id) {
                    $r_status = ProductStatus::filterRegion(false)->find($status);
                    $color = '#9E9E9E';
                    switch ($r_status->id) {
                        case 1:
                        case 5:
                        case 11: $color = '#4CAF50'; break;
                        case 6:
                        case 13: $color = '#FFC107'; break;
                        case 3:
                        case 14: $color = '#9E9E9E'; break;
                    }
                    $html .= '<div style="color:'.$color.'"><b>'.$r_status->name.'</b></div>';
                }


                return $html;

            })
            ->editColumn('account', function ($service) {
                $html = '<div>Username: <b>'.$service->mob_account_id.'</b></div>
                <div>Password: <b>'.$service->service_password.'</b></div>
                <div>Email: <b>'.$service->user()->first()->email.'</b></div>';

                return $html;
            })
            ->editColumn('price', function ($service) use ($region){
                $currency = Currency::where('region_id', $region)->first();
                return '<span class="price-holder-inline">'.$currency->sign.round($service->price*$service->quantity, 2).'</div>';
            })
            ->addColumn('control', function ($service) {
                $disabled = '';
                $messages = null;
                $overlay = '';
                $statuses = $service->service_statuses();

                $status = ($service->products_statuse_id == 5 ? 'checked' : null);
                $html = '<div class="switch m-b-md" style="position:relative;">
                    <label>
                        Деакт.
                        <input type="checkbox" name="service_status" '.$disabled.' data-id="'.$service->id.'" class="change-status" '.$status.' data-type="mobile_services">
                        <span class="lever"></span>
                        Акт.
                    </label>
                    '.$overlay.'
                </div>';
                return $html;
            })
            ->addColumn('comment', function ($service) { 
                $html = '';
                if ($service->comment) {
                    $html .= '<div class="costom-commen-holder-inline blue-grey" style="max-width:210px;">
                        <span class="white-text"><i class="material-icons" style="font-size:1.5rem;">format_quote</i> '.$service->comment.'</span>
                    </div';
                }

                /*$html = '<div class="input-field col s12">
                    <button class="waves-effect waves-blue btn-flat comment_modal-trigger" data-id="'.$service->id.'" data-comment="'.$service->comment.'">comment</button>
                </div>';*/
                return $html;
            })
            ->addColumn('action', function ($service) { 
                $disable_delete = ($service->products_statuse_id == 5 ? 'disabled' : '');
                $html = '<div class="right"><a data-id="'.$service->id.'" data-type="mobile_services" '.$disable_delete.' class="btn-xs btn-flat waves-effect waves-red products-delete-btn right"><i class="material-icons" style="font-size:20px;">delete_forever</i></a><a data-id="'.$service->id.'" data-type="mobile_service" class="btn-xs btn-flat waves-effect waves-teal products-edit-btn tooltipped right" data-position="top" data-delay="50" data-tooltip="'.trans('main.misc.edit').'"><i class="material-icons" style="font-size:20px;">mode_edit</i></a></div>';
                
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
        $mobile_services = null;
        if ($user_id = $this->request()->get('user_id')) {
            $mobile_services = User::where('region_id', $region)->find($user_id)->get_mobile_services();
            $mobile_services->where(function ($query) {
                $query->whereHas('order', function ($query){
                    $query->where('orders.orders_status_id', '!=', 3);
                    $query->where('orders.prolong_id', 0);
                })
                ->orWhereHas('order', function ($query){}, '<', 1);
            });

            if ($orders_filtered = $this->request()->get('orders_filtered')) {
                $mobile_services->whereHas('order', function ($query) use ($orders_filtered){
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
        
        return $this->applyScopes($mobile_services);
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
                'mob_account_id',
                'service_password'
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload', 'delete'],
            ]);
    }
}
 