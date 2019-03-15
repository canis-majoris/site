<?php

namespace App\DataTables\Promos;

use App\Models\Language;
use App\Models\Product\Product;
use App\Models\Region;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Currency;
use App\Models\Promo\Promo;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class PromosProductsDataTable extends DataTable
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
        $promo_id = $this->request()->get('promo_id');
        $promo = Promo::find($promo_id);
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('select', function ($product) use ($promo) {
                $checked = '';
                $disabled = 'disabled';
                $active_label = null;
                $percent_val = null;
                if ($promo) {
                    $product_promos = ($promo->products_percent != null ? json_decode($promo->products_percent, true) : null);
                    if ($product_promos) {
                       $percent_val = (isset($product_promos[$product->id]) ? $product_promos[$product->id] : null);

                        if(isset($product_promos[$product->id])) {
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
                    <input id="d_percent_'.$product->id.'" type="text" class="validate product_percent_value masked" name="d_percents['.$product->id.']" value="'.$percent_val.'" '.$disabled.' style="margin-bottom:0;">
                    <label for="d_percent_'.$product->id.'" class="'.$active_label.'">percent</label>
                </div></div>';
                return $html;
            })
            ->editColumn('name', function ($product) {
                $html = '';
                $tr = $product->translated()->where('language_id', 1)->first();
                if ($tr ) {
                    $st = $tr->short_text ?  ' <small>('.$tr->short_text.')</small>' : '';
                    $html .= '<span>'.$tr->name.''.$st.'</small></span>';
                } else {
                    $st = $product->short_text ?  ' <small>('.$product->short_text.')</small>' : '';
                    $html .= '<span>'.$product->name.''.$st.'</span>';
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
    public function query() {
        $products = Product::FilterRegion()->where('watch', 1)->select();
        return $this->applyScopes($products);
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
                'name',
                'price',
                'created_at'
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload', 'delete'],
            ]);
    }
}
 