<?php

namespace App\DataTables\Transactions;

use App\Models\Tvoyo\User;
use App\Models\Region;
use App\Models\CartuTransaction;
use App\Models\Order\Order;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class CartuTransactionsDataTable extends DataTable
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
            // ->addColumn('user', function ($cartu_transaction) {
            //     $transaction = $cartu_transaction->transaction()->first();

            //     return count($transaction);

            //     if ($transaction) {
            //         return $transaction->id;
            //         $user = $transaction->user;
            //         if ($user) {
            //             return $user->email;
            //         }
                    
            //     }
            // })
            ->editColumn('result', function ($cartu_transaction) {
                switch ($cartu_transaction->result) {
                    case 'Y': $bck_color = '#03A9F4'; $text = 'confirmed'; break;
                    case 'U': $bck_color = '#FF9800'; $text = 'declined'; break;
                    default: $bck_color = '#FF9800'; $text = $activityLog->result ; break;
                }
                return '<div class="wrapper-custom-2 white-text" style="background-color:' . $bck_color . '; text-transform:uppercase; font-size:.8rem;"><b>' . $text . '</b></div>';
            })
            ->editColumn('m_amt', function ($cartu_transaction) {
                // if ($cartu_transaction->m_currency == '840')
                //     $currency_sign ='$';
                // if ($cartu_transaction->m_currency == '981')
                //     $currency_sign ='₾';
                // if ($cartu_transaction->m_currency == '978')
                //     $currency_sign ='€';


                $currency_sign ='€';
                return '<b>' . $currency_sign . number_format($cartu_transaction->m_amt/100, 2) . '</b>';
            })
            ->editColumn('m_ip', function ($cartu_transaction) {
                return '<div class="deep-purple lighten-5 wrapper-custom-2"><b>' . $cartu_transaction->m_ip . '</b></div>';
            })
            ->editColumn('date', function ($cartu_transaction) {
                return '<div class="deep-purple lighten-5 wrapper-custom-2 inline-flex-1"><i class="material-icons" style="">date_range</i> ' . $cartu_transaction->date . '</div>';
            })

            
            // ->editColumn('ip', function ($activityLog) {
            //     return '<div class="deep-purple lighten-5 wrapper-custom-2"><b>' . $activityLog->ip . '</b></div>';
            // })
            //  ->editColumn('type', function ($activityLog) {
            //     switch ($activityLog->type) {
            //         case 'allow': $bck_color = '#03A9F4'; break;
            //         case 'deny': $bck_color = '#FF9800'; break;
            //         default: $bck_color = '#FF9800'; break;
            //     }
            //     return '<div class="wrapper-custom-2 white-text" style="background-color:' . $bck_color . '; text-transform:uppercase; font-size:.8rem;"><b>' . $activityLog->type . '</b></div>';
            // })
            // ->editColumn('message', function ($activityLog) {
            //     return '<i>' . $activityLog->message . '</i>';
            // })
            // ->editColumn('region', function ($activityLog) {
            //     $region = 'Global';
            //     if ($activityLog->region) {
            //         $region = $activityLog->region->name;
            //     }
            //     return $region;
            // })
            // ->editColumn('created_at', function ($activityLog) {
            //     return '<div class="deep-purple lighten-5 wrapper-custom-2"><i class="material-icons" style="">date_range</i> ' . $activityLog->created_at . '</div>';
            // })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query() {
        $cartu_transactions = CartuTransaction::filterRegion()->whereIn('result', ['Y', 'U'])->select();

        if ($this->request()->has('status')) {
            $status = $this->request()->get('status');
            if ($status != 'any') {
                $cartu_transactions->where('result', '=', $status);
            }
        }

        $dates = $this->request()->get('date');

        if ($date_start = $dates['date_start']) {
            $cartu_transactions->where('date', '>=', $date_start);
        }
        if ($date_end = $dates['date_end']) {
            $cartu_transactions->where('date', '<=', $date_end);
        }

        if ($this->request()->has('search_input')) {
            $search_input = $this->request()->get('search_input');
            if ($search_input != null) {
                $cartu_transactions->where(function ($query) use ($search_input) {
                    $query->where('id', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('m_msg_type', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('m_ip', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('merchant_id', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('m_trans_id', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('m_amt', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('m_currency', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('m_desc', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('m_leng', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('m_ip', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('b_trans_id', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('result', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('result_code', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('rrn', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('approval_code', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('date', 'LIKE', '%'.$search_input.'%')
                    ->orWhereHas('transaction', function ($query) use ($search_input) {
                        $query->whereHas('user', function ($query) use ($search_input) {
                            $query->where('users.email', 'LIKE', '%'.$search_input.'%')
                            ->orWhere('users.code', 'LIKE', '%'.$search_input.'%');
                        })->orWhereHas('order', function ($query) use ($search_input) {
                            $query->where('orders.code', 'LIKE', '%'.$search_input.'%');
                        });
                    });
                }); 
            }
        }

        return $this->applyScopes($cartu_transactions);
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
                'm_msg_type',
                'merchant_id',
                'm_trans_id',
                'm_amt',
                'm_currency',
                'm_desc',
                'm_leng',
                'm_ip',
                'b_trans_id',
                'result',
                'result_code',
                'rrn',
                'approval_code',
                'card_number',
                'user_id',
                'date',
                'created_at',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
