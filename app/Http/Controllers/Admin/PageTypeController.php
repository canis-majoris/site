<?php
namespace App\Http\Controllers; //admin add

//use App\Repositories\LanguageRepository;
use App\DataTables\PagesDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Page\Page;
use App\Models\Language;
use App\Models\Region;
use App\Models\Page\PageType;
use App\Models\Page\PageLanguage;
use Config;
use Validator;

class PageTypeController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(PagesDataTable $dataTable)
    {
        return $dataTable->render('pages.index', []);

        //return view('main.languages.index', compact('languages'/*, 'links'*/));
    }
    
    public function create() {
        return view('pages.create');
    }

    public function show() {
        return view('pages.create');
    }

    public function loadPageTypes(Request $request) {
        
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['language_id']) {
                $pages_types = PageType::filterRegion()->get();
                $language_id = $data['language_id'];
                $current_menu_item_id = isset($data['current_menu_item_id']) ? $data['current_menu_item_id'] : null;
                $view = View::make('pages.parts.form_right', compact('pages_types', 'language_id', 'current_menu_item_id'))->render();

                return response()->json(['success' => true, 'status' => 1, 'message' => 'content types loaded', 'html' => $view, 'itemCount' => count($pages_types)]);
            }
            return response()->json(['success' => false, 'status' => 0, 'message' => 'could not load content types..']);
        }
    }

    public function loadPageTypesData(Request $request, PagesDataTable $dataTable) {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['language_id'] && $data['current_menu_item_id']) {
                //$region_id = session('region_id') ? session('region_id') : $this->default_region_id;
                $pages_types = PageType::filterRegion()->find($data['current_menu_item_id'])->pages()->get();
                $view = $dataTable->render('pages.parts.form_right_data')->render();
                return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
            }
            return response()->json(['success' => false, 'status' => 0, 'message' => 'could not load page types data..']);
        }
    }

    public function loadPageTypeSettings(Request $request) {
        if ($request->ajax()) {

            $data = $request->all();
            if ($data['id']) {
                //$region_id = session('region_id') ? session('region_id') : $this->default_region_id;
                $pages_types = PageType::filterRegion()->select('id', 'name', 'title', 'description', 'watch', 'icon')->find($data['id']);
                //dd($pages_types->translated()->count());
                //dd(array_merge($pages_types->toArray(), $translated->toArray()));
                return response()->json(['success' => true, 'status' => 1, 'message' => 'page types settings loaded', 'item' => $pages_types->toArray()]);
            }
            return response()->json(['success' => false, 'status' => 0, 'message' => 'could not page types settings']);
        }
    }

    public function deletePageType(Request $request) {
        if ($request->ajax()) {

            $errors = '';
            $message = 'page type deleted';
            $id = $request->get('id');
            $pages_type = PageType::filterRegion()->find($id);

            if ($pages_type) {
                $result = $pages_type->deleteHelper();
                $errors .= ($result['errors'] ? $result['errors'] : '');
            }

            if ($errors) {
                $message = $errors;
            }

            return response()->json(['success' => true, 'status' => 1, 'message' => $message]);
        }
        return response()->json(['success' => true, 'status' => 1, 'message' => 'could not delete page type..']);
    }

    public function addPageType(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['name'] && $data['name_trans'] && $data['current_lang_id']) {
                $pages_type = new PageType;
                $pages_type->region_id = $this->region;
                $pages_type->name = $data["name"];
                $pages_type->title = $data["title"];
                $pages_type->description = $data["description"];
                if (isset($data["watch"]))
                    $pages_type->watch = ($data["watch"] == "on") ? 1 : 0;
                else
                    $pages_type->watch = 0;

                $pages_type->save();

                //$region_id = session('region_id') ? session('region_id') : $this->default_region_id;
                $pages_types = PageType::filterRegion()->get();
                $view = View::make('pages.parts.form_right', compact('pages_types'))->render();

                return response()->json(['success' => true, 'status' => 1, 'message' => 'page type added' , 'html' => $view, 'itemCount' => count($pages_types)]);
            }
            return response()->json(['success' => false, 'status' => 0, 'message' => 'could not add page type']);
        }
    }

    public function edit(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if (isset($data['language_id']) && isset($data['id'])) {
                $id = $data['id'];
                $language_id = $data['language_id'];
                $pages_type = PageType::find($data['id']);
                if ($pages_type) {
                    $view = View::make('pages.manage.pages_type', compact('pages_type', 'language_id'))->render();
                    return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
                }
            } elseif (isset($data['language_id'])) {
                $language_id = $data['language_id'];
                $pages_type = null;
                $view = View::make('pages.manage.pages_type', compact('pages_type', 'language_id'))->render();
                return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
            }
        }
        return response()->json(['success' => true, 'status' => 1, 'message' => 'something went wrong..']);
    }

    public function update(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();

            if(false){
                //return response()->json(['success' => false, 'status' => 0, 'message' => $validator->errors()->all()]);
            } else {

                //var_dump($data); die;
                $arr = [];

                $arr['name']        = (isset($data['name']) ? $data['name'] : null);
                $arr['title']       = (isset($data['title']) ? $data['title'] : null);
                $arr['icon']        = (isset($data['icon']) ? $data['icon'] : null);
                $arr['description'] = (isset($data['text']) ? $data['text'] : null);
                $arr['watch']       = (isset($data["watch"]) && $data["watch"] == "on") ? 1 : 0;

                $result = [];
                
                if (isset($data['id']) && $data['id'] != null) {
                    //update existing page type
                    $pages_type_current = PageType::filterRegion()->find($data['id']);
                    $result_current = $pages_type_current->saveHelper($arr);

                } else {
                    //add new page type

                    $pages_type = new PageType;
                    $result = $pages_type->saveHelper($arr);
                }

                $message = 'page type settings updated';
                if (isset($result['errors'])) {
                    $message = $result['errors'];
                }

                return response()->json(['success' => true, 'status' => 1, 'message' => $message]);
            }
        }
        return response()->json(['success' => true, 'status' => 1, 'message' => 'could not update page type parameters..']);
    }
}