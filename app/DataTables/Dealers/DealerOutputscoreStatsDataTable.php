<?php

namespace App\DataTables\Dealers;

use App\Models\Language;
use App\Models\Dealer\DealerStat;
use App\Models\Dealer\DealerOutputscoreStat;
use App\Models\Product\Product;
use App\Models\Tvoyo\User;
use App\Models\Region;
use App\Models\Order\Order;
use App\Models\Currency;
use Yajra\Datatables\Services\DataTable;

class DealerOutputscoreStatsDataTable extends DataTable
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
            // ->editColumn('type', function ($statistic) { 
            //     switch ($statistic->type) {
            //         case 1: $icon = '<i class="material-icons">arrow_forward</i>'; $text = trans('main.users.manage.statistics.in'); $color = 'blue'; break;
            //         case 2: $icon = '<i class="material-icons">arrow_back</i>'; $text = trans('main.users.manage.statistics.out'); $color = 'grey'; break;
            //         case 3: $icon = '<i class="material-icons">star_rate</i>'; $text = trans('main.users.manage.statistics.deposit'); $color = 'green'; break;
            //     }
            //     return '<span class="custom-inline-badge-1 pull-left white-text '.$color.'">'.$icon.' '.$text.'</span>';
            // })
            // ->editColumn('summ', function ($statistic) use ($region) {
            //     $currency = Currency::where('region_id', $region)->first();
            //     return $statistic->summ .''. $currency->sign;
            // })
            // ->editColumn('created_at', function ($statistic){
                
            //     return '<span class="custom-inline-badge-1"><i class="material-icons">date_range</i> '.$statistic->created_at.'</span>';
            // })
            // ->editColumn('why', function ($statistic) use ($region) {
            //     $color = 'blue-text';
            //     $disabled = '';
            //     if (!Order::where('region_id', $region)->find($statistic->order_id)) {
            //         $color = 'red-text';
            //         $disabled = 'disabled';
            //     }
            //     return '<button class="btn-flat '.$color.' show-order-btn" data-id="'.$statistic->order_id.'" '.$disabled.'>'.$statistic->why.'</button>';
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
        $os_statistics = null;
        if ($user_id = $this->request()->get('user_id')) {
            $user = User::filterRegion()->find($user_id);
            if ($user) {
                $os_statistics = $user->dealer_outputscore_stats()->select();

                if ($status = $this->request()->get('status')) {
                    $os_statistics->where('status', $status);
                }
                if ($date_start = $this->request()->get('date_start')) {
                    $os_statistics->where('date', '>=', $date_start);
                }
                if ($date_end = $this->request()->get('date_end')) {
                    $os_statistics->where('date', '<=', $date_end);
                }
                if ($search_input = $this->request()->get('search_input')) {
                    $os_statistics->where('id', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('user_id', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('status', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('date', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('start_date', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('summ', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('comment', 'LIKE', '%'.$search_input.'%');
                }
            }
        }

        return $this->applyScopes($os_statistics);
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
                'status',
                'start_date',
                'date',
                'summ',
                'comment',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
