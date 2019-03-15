<?php

namespace App\DataTables\Settings;

use App\Models\Language;
use App\Models\Dealer\DealerStat;
use App\Models\Product\Product;
use App\Models\Tvoyo\User;
use App\Models\Region;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Models\Settings\Settings;
use Yajra\Datatables\Services\DataTable;

class OrderStatusDataTable extends DataTable
{
    // protected $printPreview  = 'path-to-print-preview-view';
    // protected $exportColumns = ['id', 'name'];
    // protected $printColumns  = '*';

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', function ($order_status) {
                return '<div class="right"><a data-id="'.$order_status->id.'" class="btn-xs btn-flat waves-effect waves-teal delete-order_status-btn right"><i class="material-icons" style="font-size:20px;">remove_red_eye</i></a><a data-id="'.$order_status->id.'" class="btn-xs btn-flat waves-effect waves-teal edit-order_status-btn right"><i class="material-icons" style="font-size:20px;">mode_edit</i></a></div>';
            })
            // ->editColumn('type', function ($statistic) { 
            //     switch ($statistic->type) {
            //         case 1: $icon = '<i class="material-icons">arrow_forward</i>'; $text = trans('main.users.manage.statistics.in'); $color = 'blue'; break;
            //         case 2: $icon = '<i class="material-icons">arrow_back</i>'; $text = trans('main.users.manage.statistics.out'); $color = 'grey'; break;
            //     }
            //     return '<span class="custom-inline-badge-1 pull-left white-text '.$color.'">'.$icon.' '.$text.'</span>';
            // })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $order_statuses = OrderStatus::/*filterRegion(false)->*/select();
        if ($order_statuses) {
            // if ($type = $this->request()->get('type')) {
            //     $statistics->where('type', $type);
            // }
            // if ($date_start = $this->request()->get('date_start')) {
            //     $statistics->where('date', '>=', $date_start);
            // }
            // if ($date_end = $this->request()->get('date_end')) {
            //     $statistics->where('date', '<=', $date_end);
            // }
            // if ($search_input = $this->request()->get('search_input')) {
            //     $statistics->where('id', 'LIKE', '%'.$search_input.'%')
            //         ->orWhere('user_id', 'LIKE', '%'.$search_input.'%')
            //         ->orWhere('type', 'LIKE', '%'.$search_input.'%')
            //         ->orWhere('why', 'LIKE', '%'.$search_input.'%')
            //         ->orWhere('order_id', 'LIKE', '%'.$search_input.'%')
            //         ->orWhere('summ', 'LIKE', '%'.$search_input.'%')
            //         ->orWhere('comment', 'LIKE', '%'.$search_input.'%');
            // }
        }
        return $this->applyScopes($order_statuses);
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
                'name',
                'default',
                'text',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
