<?php

namespace App\DataTables\Dealers;

use App\Models\Language;
use App\Models\Dealer\DealerStat;
use App\Models\Product\Product;
use App\Models\Tvoyo\User;
use App\Models\Region;
use App\Models\Order\Order;
use App\Models\Currency;
use Yajra\Datatables\Services\DataTable;

class DealerStatsDataTable extends DataTable
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
            ->editColumn('type', function ($statistic) { 
                switch ($statistic->type) {
                    case 1: $icon = '<i class="material-icons">arrow_forward</i>'; $text = trans('main.users.manage.statistics.in'); $color = 'blue'; break;
                    case 2: $icon = '<i class="material-icons">arrow_back</i>'; $text = trans('main.users.manage.statistics.out'); $color = 'grey'; break;
                    case 3: $icon = '<i class="material-icons">star_rate</i>'; $text = trans('main.users.manage.statistics.deposit'); $color = 'green'; break;
                }
                return '<span class="custom-inline-badge-1 pull-left white-text '.$color.'">'.$icon.' '.$text.'</span>';
            })
            ->editColumn('summ', function ($statistic) use ($region) {
                $currency = Currency::where('region_id', $region)->first();
                return $statistic->summ .''. $currency->sign;
            })
            ->editColumn('created_at', function ($statistic){
                 return '<div class="deep-purple lighten-5 wrapper-custom-2 inline-flex-1"><i class="material-icons" style="">date_range</i> ' . $statistic->created_at . '</div>';
            })
            ->editColumn('why', function ($statistic) use ($region) {
                $color = 'blue-text';
                $disabled = '';
                if (!Order::where('region_id', $region)->find($statistic->order_id)) {
                    $color = 'red-text';
                    $disabled = 'disabled';
                }
                return '<button class="btn-flat '.$color.' show-order-btn" data-id="'.$statistic->order_id.'" '.$disabled.'>'.$statistic->why.'</button>';
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
        $statistics = null;
        if ($user_id = $this->request()->get('user_id')) {
            $user = User::where('region_id', $region)->find($user_id);
            if ($user) {
                $statistics = $user->dealer_stats()->select();

                if ($type = $this->request()->get('type')) {
                    $statistics->where('type', $type);
                }
                if ($date_start = $this->request()->get('date_start')) {
                    $statistics->where('date', '>=', $date_start);
                }
                if ($date_end = $this->request()->get('date_end')) {
                    $statistics->where('date', '<=', $date_end);
                }
                if ($search_input = $this->request()->get('search_input')) {
                    $statistics->where('id', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('user_id', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('type', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('why', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('order_id', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('summ', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('comment', 'LIKE', '%'.$search_input.'%');
                }
            }
        }

        return $this->applyScopes($statistics);
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
                'user_id',
                'type',
                'why',
                'order_id',
                'summ',
                'comment',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
