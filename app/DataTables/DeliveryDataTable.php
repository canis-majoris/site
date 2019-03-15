<?php

namespace App\DataTables;

use App\Models\Delivery;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class DeliveryDataTable extends DataTable
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
            ->addColumn('status', function ($delivery) { 
                $activated_class = $delivery->hide == 0 ? 'active' : null;
                return '<button data-id="'.$delivery->id.'" class="btn btn-xs stat-change '.$activated_class.'"><i class="material-icons">check</i></button>';
            })
            ->addColumn('action', function ($delivery) {
                return '<a data-id="'.$delivery->id.'" class="btn-xs btn-flat waves-effect waves-teal delivery-edit-btn right"><i class="material-icons" style="font-size:20px;">mode_edit</i></a>';
            })
            ->editColumn('price', function ($delivery) use ($region){
                $currency = Currency::where('region_id', $region)->first();
                return $currency->sign.$delivery->price;
            })
            ->editColumn('img', function ($delivery) use ($region){
                $img = null;
                if ($delivery->img != null && $delivery->img != '' && $delivery->img != '[]') {
                    $img = array_values(json_decode($delivery->img))[0];
                    return '<div class="table_image_holder-small"><img src="'.url('/img/delivery').'/'.$img.'"  alt="'.$delivery->name.'" class="materialboxed responsive-img" data-caption="'.$delivery->name.'"></div>';
                }
                return null;
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
        $region = session('region_id') ? session('region_id') : 1;
        $delivery = Delivery::where('region_id', $region)->select();

        if ($this->request()->get('status') != null) {
            $act = $this->request()->get('status');
            if ($act != 'any') {
                $delivery->where('hide', '=', $act);
            }
        }

        if ($search_input = $this->request()->get('search_input')) {
            $delivery->where(function ($query) use ($search_input) {
                $query->where('id', 'LIKE', '%'.$search_input.'%')
                ->orWhere('hide', 'LIKE', '%'.$search_input.'%')
                ->orWhere('name', 'LIKE', '%'.$search_input.'%')
                ->orWhere('img', 'LIKE', '%'.$search_input.'%')
                ->orWhere('price', 'LIKE', '%'.$search_input.'%');
            });  
        }

        return $this->applyScopes($delivery);
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
                'name',
                'image',
                'comment',
                'price',
                'hide',
                'created_at',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
            ]);
    }
}
