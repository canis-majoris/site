<?php

namespace App\DataTables;

use App\User;
use App\Models\Region;
use App\Permission;
use App\Role;
use App\Models\BillingLog;
use App\Models\Order\OrderProduct;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class BillingLogDataTable extends DataTable
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
            // ->editColumn('user', function ($activityLog) {
            //     $username = null;
            //     if ($activityLog->user) {
            //         $username = $activityLog->user->username;
            //     }
            //     return $username;
            // })
            ->editColumn('type', function ($billingLog) {
                return ucfirst(implode(' ', explode('_', $billingLog->type)));
            })
             ->editColumn('status', function ($billingLog) {
                switch ($billingLog->status) {
                    case 1: $bck_color = '#03A9F4'; $text = 'completed'; break;
                    case 0: $bck_color = '#FF9800'; $text = 'interrupted'; break;
                    default: $bck_color = '#FF9800'; $text = 'completed'; break;
                }
                return '<div class="wrapper-custom-2 white-text" style="background-color:' . $bck_color . '; text-transform:uppercase; font-size:.8rem;"><b>' . $text. '</b></div>';
            })
            ->editColumn('text', function ($billingLog) {
                $html = '<div class="inline-log-container-1">';
                $array = json_decode($billingLog->text, 1);
                if ($billingLog->type == 'daily_subscription_managemant_results') {
                   foreach ($array as $service => $log) {
                        $real_service = OrderProduct::where('code', $service)->first();

                        $user_id = null;
                        if ($real_service) {
                            $user_id = $real_service->user->id;
                        }
                        
                        $message_color = 'gray';
                        if ($log['message'] == 'subscription paused') {
                            $message_color = 'orange';
                        }

                        $html .= '<div class="inline-log-wrapper">
                                <b><a href="'.route('users-show', $user_id).'" data-id="'.$user_id.'" class="blue-text" target="_blank">' . $service . '</a>: </b>
                                <span style="color: ' . $message_color . ';"><i>' . $log['message'] . '</i></span>
                                <span>' . (isset($log['day(s) left']) ? '(' . $log['day(s) left'] . 'day(s) left)' : '') . '</span>
                            </div>';
                   }
                } elseif ($billingLog->type == 'daily_service_deactivation_results') {
                    foreach ($array as $service => $log) {
                        $packages = '';
                        $macs = '';
                        foreach ($log['PACKAGES DEACTIVATION LOG'] as $mac_addr => $mac_log) {
                            $macs .= '<span class="custom-inline-badge-1 white-text grey" style="margin-right: 0.5rem;">' . strtoupper($mac_addr) . '</span>';
                            $packages = (isset($mac_log['CHANGE PACKAGE RESULT']) ? '(' . implode(', ', array_keys($mac_log['CHANGE PACKAGE RESULT'])) . ')' : '');
                        }

                        $real_service = OrderProduct::where('code', $service)->first();

                        $user_id = null;
                        if ($real_service) {
                            $user_id = $real_service->user->id;
                        }

                        $next_service = null;
                        if ($log['ACTIVATE SERVICE IN QUEUE']['NEXT SERVICE'] != 'no new service available') {
                            
                            foreach ($log['ACTIVATE SERVICE IN QUEUE']['NEXT SERVICE'] as $new_service => $new_service_parameters) {
                                $new_macs = '';
                                foreach ($new_service_parameters['MACS'] as $new_mac) {
                                    $new_macs .= '<span class="custom-inline-badge-1 white-text grey" style="margin-right: 0.5rem;">' . strtoupper($new_mac) . '</span>';
                                }
                                
                                $next_service .= '<span><b><a href="'.route('users-show', $user_id).'" data-id="'.$user_id.'" class="blue-text" target="_blank">' . $new_service . '</a></b>: mac(s) ' . $new_macs . '</span>';
                            }
                            
                        } else $next_service = $log['ACTIVATE SERVICE IN QUEUE']['NEXT SERVICE'];

                        $message_color = '#F44336';
                        // if ($log['message'] == 'subscription paused') {
                        //     $message_color = 'orange';
                        // }

                        $html .= '<div class="inline-log-wrapper">
                                <b><a href="'.route('users-show', $user_id).'" data-id="'.$user_id.'" class="blue-text" target="_blank">' . $service . '</a>: </b>
                                <span style="color: ' . $message_color . ';"> service deactivated on mac(s) ' . $macs . ' <i>with package(s): <b>' . $packages . '</b></i>, </span>
                                <span> next service: ' . $next_service . '</span>
                            </div>';
                   }
                   //return $billingLog->text;
                } elseif ($billingLog->type == 'cartu_transaction_update') {
                    # code...
                }

                return $html . '</div>';
            })
            ->addColumn('show_log', function ($billingLog) {

                $html = null;
                if (in_array($billingLog->type, ['daily_service_deactivation_results', 'daily_subscription_managemant_results', 'cartu_transaction_update'])) {
                     $html = '<a data-id="'.$billingLog->id.'" class="btn-xs btn-flat waves-effect waves-blue show_log-btn right"><i class="material-icons" style="font-size:20px;">remove_red_eye</i></a>';
                }
                return $html;
            })
            ->editColumn('created_at', function ($billingLog) {
                return '<div class="deep-purple lighten-5 wrapper-custom-2 inline-flex-1"><i class="material-icons" style="">date_range</i> ' . $billingLog->created_at . '</div>';
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query() {
        $billing_logs = BillingLog::where('type', '!=', 'mobile_service_deactivation')->select();

        if ($this->request()->has('type')) {
            $type = $this->request()->get('type');
            if ($type != 'any') {
                $billing_logs->whereIn('type', $type);
            }
        }

        $dates = $this->request()->get('date');

        if ($date_start = $dates['date_start']) {
            $billing_logs->where('created_at', '>=', $date_start);
        }
        if ($date_end = $dates['date_end']) {
            $billing_logs->where('created_at', '<=', $date_end);
        }

        if ($this->request()->has('search_input')) {
            $search_input = $this->request()->get('search_input');
            if ($search_input != null) {
                $billing_logs->where(function ($query) use ($search_input) {
                    $query->where('id', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('text', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('description', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('status', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('type', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('created_at', 'LIKE', '%'.$search_input.'%')
                    ->orWhereHas('region', function ($query) use ($search_input) {
                        $query->where('regions.name', 'LIKE', '%'.$search_input.'%');
                    });
                }); 
            }
        }


        if ($this->request()->has('region')) {
            $regions = $this->request()->get('region');
            if ($regions != 'any') {
                $billing_logs->whereIn('region_id', $regions);
            } else {
                $billing_logs->where('region_id', null);
            }
        }

        return $this->applyScopes($billing_logs);
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
                'text',
                'status',
                'type',
                'description',
                'created_at',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
