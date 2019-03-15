<?php
namespace App\Http\Controllers;

use App\DataTables\TextsDataTable;
use App\Repositories\Eloquent\MediaRepository;
use App\Repositories\Eloquent\LanguageRepository;
use App\Repositories\Eloquent\TextRepository;
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
use Validator;
use App\Models\User;
use App\Models\Language;
use App\Models\Region;
use App\Models\Text\TextType;
use App\Models\Text\Text;
use App\Models\Text\TextLanguage;
use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryType;
use Config;

class TextController extends MediaController {

    public $text;
    public $language;

    public function __construct( MediaRepository $media, TextRepository $text, LanguageRepository $language ) {
        parent::__construct($media);
        $this->item = $text;
        $this->view_directory = 'texts';
        $this->text = $text;
        $this->language = $language;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(TextsDataTable $dataTable) {

        $languages = $this->language->getAll();
        $regions = Region::get();
        $menu_items = [];

        if ($regions->count()) {

            $menu_items = $this->text->getTypes();

        }

        $language_id = $languages->first()->id;
        
        return $dataTable->render('texts.index', compact('menu_items', 'languages', 'language_id'));

    }

    public function update(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();
            $result = $this->text->getUpdate($data);
            $current_menu_item_id = $result['texts_type'] != null ? $result['texts_type']->id : null;
            $view = View::make('texts.manage.form', $result)->render();

            return response()->json(['success' => true, 'status' => 1, 'message' => 'new content', 'html' => $view, 'response' => ['current_menu_item_id' => $current_menu_item_id]]);
            
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong..']);

    }

    public function save(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();
            $result = [];
            $id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

            $validator = Validator::make($data, Text::rules($id));
            
            if($validator->fails()){

                return response()->json(['success' => false, 'status' => 0, 'message' => $validator->errors()->all()]);

            } else {

                $result = $this->text->update($id, $data);

                return response()->json($result);

            }
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong']);

    }

    public function delete(Request $request) {

        if ($request->ajax()) {

            $ids = $request->get('ids');
            $message = 'Text item deleted';

            if (count($ids) > 1) {

                $message = count($ids).' text items deleted';

            }

            $result = $this->text->delete($ids);

            return response()->json(['success' => true, 'status' => $result['status'], 'messages' => $result['messages'], 'errors' => $result['errors']]);
        }

        return response()->json(['success' => true, 'status' => 1, 'message' => 'could not delete text item..']); 
    }

    public function changeStatus(Request $request) {

        if ($request->ajax()) {

            $ids = $request->get('ids');
            $message = 'status updated';

            $result = $this->text->changeStatus($ids);

            if ($result['errors']) {

                $message = $result['errors'];

            }

            return response()->json(['success' => true, 'status' => $result['status'], 'message' => $message]);

        }

        return response()->json(['success' => false, 'error' => true, 'status' => $result['status'], 'message' => 'could not update status..']);
    }

    // public function update_img(Request $request) {
    //     if ($request->ajax()) {
    //         $data = $request->all();
    //         $id = (isset($data['id'])) ? $data['id'] : null;
    //         $message = null;
    //         $language_id = $data['language_id'];

    //         if (isset($data['action']) && $language_id) {

    //             $text = Text::filterRegion()->find($id);

    //             if ($text) {
    //                 $fname = $text->manage_img($data);
    //                 return response()->json(['success' => true, 'status' => 1, 'message' => $message, 'newfilename' => $fname]);
    //             } 
    //         }
    //     }
    //     return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong..']);
    // }

    // public function load_img_form(Request $request) {

    //     if ($request->ajax()) {
    //         $data = $request->all();
    //         $text = null;
    //         $translated = null;
    //         if ($data['id'] && !empty($data['id'])) {
    //             $text = Text::filterRegion()->find($data['id']);
    //             $translated = $text->translated()->where('language_id', $data['language_id'])->select('name', 'img', 'created_at')->first();
    //         }
    //         $view = View::make('texts.parts.image', compact('text', 'translated'))->render();
    //         return response()->json(['success' => true, 'status' => 1, 'message' => 'new content', 'html' => $view]);
    //     }
    // }

    // public function attach_image_form(Request $request) {
    //     if ($request->ajax()) {
    //         $data = $request->all();
    //         if (isset($data['language_id'])) {

    //             $language_id = $data['language_id'];
    //             $text = null;
    //             $translated = null;
    //             $text_type = null;

    //             if ($data['id'] && !empty($data['id'])) {
    //                 $text = Text::filterRegion()->find($data['id']);
    //                 $translated = $text->translated()->where('language_id', $language_id)->select('name', 'img', 'created_at')->first();
    //             }

    //             $view = View::make('texts.parts.attach_images', compact('text', 'translated', 'language_id'))->render();
    //             return response()->json(['success' => true, 'status' => 1, 'message' => 'new content', 'html' => $view]);
    //         }
    //     }
    // }

    // public function attach_images(Request $request) {
    //     if ($request->ajax()) {
    //         $data = $request->all();

    //         if (isset($data['id']) && isset($data['language_id']) && !empty($data['id'])) {
    //             $img = (isset($data['images']) && !empty($data['images'])) ? json_encode($data['images']) : null;
    //             $text = Text::filterRegion()->select('id')->find($data['id']);
    //             $translated = $text->translated()->where('language_id', $data['language_id'])->first();
    //             $siblings = $translated->siblings()->withNoImage()->get();

    //             foreach ($siblings as $sibling) {
    //                 $sibling->img = $img;
    //                 $sibling->save();
    //             }

    //             $translated->img = $img;
    //             $translated->save();

    //             return response()->json(['success' => true, 'status' => 1, 'message' => 'Image(s) attached']);
    //         }
    //     }

    //     return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong']);
    // }

    // public function load_attached_images(Request $request) {
    //     if ($request->ajax()) {
    //         $data = $request->all();
    //         if (isset($data['language_id'])) {
    //             $text = (isset($data['id']) && !empty($data['id'])) ? Text::filterRegion()->select('id')->find($data['id']) : null;
    //             $translated = $text ? $text->translated()->where('language_id', $data['language_id'])->select('name', 'img', 'created_at')->first() : null;
    //             $image_names_arr = $translated ? json_decode($translated->img, 1) : [];
    //             $gallery_items = $translated ? Gallery::filterRegion(false)->whereIn('img', $image_names_arr)->get() : [];
    //             $layout_grid_gallery = (isset($data['layout_grid_gallery']) ? $data['layout_grid_gallery'] : 'l3 m4 s12');
               
    //             $view = View::make('texts.parts.selected_images', compact('gallery_items', 'layout_grid_gallery', 'text'))->render();
    //             return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
    //         }
    //     }

    //     return response()->json(['success' => false, 'status' => 0]);
    // }

    // public function load_gallery_items(Request $request) {
    //     if ($request->ajax()) {
    //         $data = $request->all();
    //         if ((isset($data['current_menu_item_id']) || isset($data['menu_item_name'])) && isset($data['language_id'])) {
    //             if (isset($data['current_menu_item_id'])) {
    //                  $gallery_type = GalleryType::filterRegion()->find($data['current_menu_item_id']);
    //             } elseif (isset($data['menu_item_name'])) {
    //                  $gallery_type = GalleryType::filterRegion()->where('title', $data['menu_item_name'])->first();
    //             }

    //             $text = null;
    //             $translated = null;

    //             if (isset($data['id']) && !empty($data['id'])) {
    //                 $text = Text::filterRegion()->select('id')->find($data['id']);
    //                 $translated = $text->translated()->where('language_id', $data['language_id'])->select('id', 'language_id', 'name', 'img', 'created_at')->first();
    //             }

    //             $gallery_items = $gallery_type->content()->get();
    //             $current_menu_item_id = $gallery_type->id;
    //             $layout_grid_gallery = (isset($data['layout_grid_gallery']) ? $data['layout_grid_gallery'] : 'l4 m6 s12');
               
    //             $view = View::make('texts.parts.gallery', compact('gallery_items', 'gallery_type', 'current_menu_item_id', 'layout_grid_gallery', 'translated', 'text'))->render();
    //             return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
    //         }
    //         return response()->json(['success' => false, 'status' => 0, 'message' => 'could not load gallery items..']);
    //     }
    // }

    // public function remove_img(Request $request) {
    //     if ($request->ajax()) {
    //         $data = $request->all();
    //         if (isset($data['id']) && isset($data['language_id']) && isset($data['img']) && !empty($data['id'])) {
    //             $text = Text::filterRegion()->select('id')->find($data['id']);
    //             $translated = $text->translated()->where('language_id', $data['language_id'])->first();
    //             $image_names_arr = json_decode($translated->img, 1);

    //             if (($key = array_search($data['img'], $image_names_arr)) !== false) {
    //                 unset($image_names_arr[$key]);
    //             }

    //             $translated->img = json_encode($image_names_arr);
    //             $translated->save();
    //             return response()->json(['success' => true, 'status' => 1, 'message' => 'image removed',]);
    //         }
    //     }
    //     return response()->json(['success' => false, 'status' => 0, 'message' => 'could not remove image..']);
    // }
}