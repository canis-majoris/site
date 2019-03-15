<?php

namespace App\DataTables\Transactions;

use App\Models\Tvoyo\User;
use App\Models\Region;
use App\Models\Paypal\PaypalTransaction;
use App\Models\Order\Order;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class PaypalTransactionsDataTable extends DataTable
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
            ->editColumn('created_at', function ($paypal_transaction) {
                return '<div class="deep-purple lighten-5 wrapper-custom-2 inline-flex-1"><i class="material-icons" style="">date_range</i> ' . $paypal_transaction->created_at . '</div>';
            })
            ->editColumn('user_id', function ($paypal_transaction) {
                $user = $paypal_transaction->user;
                $val = null;
                if ($user) {
                    $val = '<a href="'.route('users-show', $user->id).'" data-id="'.$user->id.'" class="btn-flat blue-text btn-extra-content" target="_blank"><b>'.$user->code.'</b>
                    <div class="clear"></div>
                    <div><small class="grey-text"><b>' . $user->username . '</b></small></div>
                    </a>';
                }

                return $val;
            })
            ->editColumn('after_payment_status', function ($paypal_transaction) {
                switch ($paypal_transaction->after_payment_status) {
                    case 'Completed Successfully': $bck_color = '#03A9F4'; $text = 'confirmed'; break;
                    default: $bck_color = '#FF9800'; $text = 'declined' ; break;
                }
                return '<div class="wrapper-custom-2 white-text" style="background-color:' . $bck_color . '; text-transform:uppercase; font-size:.8rem;"><b>' . $text . '</b></div>';
            })
            ->addColumn('payment_data', function ($paypal_transaction) {
                $json = $paypal_transaction->after_payment_data;
                $currency_sign ='$';
                if ($json) {
                    $json = str_replace(array( '"{', '}"' ), array( '{', '}' ), $json);
                    $data = json_decode($json, 1);

                    return '<b>' . $currency_sign . $data['mc_gross'] . '</b>' . (isset($data['mc_fee']) ? ' (fee: ' . $currency_sign . $data['mc_fee'] . ')' : '');

                    // $pattern = '/
                    // \{              # { character
                    //     (?:         # non-capturing group
                    //         [^{}]   # anything that is not a { or }
                    //         |       # OR
                    //         (?R)    # recurses the entire pattern
                    //     )*          # previous group zero or more times
                    // \}              # } character
                    // /x';

                    // preg_match_all($pattern, $tmp_json, $matches);
                }
            })
            
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query() {
        $paypal_transactions = PaypalTransaction::filterRegion()->where('after_payment_status', 'Completed Successfully')->select();

        if ($this->request()->has('status')) {
            $status = $this->request()->get('status');
            if ($status != 'any') {
                $paypal_transactions->where('after_payment_status', '=', $status);
            }
        }

        $dates = $this->request()->get('date');

        if ($date_start = $dates['date_start']) {
            $paypal_transactions->where('created_at', '>=', $date_start);
        }
        if ($date_end = $dates['date_end']) {
            $paypal_transactions->where('created_at', '<=', $date_end);
        }

        if ($this->request()->has('search_input')) {
            $search_input = $this->request()->get('search_input');
            if ($search_input != null) {
                $paypal_transactions->where(function ($query) use ($search_input) {
                    $query->where('id', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('transaction_id', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('user_id', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('type', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('after_payment_status', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('after_payment_data', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('created_at', 'LIKE', '%'.$search_input.'%')
                    ->orwhereHas('user', function ($query) use ($search_input) {
                        $query->where('users.email', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('users.code', 'LIKE', '%'.$search_input.'%');
                    });
                }); 
            }
        }

        return $this->applyScopes($paypal_transactions);
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
                'transaction_id',
                'user_id',
                'type',
                'after_payment_status',
                'created_at',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
