<?php

namespace App\DataTables;

use App\Models\Language;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class LanguagesDataTable extends DataTable
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
            ->editColumn('status', function ($language) { 
                $activated_class = $language->watch ? 'active' : null;
                return '<button data-id="'.$language->id.'" class="btn btn-xs stat-change '.$activated_class.'"><i class="material-icons">check</i></button>';})
            ->addColumn('action', function ($language) {
                return '<a data-id="'.$language->id.'" class="btn-xs btn-flat waves-effect waves-teal language-edit-btn right"><i class="material-icons" style="font-size:20px;">mode_edit</i></a>';
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
        //$users = User::changeConnection('mysql_billing_remote_eu')->get();
        //$users = DB::connection('mysql_billing_remote_eu')->select('users')->get();

        $languages = Language::filterRegion()->select();

        if ($this->request()->get('status') != null) {
            $act = $this->request()->get('status');
            if ($act != 'any') {
                $languages->where('watch', '=', $act);
            }
        }

        return $this->applyScopes($languages);
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
                'language',
                'language_code',
                'watch',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
