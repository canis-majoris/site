<?php

namespace App\DataTables\Promos;

use App\Models\Language;
use App\Models\Product\Product;
use App\Models\Region;
use App\Models\Currency;
use App\Models\Promo\Promo;
use Yajra\Datatables\Services\DataTable;

class PromosDataTable extends DataTable
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
            ->addColumn('color', function ($promo) { 
                $status = $promo->status;
                $color = ($status == 1 ? '#03A9F4' : '#9E9E9E');
                return '<div style="background-color: '.$color.';" class="filler-circle-1"></div>';
            })
            ->editColumn('status', function ($promo) { 
                $activated_class = $promo->status == 1 ? 'active' : null;
                return '<button data-id="'.$promo->id.'" class="btn btn-xs stat-change '.$activated_class.'"><i class="material-icons">check</i></button>';
            })
            ->editColumn('limit', function ($promo) { 
                $limit = $promo->limit > 0 ? $promo->limit : 'неограниченный';
                return $limit;
            })
            ->editColumn('code', function ($promo) { 
                return '<b>'.$promo->code.'</b>';
            })
            ->addColumn('action', function ($promo) {
                return '<div class="right"><a data-id="'.$promo->id.'" class="btn-xs btn-flat waves-effect waves-teal track-promo-btn right"><i class="material-icons" style="font-size:20px;">remove_red_eye</i></a><a data-id="'.$promo->id.'" class="btn-xs btn-flat waves-effect waves-teal show-promo-btn right"><i class="material-icons" style="font-size:20px;">mode_edit</i></a></div>';
            })
            ->addColumn('discount_type', function ($promo) use ($region) {
                $html = '';
                if ($promo->type == 1) {
                    $html .= '<div class="chip">Скидка на общую сумму</div>';
                } elseif ($promo->type == 2 || $promo->type == 3) {
                    if ($promo->type == 2) {
                        $disc_t_1 = 'Скидка на пакеты';
                    } else $disc_t_1 = 'Скидка на товары';
                    
                    $html .= '<div class="chip">'.$disc_t_1.' (';
                    if ($promo->type_discount == 1) {
                        $html .= 'На один товар';
                    } elseif ($promo->type_discount == 2) {
                        $html .= 'На все товары';
                    }
                    $html .= ')</div>';
                }
                
                return $html;
            })
            ->addColumn('discount_percents', function ($promo) use ($region) {
                $html = '';
                if ($promo->type == 1) {
                    $html .= '<div class="chip">'.$promo->percent.'%</div>';
                } elseif ($promo->type == 2 || $promo->type == 3) {
                    $products_percent = json_decode($promo->products_percent, true);
                    if (count($products_percent) && is_array($products_percent)) {
                        $products = Product::where('region_id', $region)->find(array_keys($products_percent));
                        foreach ($products as $product) {
                            $html .= '<div class="chip">'.$products_percent[$product->id].'% - '.$product->name.'</div>';
                        }
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
        $promos = Promo::filterRegion()->select();

        if ($status = $this->request()->get('status')) {
            switch ($status) {
                case 1: $status = 1; break;
                case 2: $status = 0; break;
            }

            $promos->where('status', $status);
        }

        if ($type = $this->request()->get('type')) {
            $promos->where('type', $type);
        }

        $dates = $this->request()->get('date');

        if ($date_start_from = $dates['date_start_from']) {
            $promos->where('date_start', '>', $date_start_from);  
        }
        if ($date_start_to = $dates['date_start_to']) {
            $promos->where('date_start', '<', $date_start_to);  
        }
        if ($date_end_from = $dates['date_end_from']) {
            $promos->where('date_end', '>', $date_end_from);  
        }
        if ($date_end_to = $dates['date_end_to']) {
            $promos->where('date_end', '<', $date_end_to);  
        }

        if ($search_input = $this->request()->get('search_input')) {
            $promos->where(function ($query) use ($search_input) {
                $query->where('code', 'LIKE', '%'.$search_input.'%');
            }); 
        }

        

        return $this->applyScopes($promos);
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
                'code',
                'type',
                'date_start',
                'date_end',
                'limit',
                'percent',
                'products',
                'products_percent',
                'status',
                'created_at',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
