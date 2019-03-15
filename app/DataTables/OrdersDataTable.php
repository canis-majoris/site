<?php

namespace App\DataTables;

use App\Models\Language;
use App\Models\Product\Product;
use App\Models\Region;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Currency;
use App\Models\CartuTransaction;
use App\Models\Paypal\PaypalTransaction;
use Yajra\Datatables\Services\DataTable;

class OrdersDataTable extends DataTable
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
            ->addColumn('color', function ($order) { 
                $status = $order->status()->first();
                $background = '';
                switch ($status->id) {
                    case 1: $background = '#03A9F4'; break;
                    case 2: $background = '#4CAF50'; break;
                    case 3: $background = '#F44336'; break;
                    case 5: $background = '#9E9E9E'; break;
                    case 6: $background = '#4CAF50'; break;
                    default: $background = '#9E9E9E'; break;
                }
                return '<div style="background-color: '.$background.';" class="filler-circle-1"></div>';
            })
            ->addColumn('status', function ($order) {
                $color = '#9E9E9E';
                if ($order->status()->first()->id == 3) {
                    $color = '#F44336';
                }
                return '<span style="color: '.$color.';">'.$order->status()->first()->name.'</span>';
            })
            ->editColumn('date', function ($order) { 
                return '<div class="deep-purple lighten-5 wrapper-custom-2 inline-flex-1"><i class="material-icons" style="">date_range</i> ' . $order->date . '</div>';
            })   
            ->editColumn('code', function ($order) { 
                return '<a href="'.route('orders-show', $order->id).'" data-id="'.$order->id.'" class="btn-flat blue-text" target="_blank"><b>'.$order->code.'</b></a>';
            })
            ->addColumn('user', function ($order) {
                $user = $order->user()->find($order->user_id);
                $val = null;
                if ($user) {
                    $val = '<a href="'.route('users-show', $user->id).'" data-id="'.$user->id.'" class="btn-flat blue-text btn-extra-content" target="_blank"><b>'.$user->code.'</b>
                    <div class="clear"></div>
                    <div><small class="grey-text"><b>' . $user->username . '</b></small></div>
                    </a>';
                }

                return $val;
            })
            ->addColumn('price', function ($order) {
                $currency = Currency::filterRegion()->first();
                return '<b>' . $currency->sign . $order->get_order_price() . '</b>';
            })
            ->editColumn('globtotal', function ($order) { 
               // return ($order->get_order_price() - $order->globtotal) != 0 ? $order->get_order_price() - $order->globtotal : '';
               return ($order->user->email != $order->email && $order->user->username != $order->email) ? $order->user->username . ' - ' . $order->email : '';
            })
            ->addColumn('tracking', function ($order) {
                //return $order->get_order_price();
            })
            ->addColumn('ups', function ($order) {
                $html = '';
                if($order->ups && stripos($order->ups,'ups') != false) {
                    $html .= '<div class="inline-info-block shadow-1-1">' . $order->ups . '<br>';
                    if (!$order->ups_img) {
                        $html .= '<a href="javascript:;" onclick="sticker_query('.$order->id.', this)">Запрос на стикер</a>
                        <a style="display:none" class="upsopnr" href=""
                           onclick="window.open(this.href, "targetWindow", "_blank,width=810,height=650");
                                   return false;">
                            Показать стикер
                        </a>';
                    } else {
                    
                         $html .= '<a class="upsopnr" href="/orders/get/usticker?id='.$order->id.'"
                           onclick="window.open(this.href, \'targetWindow\', \'_blank,width=810,height=650\');
                                   return false;">
                            Показать стикер
                        </a>';
                    }
                    $html .= '</div>';
                }
                return $html;
            })
            ->addColumn('transaction', function ($order) {
                $html = '';
                switch ($order->pay_type) {
                    case 0:
                        if (CartuTransaction::filterRegion()->where("m_desc", $order->orderid)->where("result", "Y")->first()) {
                            $html .= trans('main.orders.manage.by_card');

                            $trans_text = null;
                            if ($order->transop1 == 1) {
                                $trans_text = trans('main.misc.confirmed');
                            } elseif ($order->transop1 == 2) {
                                $trans_text = trans('main.misc.canceled');
                            } elseif ($order->transop1 == 3) {
                                $trans_text = trans('main.misc.returned');
                            }

                            $html .= '<br><span class="green-text"><b>' . $trans_text . '</b></span>';
                        }
                        break;
                    case 1: $html .= trans('main.orders.manage.part_from_score'); break;
                    case 2: $html .= trans('main.orders.manage.from_score'); break;
                    case 3: $html .= trans('main.orders.manage.by_cash'); break;
                   // case 3: $html .= trans('main.orders.manage.by_cash'); break;
                    case 10: 
                        if (PaypalTransaction::filterRegion()->where("transaction_id", $order->orderid)->where("after_payment_status", "Completed Successfully")->first()) {
                            $html .= trans('main.orders.manage.by_paypal');
                        }
                        break;
                    default: $html .= trans('main.orders.manage.by_card'); break;
                }

                return $html;
            })
            ->editColumn('comment', function ($order) { 
                $comment = '';
                if ($op = OrderProduct::find($order->prolong_id)) {
                    $code = $op->code;
                    $comment = $order->prolong_id != 0 ? '<div class="flex-center"><i class="material-icons accent-1" style="margin: 0 .75rem; font-size:1.5rem;">subscriptions</i> <i>Продление сервиса <b>' . $code . '</i></b></div>' : null;
                }
               
                return $comment;
            })
           /* ->addColumn('action', function ($order) {
                return '<div style="max-width:100px;" class="pull-right"><a data-id="'.$order->id.'" class="btn-xs btn-flat waves-effect waves-teal show-order-btn"><i class="material-icons" style="font-size:20px;">mode_edit</i></a></div>';
            })*/
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $orders = Order::filterRegion()->select();

        if ($status = $this->request()->get('status')) {
            if ($status != 'any') {
                $orders->whereIn('orders_status_id', $status);
            } 
        }
        if ($date_start = $this->request()->get('date_start')) {
            $orders->where('date', '>=', $date_start);
        }
        if ($date_end = $this->request()->get('date_end')) {
            $orders->where('date', '<=', $date_end);
        }
        if ($search_input = $this->request()->get('search_input')) {
            $orders/*->where('region_id', $region)*/
                ->where(function ($query) use ($search_input){
                    $query->where('id', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('user_id', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('name', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('email', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('code', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('user_comment', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('admin_comment', 'LIKE', '%'.$search_input.'%')
                    ->orWhereHas('user', function ($query) use ($search_input) {
                        $query->where('name', 'LIKE', '%'.$search_input.'%')
                            ->orWhere('code', 'LIKE', '%'.$search_input.'%')
                            ->orWhere('email', 'LIKE', '%'.$search_input.'%')
                            ->orWhere('phone', 'LIKE', '%'.$search_input.'%');
                    });
                });
                
        }

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
                'email',
                'comment',
                'globtotal'
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
