<?php

namespace App\DataTables;

use App\Models\Language;
use App\Models\Product\Product;
use App\Models\Region;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class ProductsLogsDataTable extends DataTable
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
            /*->editColumn('watch', function ($product) { 
                $activated_class = $product->watch ? 'active' : null;
                return '<button data-id="'.$product->id.'" class="btn btn-xs stat-change '.$activated_class.'"><i class="material-icons">check</i></button>';})
            ->addColumn('action', function ($product) {
                return '<a data-id="'.$product->id.'" class="btn-xs btn-flat waves-effect waves-teal products-edit-btn"><i class="material-icons" style="font-size:20px;">mode_edit</i></a>
                        <a data-id="'.$product->id.'" class="btn-xs btn-flat waves-effect waves-red products-delete-btn"><i class="material-icons" style="font-size:20px;">delete_forever</i></a>';
            })*/
            ->editColumn('status', function ($log) {
                $status = $log->product_status()->first();
                return $status->name;
            })
            ->addColumn('action', function ($log) {
                $html = '<div style="max-width:100px;" class="pull-right"><a data-id="'.$log->id.'" data-type="log" class="btn-xs btn-flat waves-effect waves-red log-delete-btn"><i class="material-icons" style="font-size:20px;">delete_forever</i></a></div>';

                return $html;
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query() {
        $region = session('region_id') ? session('region_id') : 1;
        $logs = null;
        if ($product_id = $this->request()->get('product_id')) {
            $logs = OrderProduct::where('region_id', $region)->find($product_id)->logs();
        }

        return $logs;
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
                'orders_product_id',
                'products_statuse_id',
                'comment',
                'owner',
                'created_at'
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload', 'delete'],
            ]);
    }
}
 