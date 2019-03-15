<?php

namespace App\DataTables\Discounts;

use App\Models\Language;
use App\Models\Product\Product;
use App\Models\Region;
use App\Models\Discount\Discount;
use App\Models\Discount\DiscountProduct;
use App\Models\Currency;
use Yajra\Datatables\Services\DataTable;

class DiscountsDataTable extends DataTable
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
            ->addColumn('color', function ($discount) { 
                $status = $discount->status;
                $color = ($status == 1 ? '#03A9F4' : '#9E9E9E');
                return '<div style="background-color: '.$color.';" class="filler-circle-1"></div>';
            })
            ->editColumn('status', function ($discount) { 
                $activated_class = $discount->status == 1 ? 'active' : null;
                return '<button data-id="'.$discount->id.'" class="btn btn-xs stat-change '.$activated_class.'"><i class="material-icons">check</i></button>';
            })
            ->editColumn('limit', function ($discount) { 
                $limit = $discount->limit > 0 ? $discount->limit : 'неограниченный';
                return $limit;
            })
            ->addColumn('user', function ($discount) {

                $text = null;
                if ($dealer = $discount->dealers()->first()) {
                    $text = '<a data-id="'.$dealer->id.'" class="show-user-btn btn-flat blue-text"><b>'.$dealer->code.'</b></a>';
                } elseif ($user_count = $discount->users()->count()) {
                    $text = $user_count.' user(s)';
                }

                return $text;
            })
            ->editColumn('code', function ($discount) { 
                return '<b>'.$discount->code.'</b>';
            })
            ->addColumn('action', function ($discount) {
                return '<div class="right"><a data-id="'.$discount->id.'" class="btn-xs btn-flat waves-effect waves-teal track-discount-btn right"><i class="material-icons" style="font-size:20px;">remove_red_eye</i></a><a data-id="'.$discount->id.'" class="btn-xs btn-flat waves-effect waves-teal assign-to-users-btn right"><i class="material-icons" style="font-size:20px;">assignment_ind</i></a><a data-id="'.$discount->id.'" class="btn-xs btn-flat waves-effect waves-teal show-discount-btn right"><i class="material-icons" style="font-size:20px;">mode_edit</i></a></div>';
            })
            ->addColumn('discount_type', function ($discount) use ($region) {
                $html = '';
                if ($discount->type == 1) {
                    $html .= '<div class="chip">Скидка на общую сумму</div>';
                } elseif ($discount->type == 2 || $discount->type == 3) {
                    if ($discount->type == 2) {
                        $disc_t_1 = 'Скидка на пакеты';
                    } else $disc_t_1 = 'Скидка на товары';
                    
                    $html .= '<div class="chip">'.$disc_t_1.' (';
                    if ($discount->type_discount == 1) {
                        $html .= 'На один товар';
                    } elseif ($discount->type_discount == 2) {
                        $html .= 'На все товары';
                    }
                    $html .= ')</div>';
                }
                
                return $html;
            })
            ->addColumn('discount_percents', function ($discount) use ($region) {
                $html = '';
                if ($discount->type == 1) {
                    $html .= '<div class="chip">'.$discount->percent.'%</div>';
                } elseif ($discount->type == 2 || $discount->type == 3) {
                    $products_percent = json_decode($discount->products_percent, true);
                    if (count($products_percent)) {
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
        $discounts = Discount::where('region_id', $region)->select();

        if ($status = $this->request()->get('status')) {
            switch ($status) {
                case 1: $status = 1; break;
                case 2: $status = 0; break;
            }

            $discounts->where('status', $status);
        }

        if ($type = $this->request()->get('type')) {
            $discounts->where('type', $type);
        }

        $dates = $this->request()->get('date');

        if ($date_start_from = $dates['date_start_from']) {
            $discounts->where('date_start', '>', $date_start_from);  
        }
        if ($date_start_to = $dates['date_start_to']) {
            $discounts->where('date_start', '<', $date_start_to);  
        }
        if ($date_end_from = $dates['date_end_from']) {
            $discounts->where('date_end', '>', $date_end_from);  
        }
        if ($date_end_to = $dates['date_end_to']) {
            $discounts->where('date_end', '<', $date_end_to);  
        }

        if ($search_input = $this->request()->get('search_input')) {
            $discounts->where(function ($query) use ($search_input) {
                $query->where('code', 'LIKE', '%'.$search_input.'%')
                ->orWhereHas('dealers', function ($query) use ($search_input) {
                    $query->where('users.name', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('users.email', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('users.code', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('users.username', 'LIKE', '%'.$search_input.'%');
                });
            }); 
        }

        

        return $this->applyScopes($discounts);
    }

    /**
     * Optional method we you want to use html builder.
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
