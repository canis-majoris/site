<?php

namespace App\DataTables;

use App\Models\Product\Product;
use App\Models\Region;
use App\Models\Product\ProductMenu;
use App\Models\Product\ProductLanguage;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Services\DataTable;

class ProductsDataTable extends DataTable
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
        $menu_item_id = $this->request()->get('current_menu_item_id');
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('name', function ($product) use($language_id) {
                $html = '';
                $tr = $product->translated()->where('language_id', $language_id)->first();
                if ($tr ) {
                    $st = $tr->short_text ?  ' <small>('.$tr->short_text.')</small>' : '';
                    $html .= '<span>'.$tr->name.''.$st.'</small></span>';
                } else {
                    $st = $product->short_text ?  ' <small>('.$product->short_text.')</small>' : '';
                    $html .= '<span>'.$product->name.''.$st.'</span>';
                }

                return $html;
            })
            ->editColumn('watch', function ($product) { 
                $activated_class = $product->watch ? 'active' : null;
                return '<button data-id="'.$product->id.'" class="btn btn-xs stat-change '.$activated_class.'"><i class="material-icons">check</i></button>';})
            /*->editColumn('order', function ($product) { 
                return '<i class="material-icons">sort</i>';})*/
            ->addColumn('action', function ($product) use ($menu_item_id, $language_id) {

                // $detach_from_role = ($menu_item_id ? '<a data-id="'.$product->id.'" data-display_name="'.$product->display_name.'" class="btn-xs btn-flat waves-effect waves-red remove_from_menu_item-btn right"><i class="material-icons" style="font-size:20px;">remove_circle</i></a>' : '');
                // return '<div style="max-width:100px;" class="pull-right"><a data-id="'.$product->id.'" class="btn-xs btn-flat waves-effect waves-red products-delete-btn right"><i class="material-icons" style="font-size:20px;">delete_forever</i></a><a data-id="'.$product->id.'" class="btn-xs btn-flat waves-effect waves-teal products-edit-btn right"><i class="material-icons" style="font-size:20px;">mode_edit</i></a>
                //         </div>';
                $name = '-- name not assigntd --';
                $tr = $product->translated()->where('language_id', $language_id)->first();

                if ($tr) {
                    $name = $tr->name . ($tr->short_text ?  ' <small>('.$tr->short_text.')</small>' : '');
                }

                $disable_delete = '';
                
                $detach_from_menu_item = ($menu_item_id ? '<a data-id="'.$product->id.'" data-display_name="' . $name . '" class="btn-xs btn-flat waves-effect waves-red remove_from_menu_item-btn right"><i class="material-icons" style="font-size:20px;">remove_circle</i></a>' : '');
                $delete_product = (!$menu_item_id || true ? '<a data-id="'.$product->id.'" '.$disable_delete.' class="btn-xs btn-flat waves-effect waves-red products-delete-btn right"><i class="material-icons" style="font-size:20px;">delete_forever</i></a>' : '');

                $html = '<div class="right">'.$delete_product.''.$detach_from_menu_item.'<a data-id="'.$product->id.'" class="btn-xs btn-flat waves-effect waves-teal products-edit-btn right"><i class="material-icons" style="font-size:20px;">mode_edit</i></a></div>';
                
                return $html;


            })
            ->editColumn('img', function ($product) use($language_id) {
                $tr = $product->translated()->where('language_id', $language_id)->first();
                if ($tr && $tr->img != null && $tr->img != '') {
                    $img = json_decode($tr->img, true);
                    $html = (isset($img[0]) ? '<div class="table_image_holder-small"><img src="'.url('/img/products').'/'.$img[0].'"  alt="'.$tr->name.'" class="materialboxed responsive-img" data-caption="'.$tr->name.'"></div>' : null);
                    return $html;
                }
                return null;
            })
            ->editColumn('price', function ($product) use($language_id) {
                $currency = Currency::filterRegion()->first();
                return $currency->sign . $product->price;
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
        $region = session('region_id') ? session('region_id') : 1;
        $current_menu_item_id = null;
        if ($this->request()->get('current_menu_item_id')) {
            $current_menu_item_id = $this->request()->get('current_menu_item_id');
            $menus_products_id = ProductMenu::filterRegion()->find($current_menu_item_id)->id;
            //dd($menus_products_id );
            $products = Product::filterRegion()->whereHas('type', function($q) use($menus_products_id) {
                   $q->where('eshop_menus_products.eshop_menu_id', $menus_products_id);
            })->select();

           // dd($products->count());

            /*$products = ProductLanguage::filterRegion()->whereHas('products', function($q) use($menus_products_id, $language_id){
                $q->whereHas('menu_item', function($q) use($menus_products_id, $language_id) {
                    $q->where('eshop_menus_products.eshop_menu_id', $menus_products_id);
                });
            })->select();*/


        } else {
            /*$products = ProductLanguage::filterRegion()->where('language_id', $language_id)->select();*/
            $products = Product::filterRegion()->select();
        }

        
        $products/*->where('language_id', $language_id)*/->where('deleted_at', null)->select();
        
        return $this->applyScopes($products);
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
                'ord',
                'id',
                'ind',
                'region_id',
                'language_id',
                'name',
                'text',
                'ind',
                'img',
                'watch',
                'price',
                'for_mobile',
            ])
            ->parameters([
                //'dom' => 'Bfrtip',
                'buttons' => ['csv', 'excel', 'print', 'reset', 'reload', 'delete'],
            ]);
    }
}
