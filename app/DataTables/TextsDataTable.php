<?php

namespace App\DataTables;

use App\Models\Product\Product;
use App\Models\Region;
use App\Models\Text\Text;
use App\Models\Text\TextLanguage;
use App\Models\Text\TextType;
use App\Models\language;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class TextsDataTable extends DataTable
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

        $language_id = $this->request()->get('language_id', $this->default_language_id);
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('name', function ($text) use($language_id) {
                $html = '';
                $tr = $text->translated()->where('language_id', $language_id)->first();
                if ($tr ) {
                    $st = $tr->short_text ?  '' : '';
                    $html .= '<span>'.$tr->name.''.$st.'</small></span>';
                } else {
                    $st = $text->short_text ?  '' : '';
                    $html .= '<span>'.$text->name.''.$st.'</span>';
                }

                return $html;
            })
            ->addColumn('name_trans', function ($text) use($language_id) {

                $language = Language::filterRegion()->find($language_id);
                $url = config('database.region.' . $text->region_id . '.domain') . '/' . $language->name . '/' . ($text->type ? $text->type->name : null) . '/read' . '/' . $text->id;
                return '<a class="btn btn-flat" style="text-transform: none; color: #2196F3;" href="http://' .$url . '" target="_blank">'. $url .'</a>';
            })
            ->editColumn('watch', function ($text) { 
                $activated_class = $text->watch ? 'active' : null;
                return '<button data-id="'.$text->id.'" class="btn btn-xs stat-change '.$activated_class.'"><i class="material-icons">check</i></button>';})
            /*->editColumn('order', function ($product) { 
                return '<i class="material-icons">sort</i>';})*/
            ->addColumn('action', function ($text) {
                return '<div style="max-width:100px;" class="pull-right"><a data-id="'.$text->id.'" class="btn-xs btn-flat waves-effect waves-red texts-delete-btn right"><i class="material-icons" style="font-size:20px;">delete_forever</i></a><a data-id="'.$text->id.'" class="btn-xs btn-flat waves-effect waves-teal texts-edit-btn right"><i class="material-icons" style="font-size:20px;">mode_edit</i></a>
                        </div>';
            })
            ->editColumn('img', function ($text) use($language_id) {
                $tr = $text->translated()->where('language_id', $language_id)->first();
                if ($tr && $tr->img != null && $tr->img != '') {
                    $img = json_decode($tr->img, true);
                    $html = (isset($img[0]) ? '<div class="table_image_holder-small"><img src="'.url('/img/texts').'/'.$img[0].'"  alt="'.$tr->name.'" class="materialboxed responsive-img" data-caption="'.$tr->name.'"></div>' : null);
                    return $html;
                }
                return null;
            })
            /*->addColumn('select', function ($product) {
                return '<div class="input-field">
                            <span class="input-wrapper-span-2">
                                <input type="checkbox" name="selected_products[]" value="'.$product->id.'" id="selected_product_'.$product->id.'">
                                <label for="selected_product_'.$product->id.'"></label>
                            </span>
                        </div>';
            })*/
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $current_menu_item_id = null;
        if ($this->request()->has('current_menu_item_id') && $texts_type = TextType::filterRegion()->find($this->request()->get('current_menu_item_id'))) {
            $texts = Text::filterRegion()->whereHas('type', function($q) use($texts_type) {
                    $q->where('id', $texts_type->id);
            })->select();

        } else {
            /*$products = ProductLanguage::filterRegion()->where('language_id', $language_id)->select();*/
            $texts = Text::filterRegion()->select();
        }

        if ($search_input = $this->request()->get('search_input')) {
            $texts->where(function ($query) use ($search_input) {
                $query->where('id', 'LIKE', '%'.$search_input.'%')
                ->orWhere('watch', 'LIKE', '%'.$search_input.'%')
                ->orWhere('index', 'LIKE', '%'.$search_input.'%')
                ->orWhere('created_at', 'LIKE', '%'.$search_input.'%')
                ->orWhereHas('translated', function ($query) use ($search_input) {
                    $query->where('name', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('name_trans', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('img', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('short_text', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('text', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('source', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('tags', 'LIKE', '%'.$search_input.'%')
                        ->orWhere('video', 'LIKE', '%'.$search_input.'%');
                });
            });  
        }

        
        //$texts/*->where('language_id', $language_id)*/->where('deleted_at', null)->select();
        
        return $this->applyScopes($texts);
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
                'language_id',
                'watch',
                'date',
                'name',
                'img',
                'created_at',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload', 'delete'],
            ]);
    }
}
