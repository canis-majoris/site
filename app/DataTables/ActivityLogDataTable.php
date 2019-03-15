<?php
namespace App\DataTables;

use App\User;
use App\Models\Region;
use App\Permission;
use App\Role;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class ActivityLogDataTable extends DataTable
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
            ->editColumn('user', function ($activityLog) {
                $username = null;
                if ($activityLog->user) {
                    $username = $activityLog->user->username;
                }
                return $username;
            })
            ->editColumn('permission', function ($activityLog) {
                $display_name = null;
                if ($activityLog->permission) {
                    $display_name = $activityLog->permission->display_name;
                }
                return $display_name;
            })
            ->editColumn('ip', function ($activityLog) {
                return '<div class="deep-purple lighten-5 wrapper-custom-2"><b>' . $activityLog->ip . '</b></div>';
            })
             ->editColumn('type', function ($activityLog) {
                switch ($activityLog->type) {
                    case 'allow': $bck_color = '#03A9F4'; break;
                    case 'deny': $bck_color = '#FF9800'; break;
                    default: $bck_color = '#FF9800'; break;
                }
                return '<div class="wrapper-custom-2 white-text" style="background-color:' . $bck_color . '; text-transform:uppercase; font-size:.8rem;"><b>' . $activityLog->type . '</b></div>';
            })
            ->editColumn('message', function ($activityLog) {
                return '<i>' . $activityLog->message . '</i>';
            })
            ->editColumn('region', function ($activityLog) {
                $region = 'Global';
                if ($activityLog->region) {
                    $region = $activityLog->region->name;
                }
                return $region;
            })
            ->editColumn('created_at', function ($activityLog) {
                return '<div class="deep-purple lighten-5 wrapper-custom-2 inline-flex-1"><i class="material-icons" style="">date_range</i> ' . $activityLog->created_at . '</div>';
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query() {
        $activity_logs = ActivityLog::filterRegion(false)->select();

        if ($this->request()->has('type')) {
            $type = $this->request()->get('type');
            if ($type != 'any') {
                $activity_logs->where('type', '=', $type);
            }
        }

        $dates = $this->request()->get('date');

        if ($date_start = $dates['date_start']) {
            $activity_logs->where('created_at', '>=', $date_start);
        }
        if ($date_end = $dates['date_end']) {
            $activity_logs->where('created_at', '<=', $date_end);
        }

        if ($this->request()->has('search_input')) {
            $search_input = $this->request()->get('search_input');
            if ($search_input != null) {
                $activity_logs->where(function ($query) use ($search_input) {
                    $query->where('id', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('method', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('ip', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('message', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('type', 'LIKE', '%'.$search_input.'%')
                    ->orWhere('created_at', 'LIKE', '%'.$search_input.'%')
                    ->orWhereHas('region', function ($query) use ($search_input) {
                        $query->where('regions.name', 'LIKE', '%'.$search_input.'%');
                    })
                    ->orWhereHas('permission', function ($query) use ($search_input) {
                        $query->where('permissions.name', 'LIKE', '%'.$search_input.'%')
                            ->orWhere('permissions.display_name', 'LIKE', '%'.$search_input.'%')
                            ->orWhere('permissions.description', 'LIKE', '%'.$search_input.'%');
                    })
                    ->orWhereHas('user', function ($query) use ($search_input) {
                        $query->where('users.username', 'LIKE', '%'.$search_input.'%')
                            ->orWhere('users.email', 'LIKE', '%'.$search_input.'%');
                    });
                }); 
            }
        }


        if ($this->request()->has('region')) {
            $regions = $this->request()->get('region');
            if ($regions != 'any') {
                $activity_logs->whereIn('region_id', $regions);
            } else {
               // $activity_logs->where('region_id', null);
            }
        }

        return $this->applyScopes($activity_logs);
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
                'method',
                'ip',
                'user_id',
                'module_id',
                'permission_id',
                'type',
                'message',
                'created_at',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
