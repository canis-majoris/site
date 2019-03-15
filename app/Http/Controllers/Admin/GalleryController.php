<?php


namespace App\Http\Controllers;
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
use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryType;
use Config;
use Validator;


class GalleryController extends AppController {

    public function __construct() {
        parent::__construct();
        View::share('for_all_regions', true);
        //$this->domain = $connection = config('database.region.'.$region.'.domain');
        //$this->prefix = $connection = config('database.region.'.$region.'.prefix');
    }

    public function index() {
        $gallery_items = Gallery::get();
        return view('gallery.index',compact('gallery_items'));
    }

    public function update(Request $request) {


        if ($request->ajax()) {
            $data = $request->all();
            $gallery_item = null;
            $gallery_type = null;

            if ($data['gallery_item_id']) {
                $gallery_item = Gallery::filterRegion(false)->find($data['gallery_item_id']);
                $gallery_type = $gallery_item->type()->first();
            } else {
                if (isset($data['current_menu_item_id'])) {
                    $gallery_type = GalleryType::filterRegion(false)->find($data['current_menu_item_id']);
                }
            }

            $current_menu_item_id = $gallery_type->id;

            $view = View::make('gallery.manage.form', compact('gallery_item', 'gallery_type', 'current_menu_item_id'))->render();
            return response()->json(['success' => true, 'status' => 1, 'message' => 'new content', 'html' => $view]);
        }
    }


    public function update_img(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            $id = (isset($data['id'])) ? $data['id'] : null;
            $message = null;      

            if (isset($data['action'])) {

                //var_dump($data); die;
                $gallery = Gallery::filterRegion(false)->find($id);

                if ($gallery) {
                    $data['directory'] = $gallery->type()->first()->title;
                    //var_dump($data); die;
                    $fname = $gallery->manage_img($data);
                    return response()->json(['success' => true, 'status' => 1, 'message' => $message, 'newfilename' => $fname]);
                } 
            }
        }
        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong..']);
    }

    public function save(Request $request) {

        if ($request->ajax()) {
            $data = $request->all();
            $result = [];
            //$validator = $this->validator($data);
            if(false){
                return response()->json(['success' => false, 'status' => 0, 'message' => $validator->errors()->all()]);
            } else {


                //var_dump($data); die;

                $gallery_item_id = null;
                $arr = [];

                $arr['title']       = isset($data["title"]) ? $data["title"] : null;
                $arr['status']      = isset($data["status"]) && $data["status"] == "on" ? 1 : 0;
                $arr['img']         = isset($data["image_name"]) ? trim($data["image_name"]) : null;
                $arr['type']        = isset($data["type"]) ? $data["type"] : null;
                $arr['description'] = isset($data["description"]) ? $data["description"] : null;
                $arr['tags']        = isset($data["tags"]) ? $data["tags"] : null;


                if (!isset($data['current_menu_item_id'])) {
                    $gallery_type = Gallery::filterRegion(false)->find($data['gallery_item_id'])->type()->first();
                } else {
                    $gallery_type = GalleryType::filterRegion(false)->find($data['current_menu_item_id']);
                }

                if (isset($data['gallery_item_id']) && $data['gallery_item_id'] != null) {

                    //update existing content
                    //
                    $gallery_item_current = $gallery_type->content()->find($data['gallery_item_id']);

                    $result = $gallery_item_current->saveHelper($arr);

                    $gallery_item_id = $data['gallery_item_id'];

                } else {

                    //add new content
                    //
                    $gallery_item = new Gallery;
                    $result = $gallery_item->saveHelper($arr);
                    $gallery_type->content()->save($gallery_item);

                    $gallery_item_id = $gallery_item->id;
                }

                $message = 'gallery item updated';

                if (isset($result['errors']) && !empty($result['errors'])) {
                    $message = $result['errors'];
                }

                return response()->json(['success' => $result['status'], 'status' => $result['status'], 'message' => $message, 'result' => ['id' => $gallery_item_id]]);
            }
        }
        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong']);
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
            $ids = $request->get('ids');
            $message = 'content deleted';
            if (count($ids) > 1) {
                $message = count($ids).' contents deleted';
            }

            $errors = '';
            foreach ($ids as $id) {
               if ($result = Gallery::filterRegion(false)->find($id)->deleteHelper()) {
                    $errors .= ($result['errors'] ? $result['errors'] : '');
                }
            }

            if ($errors) {
                $message = $errors;
            }

            return response()->json(['success' => true, 'status' => 1, 'message' => $message]);
        }
        return response()->json(['success' => true, 'status' => 1, 'message' => 'could not delete gallery item(s)..']); 
    }

    public function changeStatus(Request $request) {
        if ($request->ajax()) {
            $ids = $request->get('ids');
            $errors = '';
            $status = false;
            $message = 'status updated';
            foreach ($ids as $id) {
                $gallery_item = Gallery::filterRegion(false)->find($id);
                $gallery_item->status = ($gallery_item->status == 1 ? 0 : 1);
                if ($result = $gallery_item->save()) {
                    $status = $gallery_item->status;
                }
            }

            if ($errors) {
                $message = $errors;
            }

            return response()->json(['success' => true, 'status' => $status, 'message' => $message]);
        }
        return response()->json(['success' => false, 'error' => true, 'status' => $gallery_item->status, 'message' => 'could not update status..']);
    }
}