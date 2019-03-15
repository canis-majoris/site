<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\MediaRepository;
use Validator;
use App\Models\Language;
use App\Models\Region;
use App\Models\Product\Product;
use App\Models\Settings\Settings;
use App\Models\Text\Text;
use App\Models\Page\Page;
use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryType;

class MediaController extends Controller {

    /**
     * The MenuRepository instance.
     *
     * @var App\Repositories\DiscountRepository
     */
    protected $media;
    protected $item;
    protected $view_directory;

    /**
     * Create a new DiscountController instance.
     *
     * @param  App\Repositories\DiscountRepository $discount
     * @return void
     */
    public function __construct(MediaRepository $media) {
       // parent::__construct();
        $this->media = $media;
    }

    public function update_img(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            $result = $this->media->update_img($this->item, $data);

            if ($result) return response()->json(['success' => true, 'status' => 1, 'message' => 'Image uploaded', 'newfilename' => $result]);
        }
        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong..']);
    }

    // public function load_img_form(Request $request) {
    //     if ($request->ajax()) {
    //         $data = $request->all();
    //         //$result = $this->media->load_img_form($this->item, $data);

    //         $product = null;
    //         $translated = null;
    //         if ($data['id'] && !empty($data['id'])) {
    //             $product = Product::filterRegion()->find($data['id']);
    //             $translated = $product->translated()->where('language_id', $data['language_id'])->select('name', 'img', 'created_at')->first();
    //         }

    //         $view = View::make('media.image', compact('product', 'translated'))->render();
    //         return response()->json(['success' => true, 'status' => 1, 'message' => 'new content', 'html' => $view]);
    //     }
    // }

    public function attach_image_form(Request $request) {
        if ($request->ajax()) {

            $data = $request->all();
            $result = $this->media->attach_image_form($this->item, $data);

            if ($result['item']) {
                $view = view($this->view_directory . '.parts.attach_images', $result)->render();
                return response()->json(['success' => true, 'status' => 1, 'message' => 'new content', 'html' => $view]);
            }
        }
    }

    public function attach_images(Request $request) {
        if ($request->ajax()) {

            $data = $request->all();
            $result = $this->media->attach_images($this->item, $data);

            if ($result) {
                return response()->json(['success' => true, 'status' => 1, 'message' => 'Image(s) attached']);
            }
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong']);
    }

    public function load_attached_images(Request $request) {
        if ($request->ajax()) {

            $data = $request->all();
            $result = $this->media->load_attached_images($this->item, $data);

            if ($result['item']) {
                $view = view($this->view_directory . '.parts.selected_images', $result)->render();
                return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
            }
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'could not load attached gallery items..']);
    }

    public function load_gallery_items(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            $result = $this->media->load_gallery_items($this->item, $data);

            if ($result['status']) {

                $view = view('admin.media.gallery_items_select', $result)->render();
                return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $view]);
            }
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'could not load gallery items..']);
    }

    public function remove_img(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
            $result = $this->media->remove_img($this->item, $data);

            if ($result) {
                return response()->json(['success' => true, 'status' => 1, 'message' => 'image removed']);
            }
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'could not remove image..']);
    }
}
