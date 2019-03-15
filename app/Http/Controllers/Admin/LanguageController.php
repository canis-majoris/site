<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\LanguageRepository;
use App\DataTables\LanguagesDataTable;
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
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Region;
use App\Models\Country;
use App\Models\Language;
use Validator;
//use App\Repositories\UserRepository;

class LanguageController extends Controller {

    public $language;

    public function __construct( LanguageRepository $language ) {
        parent::__construct();
        $this->item = $language;
        $this->language = $language;
    }

    public function index(LanguagesDataTable $dataTable) {
        return $dataTable->render('languages.index');
    }

    public function store() {
        
    }

    public function edit(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();
            $result = $this->language->getUpdate($data);
            $view = View::make('languages.manage.form', $result)->render();

            return response()->json(['success' => true, 'status' => 1, 'message' => 'new content', 'html' => $view]);
            
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong..']);
    }

    public function update(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();
            $result = [];
            $id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

            $validator = Validator::make($data, Language::rules($id));

            if($validator->fails()){

                return response()->json(['success' => false, 'status' => 0, 'message' => $validator->errors()->all()]);

            } else {

                $result = $this->language->update($id, $data);

                return response()->json($result);

            }
        }

        return response()->json(['success' => true, 'status' => 1, 'message' => 'could not update language..']);
    }

    public function delete(Request $request) {

        if ($request->ajax()) {

            $ids = $request->get('ids');
            $message = 'Language deleted';

            if (count($ids) > 1) {

                $message = count($ids).' languages deleted';

            }

            $result = $this->language->delete($ids);

            return response()->json(['success' => true, 'status' => $result['status'], 'messages' => $result['messages'], 'errors' => $result['errors']]);
        }

        return response()->json(['success' => true, 'status' => 1, 'message' => 'could not delete language..']); 
    }

    public function postChangeStatus(Request $request) {

        if ($request->ajax()) {

            $ids = $request->get('ids');
            $message = 'status updated';

            $result = $this->language->changeStatus($ids);

            if ($result['errors']) {

                $message = $result['errors'];

            }

            return response()->json(['success' => true, 'status' => $result['status'], 'message' => $message]);
        }

        return response()->json(['success' => false, 'error' => true, 'status' => $result['status'], 'message' => 'could not update status..']);
    }
}