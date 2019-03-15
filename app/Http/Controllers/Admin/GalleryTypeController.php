<?php
namespace App\Http\Controllers; //admin add

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
use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryType;
use App\Models\Region;
use Config;
use Validator;
use File;

class GalleryTypeController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $dataTable->render('gallery.index', []);

        //return view('main.languages.index', compact('languages'/*, 'links'*/));
    }
    
    public function create() {
        return view('gallery.create');
    }

    public function show() {
        return view('gallery.create');
    }

    public function loadGalleryTypes(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();
            $menu = GalleryType::filterRegion(false)->get();
            $current_menu_item_id = isset($data['current_menu_item_id']) ? $data['current_menu_item_id'] : null;
            $view = View::make('gallery.parts.form_right', compact('menu', 'current_menu_item_id'))->render();

            return response()->json(['success' => true, 'status' => 1, 'message' => 'gallery types loaded', 'html' => $view, 'itemCount' => count($menu)]);
        }
    }

    public function loadGalleryTypesData(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if (isset($data['current_menu_item_id']) || isset($data['menu_item_name'])) {
                $gallery_type = null;
                $current_menu_item_id = null;

                if (!empty($data['current_menu_item_id'])) {
                     $gallery_type = GalleryType::filterRegion(false)->find($data['current_menu_item_id']);
                } elseif (isset($data['menu_item_name'])) {
                     $gallery_type = GalleryType::filterRegion(false)->where('title', $data['menu_item_name'])->first();
                }

                if ($gallery_type) {
                    $gallery_items = $gallery_type->content()->get();
                    $current_menu_item_id = $gallery_type->id;
                } else {
                    $gallery_items = Gallery::all();
                }
    
                $layout_grid_gallery = (isset($data['layout_grid_gallery']) ? $data['layout_grid_gallery'] : 'l3 m6 s12');
               
                $view = view('admin.gallery.parts.gallery_items', compact('gallery_items', 'gallery_type', 'current_menu_item_id', 'layout_grid_gallery'))->render();
                return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
            }
            return response()->json(['success' => false, 'status' => 0, 'message' => 'could not load gallery types data..']);
        }
    }

    public function loadGalleryTypeSettings(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['id']) {
                $gallery_types = GalleryType::filterRegion(false)->select('id', 'title', 'status', 'description', 'created_at')->find($data['id']);
                return response()->json(['success' => true, 'status' => 1, 'message' => 'setting types gallery loaded', 'item' => $gallery_types->toArray()]);
            }
            return response()->json(['success' => false, 'status' => 0, 'message' => 'could not gallery types gallery']);
        }
    }

    public function deleteGalleryType(Request $request) {
        if ($request->ajax()) {

            $errors = '';
            $message = 'gallery type deleted';
            $id = $request->get('id');
            $gallery_type = GalleryType::filterRegion(false)->find($id);

            if ($gallery_type) {
                $result = $gallery_type->deleteHelper();
                $errors .= ($result['errors'] ? $result['errors'] : '');
            }

            if ($errors) {
                $message = $errors;
            }

            return response()->json(['success' => true, 'status' => 1, 'message' => $message]);
        }
        return response()->json(['success' => true, 'status' => 1, 'message' => 'could not delete gallery type..']); 
    }

    public function addGalleryType(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['title'] && $data['current_lang_id']) {
                $gallery_type = new GalleryType;
                $gallery_type->region_id = $this->region;
                $gallery_type->title = $data["title"];
                $gallery_type->description = $data["description"];
                if (isset($data["status"]))
                    $gallery_type->status = ($data["status"] == "on") ? 1 : 0;
                else
                    $gallery_type->status = 0;

                if ($gallery_type->save()) {
                    
                }

                //$region_id = session('region_id') ? session('region_id') : $this->default_region_id;
                $gallery_types = GalleryType::filterRegion(false)->get();
                $view = View::make('gallery.parts.form_right', compact('gallery_types'))->render();

                return response()->json(['success' => true, 'status' => 1, 'message' => 'setting type added' , 'html' => $view, 'itemCount' => count($gallery_types)]);
            }
            return response()->json(['success' => false, 'status' => 0, 'message' => 'could not add setting type']);
        }
    }

    public function edit(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if (isset($data['id'])) {
                $id = $data['id'];
                $gallery_type = GalleryType::find($data['id']);
                if ($gallery_type) {
                    $view = View::make('gallery.manage.gallery_type', compact('gallery_type'))->render();
                    return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
                }
            } else {
                $gallery_type = null;
                $view = View::make('gallery.manage.gallery_type', compact('gallery_type'))->render();
                return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
            }
        }
        return response()->json(['success' => true, 'status' => 1, 'message' => 'something went wrong..']);
    }

    public function update(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if (isset($data['id']) && $data['id'] != null) {
                $validator = Validator::make($data, GalleryType::rules($data['id']));
            } else {
                $validator = Validator::make($data, GalleryType::rules());
            }
            if($validator->fails()){
                return response()->json(['success' => true, 'status' => 0, 'message' => $validator->errors()->all()]);
            } else {

                //var_dump($data); die;
                $arr = [];

                $arr['title']       = (isset($data['title']) ? $data['title'] : null);
                $arr['description'] = (isset($data['setting']) ? $data['setting'] : null);
                $arr['status']      = (isset($data["status"]) && $data["status"] == "on") ? 1 : 0;

                $result = [];
                
                if (isset($data['id']) && $data['id'] != null) {
                    //update existing setting type
                    $gallery_type_current = GalleryType::filterRegion(false)->find($data['id']);
                    $result_current = $gallery_type_current->saveHelper($arr);

                } else {
                    //add new setting type


                    $gallery_type = new GalleryType;
                    $result = $gallery_type->saveHelper($arr, true);
                }

                $message = 'gallery type gallery updated';
                if (isset($result['errors'])) {
                    $message = $result['errors'];
                }

                return response()->json(['success' => true, 'status' => 1, 'message' => $message]);
            }
        }
        return response()->json(['success' => true, 'status' => 1, 'message' => 'could not update gallery type parameters..']);
    }

    public function attach_image_form(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            if (isset($data['current_menu_item_id']) && !empty($data['current_menu_item_id'])) {
                $gallery_type = GalleryType::filterRegion(falsefalse)->find($data['current_menu_item_id']);
                $view = View::make('gallery.parts.attach_images', compact('gallery_type', 'current_menu_item_id'))->render();
                return response()->json(['success' => true, 'status' => 1, 'message' => 'new content', 'html' => $view]);
            }
        }
    }
}