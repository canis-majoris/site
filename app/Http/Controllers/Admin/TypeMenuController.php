<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use Validator;
use App\Models\Language;
use App\Models\Region;
use App\Models\Product\Product;
use App\Models\Product\ProductMenu;
use App\Models\Settings\Settings;
use App\Models\Settings\SettingsType;
use App\Models\Text\Text;
use App\Models\Text\TextType;
use App\Models\Page\Page;
use App\Models\Page\PageType;
use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryType;

class TypeMenuController extends Controller {

    /**
     * The MenuRepository instance.
     *
     * @var App\Repositories\DiscountRepository
     */
    protected $item;
    protected $item_translated;
    protected $view_directory;

    /**
     * Create a new DiscountController instance.
     *
     * @param  App\Repositories\DiscountRepository $discount
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    public function loadTypes(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();

            //if (isset($data['language_id'])) {

                $menu_items = $this->item->filterRegion()->get();
                $language_id = isset($data['language_id']) ? $data['language_id'] : null;
                $current_menu_item_id = isset($data['current_menu_item_id']) ? $data['current_menu_item_id'] : null;

                $view = view($this->view_directory . '.parts.form_right', compact('menu_items', 'language_id', 'current_menu_item_id'))->render();

                return response()->json(['success' => true, 'status' => 1, 'message' => 'Menu items loaded', 'html' => $view, 'itemCount' => count($menu_items)]);

           // }

            return response()->json(['success' => false, 'status' => 0, 'message' => 'Could not load settings types..']);
        }
    }

    public function loadTypesData(Request $request, SettingsDataTable $dataTable) {

        if ($request->ajax()) {

            $data = $request->all();

            if ($data['language_id'] && $data['current_menu_item_id']) {

                $menu_items = $this->item->filterRegion()->find($data['current_menu_item_id'])->items()->get();

                $view = $dataTable->render($this->view_directory . '.parts.form_right_data', compact('menu_items'))->render();

                return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
            }

            return response()->json(['success' => false, 'status' => 0, 'message' => 'Could not load menu items data..']);
        }

    }

    public function loadTypeSettings(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();

            $region = ( isset($data['region']) && $data['region'] == false ) ? false : true;

            if ($data['id']) {

                $menu_item = $this->item->filterRegion($region)->find($data['id']);

                $select_data = [];

                $items_type = null;

                if ($this->view_directory == 'products') {

                    $select_data = Product::filterRegion()->whereNotIn('id', $menu_item->items()->pluck('products.id')->toArray())->wherehas('translated', function ( $query ) {}, '>', 0)->with('translated')->get();

                    $items_type = 'products';
                }

                $translated = null;
                
                $item = $menu_item->toArray();
                
                if (isset($data['language_id']) && method_exists($menu_item, 'translated')) {

                    $translated = $menu_item->translated()->where('language_id', $data['language_id'])->first();
                    $translated_arr = $translated->toArray();
                    unset($translated_arr['id']);

                    if ($translated) {
                        $item = array_merge($item, $translated_arr);
                    }
                }

                return response()->json(['success' => true, 'status' => 1, 'message' => 'Menu item types settings loaded', 'item' => $item, 'items_type' => $items_type,  'select_data' => $select_data]);
            }

            return response()->json(['success' => false, 'status' => 0, 'message' => 'could not settings types settings']);
        }
    }

    public function deleteType(Request $request) {

        if ($request->ajax()) {

            $errors = '';
            $message = 'Menu item deleted';
            $id = $request->get('id');
            $menu_item = $this->item->filterRegion()->find($id);

            if ($menu_item) {

                $result = $menu_item->deleteHelper();
                $errors .= ($result['errors'] ? $result['errors'] : '');

            }

            if ($errors) {
                $message = $errors;
            }

            return response()->json(['success' => true, 'status' => 1, 'message' => $message]);
        }
        return response()->json(['success' => true, 'status' => 1, 'message' => 'Could not delete menu item..']); 
    }

    public function addType(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();
            $ind = 1;

            if ($this->item->filterRegion()->count() > 0) {

                $ind = $this->item->orderBy('ind', 'desc')->first()->ind + 1;

            }

            if ($data['name'] && $data['current_lang_id']) {

                $languages = Language::filterRegion()->get();

                $menu_item = new $this->item;
                $menu_item->region_id = $this->region;
                $menu_item->name = $data["name"];
                $menu_item->description = $data["description"];

                if (isset($data["status"]))
                    $menu_item->status = ($data["status"] == "on") ? 1 : 0;
                else
                    $menu_item->status = 0;

                $menu_item->save();

                //$region_id = session('region_id') ? session('region_id') : $this->default_region_id;
                $menu_items = $this->item->filterRegion()->get();
                $view = view($this->view_directory . '.parts.form_right', compact('menu_items'))->render();

                return response()->json(['success' => true, 'status' => 1, 'message' => 'Menu item added' , 'html' => $view, 'itemCount' => count($menu_items)]);

            }

            return response()->json(['success' => false, 'status' => 0, 'message' => 'Could not add menu item']);
        }
    }

    public function edit(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();

            if (isset($data['language_id']) && isset($data['id'])) {

                $id = $data['id'];
                $language_id = $data['language_id'];
                $menu_item = $this->item->find($data['id']);

                if ($menu_item) {

                    $view = view($view_directory . '.manage.menu_item', compact('menu_item', 'language_id'))->render();
                    return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);

                }

            } elseif (isset($data['language_id'])) {

                $language_id = $data['language_id'];
                $menu_item = null;

                $view = view($this->view_directory . '.manage.menu_item', compact('menu_item', 'language_id'))->render();
                return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);

            }
        }

        return response()->json(['success' => true, 'status' => 1, 'message' => 'something went wrong..']);
    }

    public function update(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();

            $id = (isset($data['id']) && $data['id'] != null ? $data['id'] : null);

            $validator = Validator::make($data, $this->item->rules($id));

            if($validator->fails()){

                return response()->json(['success' => false, 'status' => 0, 'message' => $validator->errors()->all()]);
               
            } else {

                $arr = [];
                $update_arr = [];
                $language_id = $data['language_id'] ?? null;

                $arr['name']         = $data['name'] ?? null;
                $arr['title']        = $data['title'] ?? null;
                $arr['description']  = $data['setting'] ?? null;
                $arr['display_name'] = $data["display_name"] ?? null;
                $arr['status']       = (isset($data["status"]) && $data["status"] == "on") ? 1 : 0;
                $arr['watch']        = (isset($data["watch"]) && $data["watch"] == "on") ? 1 : 0;
                $arr['is_group']     = (isset($data["is_group"]) && $data["is_group"] == "on") ? 1 : 0;

                $update_arr['name']        = $data['name'] ?? null;
                $update_arr['name_trans']  = $data['name_trans'] ?? null;
                $update_arr['text']        = $data['text'] ?? null;
                $update_arr['language_id'] = $language_id;

               // dd($data)

                $languages = Language::filterRegion()->get();
                $result = [];
                
                if (isset($data['id']) && $data['id'] != null) {
                    //update existing type

                    $menu_item = $this->item->find($data['id']);

                    $result_current = $menu_item->saveHelper($arr);

                    if (method_exists($menu_item, 'translated')) {

                        $translated = $menu_item->translated()->where('language_id', $data['language_id'])->first();

                        if ($translated) {

                            $translated->saveHelper($update_arr);

                        } else {

                            $translated = new $this->item_translated;
                            $translated->saveHelper($update_arr);
                            $menu_item->translated()->save($translated);

                        }
                    }

                } else {
                    //add new type

                    $menu_item = new $this->item;
                    $result = $menu_item->saveHelper($arr);

                    if (method_exists($menu_item, 'translated')) {

                        foreach ($languages as $lang) {

                            $update_arr['language_id'] = $lang->id;
                            $translated = new $this->item_translated;
                            $translated->saveHelper($update_arr);
                            $menu_item->translated()->save($translated);

                        }

                    }
                    
                }

                $message = 'Menu item parameters updated';

                if (isset($result['errors'])) {
                    $message = $result['errors'];
                }

                return response()->json(['success' => true, 'status' => 1, 'message' => $message]);
            }
        }
        return response()->json(['success' => true, 'status' => 1, 'message' => 'Could not update menu item parameters..']);
    }
}