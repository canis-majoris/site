<?php
namespace App\Http\Controllers\Admin;

use App\Repositories\Eloquent\MediaRepository;
use App\Repositories\Eloquent\LanguageRepository;
use App\Repositories\Eloquent\PageRepository;
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
use App\User;
use App\Language;
use App\Region;
use App\PageType;
use App\Page;
use App\PageLanguage;
use App\Gallery;
use App\GalleryType;
use Validator;
use Config;

class PageController extends MediaController {

    public $page;
    public $language;

    public function __construct( MediaRepository $media, PageRepository $page, LanguageRepository $language ) {
        parent::__construct($media);
        $this->item = $page;
        $this->view_directory = 'pages';
        $this->page = $page;
        $this->language = $language;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(PagesDataTable $dataTable) {
        $languages = $this->language->getAll();
        $regions = Region::get();
        $menu_items = [];

        if ($regions->count()) {

            $menu_items = $this->page->getTypes();

        }

        $language_id = $languages->first()->id;
        
        return $dataTable->render('pages.index', compact('menu_items', 'languages', 'language_id'));

    }

    public function loadNestableList(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();

            $result = $this->page->loadNestableList($data);

            return response()->json(['success' => true, 'status' => 1, 'html' => $result['generated_nestable_list'], 'response' => ['current_menu_item_id' => $result['current_menu_item_id'], 'add_item_to_parent' => $result['add_item_to_parent']]]);
        }
    }

    public function updateNestableList(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();

            $result = $this->page->updateNestableList($data);

            return response()->json(['success' => true, 'status' => 1, 'html' => $result['generated_nestable_list']]);
        }
    }

    public function update(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();
            $result = $this->page->getUpdate($data);
            $current_menu_item_id = $result['parent_item'] != null ? $result['parent_item']->id : null;
            $add_item_to_parent = $result['add_item_to_parent'];

            $view = View::make('pages.manage.form', $result)->render();

            return response()->json(['success' => true, 'status' => 1, 'message' => 'new content', 'html' => $view, 'response' => ['current_menu_item_id' => $current_menu_item_id, 'add_item_to_parent' => $add_item_to_parent]]);
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong..']);
    }

    public function save(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();
            $result = [];
            $id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

            $validator = Validator::make($data, Page::rules($id));
            
            if($validator->fails()){

                return response()->json(['success' => false, 'status' => 0, 'message' => $validator->errors()->all()]);

            } else {

                $result = $this->page->update($id, $data);
                return response()->json($result);

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

            $errors = $this->page->delete($ids);

            if ($errors) {

                $message = $errors;

            }

            return response()->json(['success' => true, 'status' => 1, 'message' => $message]);
        }

        return response()->json(['success' => true, 'status' => 1, 'message' => 'could not delete page(s)..']); 

    }

    public function changeStatus(Request $request) {
        if ($request->ajax()) {

            $ids = $request->get('ids');
            $message = 'status updated';

            $result = $this->page->changeStatus($ids);

            if ($result['errors']) {

                $message = $result['errors'];

            }

            return response()->json(['success' => true, 'status' => $result['status'], 'message' => $message]);

        }

        return response()->json(['success' => false, 'error' => true, 'status' => $result['status'], 'message' => 'could not update status..']);
    }

}