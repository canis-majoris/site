<?php

namespace App\DataTables;

use App\Models\Language;
use App\Models\Product\Product;
use App\Models\Region;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Currency;
use Yajra\Datatables\Services\DataTable;

class ProductListDataTable extends DataTable
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
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('price', function ($op) use ($region){
                $currency = Currency::where('region_id', $region)->first();
                return $op->price * $op->quantity .' '. $currency->sign;
            })
            ->editColumn('code', function ($op) { 
                return '<b>'.$op->code.'</b>';
            })
            ->editColumn('name', function ($op) { 
                $html = '';
                $product = $op->product;

                if ($product) {
                     $tr = $product->translated()/*->where('language_id', $this->default_language_id)*/->first();
                    if ($tr ) {
                        $st = $tr->short_text ?  ' <small>('.$tr->short_text.')</small>' : '';
                        $html .= '<span style="font-size:1.1rem;">'.$tr->name.''.$st.'</small></span>';
                    } else {
                        $st = $product->short_text ?  ' <small>('.$product->short_text.')</small>' : '';
                        $html .= '<span style="font-size:1.1rem;">'.$product->name.''.$st.'</span>';
                    }
                }

                return $html;
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
        $region = session('region_id') ? session('region_id') : 1;
        $ops = Order::where('region_id', $region)->find($this->request()->get('order_id'))->orders_products()->select();

        return $this->applyScopes($ops);
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
                'name',
                'price',
                'quantity'
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
