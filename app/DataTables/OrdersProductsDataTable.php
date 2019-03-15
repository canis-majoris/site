<?php

namespace App\DataTables;

use App\Language;
use App\Product;
use App\Region;
use App\Order;
use App\OrderProduct;
use App\Currency;
use Yajra\Datatables\Services\DataTable;

class OrdersProductsDataTable extends DataTable
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
            ->addColumn('status', function ($order) {
                return $order->status()->first()->name;
            })
            ->addColumn('user', function ($order) {
                $user = $order->user()->find($order->user_id);
                $val = null;
                if ($user) {
                    $val = '<a data-id="'.$user->id.'" class="show-user-btn btn-flat blue-text">'.$user->code.'</a>';
                }

                return $val;
            })
            ->addColumn('price', function ($order) {
                $currency = Currency::find(Region::find(session('region_id')?session('region_id'):$this->default_region_id)->id);
                return $order->get_order_price() .' '. $currency->sign;
            })
            ->addColumn('tracking', function ($order) {
                //return $order->get_order_price();
            })
            ->addColumn('ups', function ($order) {
                //return $order->get_order_price();
            })
            ->editColumn('comment', function ($order) { 
                $prolonged = $order->prolong_id != 0 ? 'Продление сервиса ' . OrderProduct::first()->generate_service_code($order->prolong_id) : null;
                return $prolonged;
            })
            /*->editColumn('code', function ($order) { 
                return '<a class="show-order-btn" data-id="'.$order->id.'">'.$order->code.'</a>';
            })*/
            ->addColumn('action', function ($order) {
                return '<a data-id="'.$order->id.'" class="btn-xs btn-flat waves-effect waves-teal show-order-btn"><i class="material-icons" style="font-size:20px;">mode_edit</i></a>';
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
        $orders = OrderProduct::select();

        if ($status = $this->request()->get('status')) {
            $orders->where('orders_status_id', $status);
        }
        if ($date_start = $this->request()->get('date_start')) {
            $orders->where('date', '>=', $date_start);
        }
        if ($date_end = $this->request()->get('date_end')) {
            $orders->where('date', '<=', $date_end);
        }
        if ($search_input = $this->request()->get('search_input')) {
            $orders->where('id', 'LIKE', '%'.$search_input.'%')
                ->orWhere('user_id', 'LIKE', '%'.$search_input.'%')
                ->orWhere('name', 'LIKE', '%'.$search_input.'%')
                ->orWhere('email', 'LIKE', '%'.$search_input.'%')
                ->orWhere('code', 'LIKE', '%'.$search_input.'%')
                ->orWhere('user_comment', 'LIKE', '%'.$search_input.'%')
                ->orWhere('admin_comment', 'LIKE', '%'.$search_input.'%');
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
                'comment'
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
