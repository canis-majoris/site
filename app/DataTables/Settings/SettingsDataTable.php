<?php

namespace App\DataTables\Settings;

use App\Models\Region;
use App\Models\Settings\Settings;
use App\Models\Settings\SettingsType;
use App\Models\Settings\SettingsLanguage;
use App\Models\language;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class SettingsDataTable extends DataTable
{

    private $default_language_id = 1;
    private $default_region_id = 1;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function ajax() {

        $language_id = $this->request()->get('language_id', $this->default_language_id);
        $db = $this->datatables
            ->eloquent($this->query())
            ->editColumn('name', function ($settings) use($language_id) { 
                $html = '';
                $tr = $settings->translated()->where('language_id', $language_id)->first();
                if ($tr ) {
                    $html .= '<span>'.$tr->name.'</small></span>';
                }

                return $html;
            })
            ->editColumn('status', function ($settings) { 
                $activated_class = $settings->status ? 'active' : null;
                return '<button data-id="'.$settings->id.'" class="btn btn-xs stat-change '.$activated_class.'"><i class="material-icons">check</i></button>';})
            ->addColumn('region', function ($settings) { 
                return $settings->region_id ? Region::find($settings->region_id)->name : 'Global';
            })
            ->addColumn('action', function ($settings) { 
                $html = '<div style="max-width:100px;" class="pull-right"><a data-id="'.$settings->id.'" data-type="settings" class="btn-xs btn-flat waves-effect waves-teal settings-edit-btn tooltipped" data-position="top" data-delay="50" data-tooltip="'.trans('main.misc.edit').'"><i class="material-icons" style="font-size:20px;">mode_edit</i></a>
                    <a data-id="'.$settings->id.'" data-type="settings" class="btn-xs btn-flat waves-effect waves-red settings-delete-btn"><i class="material-icons" style="font-size:20px;">delete_forever</i></a></div>';
                
                return $html;
            })
            ->addColumn('img', function ($settings) use($language_id) {
                $tr = $settings->translated()->where('language_id', $language_id)->first();
                if ($tr && $tr->img != null && $tr->img != '') {
                    $img = json_decode($tr->img, true);
                    $html = (isset($img[0]) ? '<div class="table_image_holder-small"><img src="'.url('/img/settings').'/'.$img[0].'"  alt="'.$tr->name.'" class="materialboxed responsive-img" data-caption="'.$tr->name.'"></div>' : null);
                    return $html;
                }
                return null;
            })
            ->addColumn('value', function ($settings) use($language_id) {
                $tr = $settings->translated()->where('language_id', $language_id)->first();
                if ($tr) {
                    return $tr->value;
                }
                return null;
            });

            return $db->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query() {

        $current_menu_item_id = null;
        if ($this->request()->has('current_menu_item_id') && $settings_type = SettingsType::filterRegion()->find($this->request()->get('current_menu_item_id'))) {
            $settings = Settings::filterRegion()->whereHas('type', function($q) use($settings_type) {
                $q->where('id', $settings_type->id);
            })->select();

        } else {
            /*$products = ProductLanguage::filterRegion()->where('language_id', $language_id)->select();*/
            $settings = Settings::filterRegion()->select();
        }

        if ($search_input = $this->request()->get('search_input')) {
            $settings->where(function ($query) use ($search_input) {
                $query->where('id', 'LIKE', '%'.$search_input.'%')
                ->orWhere('status', 'LIKE', '%'.$search_input.'%')
                ->orWhereHas('translated', function ($query) use ($search_input) {
                    $query->where('name', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('value', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('options', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('description', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('img', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('text', 'LIKE', '%'.$search_input.'%');
                });
            });  
        }
        
        return $this->applyScopes($settings);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html() {
        return $this->builder()
            ->columns([
                'id',
                'region_id',
                'status',
                'input_type',
                'created_at',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload', 'delete'],
            ]);
    }
}
