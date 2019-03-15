<?php

namespace App\DataTables;

use App\Models\Product\Product;
use App\Models\Region;
use App\Models\Page\Page;
use App\Models\Page\PageLanguage;
use App\Models\Page\PageType;
use App\Models\language;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class PagesDataTable extends DataTable
{
    // protected $printPreview  = 'path-to-print-preview-view';
    // protected $exportColumns = ['id', 'name'];
    // protected $printColumns  = '*';
    private $default_language_id = 1;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function dataTable()
    {

        // $language_id = $this->request()->get('language_id', $this->default_language_id);
        // dd( $language_id);
        return $this->datatables
            ->eloquent($this->query());
            // ->addColumn('name', function ($page) use($language_id) {
            //     $html = '';
            //     $tr = $page->translated()->where('language_id', $language_id)->first();
            //     if ($tr ) {
            //         $st = $tr->short_page ?  '' : '';
            //         $html .= '<span>'.$tr->name.''.$st.'</small></span>';
            //     } else {
            //         $st = $page->short_page ?  '' : '';
            //         $html .= '<span>'.$page->name.''.$st.'</span>';
            //     }

            //     return $html;
            // })
            // ->addColumn('name_trans', function ($page) use($language_id) {

            //     $language = Language::filterRegion()->find($language_id);
            //     $url = config('database.region.' . $page->region_id . '.domain') . '/' . $language->name . '/' . $page->type->name . '/read' . '/' . $page->id;
            //     return '<a class="btn btn-flat" style="page-transform: none; color: #2196F3;" href="http://' .$url . '" target="_blank">'. $url .'</a>';
            // })
            // ->editColumn('watch', function ($page) { 
            //     $activated_class = $page->watch ? 'active' : null;
            //     return '<button data-id="'.$page->id.'" class="btn btn-xs stat-change '.$activated_class.'"><i class="material-icons">check</i></button>';})
            // ->addColumn('action', function ($page) {
            //     return '<div style="max-width:100px;" class="pull-right"><a data-id="'.$page->id.'" class="btn-xs btn-flat waves-effect waves-red pages-delete-btn right"><i class="material-icons" style="font-size:20px;">delete_forever</i></a><a data-id="'.$page->id.'" class="btn-xs btn-flat waves-effect waves-teal pages-edit-btn right"><i class="material-icons" style="font-size:20px;">mode_edit</i></a>
            //             </div>';
            // })
            // ->editColumn('img', function ($page) use($language_id) {
            //     $tr = $page->translated()->where('language_id', $language_id)->first();
            //     if ($tr && $tr->img != null && $tr->img != '') {
            //         $img = json_decode($tr->img, true);
            //         $html = (isset($img[0]) ? '<div class="table_image_holder-small"><img src="'.url('/img/pages').'/'.$img[0].'"  alt="'.$tr->name.'" class="materialboxed responsive-img" data-caption="'.$tr->name.'"></div>' : null);
            //         return $html;
            //     }
            //     return null;
            // })
            //->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    // public function query()
    // {
    //     $current_menu_item_id = null;
    //     if ($this->request()->has('current_menu_item_id') && $pages_type = PageType::filterRegion()->find($this->request()->get('current_menu_item_id'))) {
    //         $pages = Page::filterRegion()->whereHas('type', function($q) use($pages_type) {
    //                 $q->where('id', $pages_type->id);
    //         })->select();

    //     } else {
    //         /*$products = ProductLanguage::filterRegion()->where('language_id', $language_id)->select();*/
    //         $pages = Page::filterRegion()->select();
    //     }

    //     if ($search_input = $this->request()->get('search_input')) {
    //         $pages->where(function ($query) use ($search_input) {
    //             $query->where('id', 'LIKE', '%'.$search_input.'%')
    //             ->orWhere('watch', 'LIKE', '%'.$search_input.'%')
    //             ->orWhere('index', 'LIKE', '%'.$search_input.'%')
    //             ->orWhere('created_at', 'LIKE', '%'.$search_input.'%')
    //             ->orWhereHas('translated', function ($query) use ($search_input) {
    //                 $query->where('name', 'LIKE', '%'.$search_input.'%')
    //                     ->orWhere('name_trans', 'LIKE', '%'.$search_input.'%')
    //                     ->orWhere('img', 'LIKE', '%'.$search_input.'%')
    //                     ->orWhere('short_page', 'LIKE', '%'.$search_input.'%')
    //                     ->orWhere('page', 'LIKE', '%'.$search_input.'%')
    //                     ->orWhere('source', 'LIKE', '%'.$search_input.'%')
    //                     ->orWhere('tags', 'LIKE', '%'.$search_input.'%')
    //                     ->orWhere('video', 'LIKE', '%'.$search_input.'%');
    //             });
    //         });  
    //     }

        
    //     //$pages/*->where('language_id', $language_id)*/->where('deleted_at', null)->select();
        
    //     return $this->applyScopes($pages);
    // }

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
                'created_at',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload', 'delete'],
            ]);
    }
}
