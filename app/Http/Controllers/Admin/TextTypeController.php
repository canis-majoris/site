<?php
namespace App\Http\Controllers; //admin add

//use App\Repositories\LanguageRepository;
use App\DataTables\TextsDataTable;
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
use App\Models\User;
use App\Models\Text\Text;
use App\Models\Language;
use App\Models\Region;
use App\Models\Text\TextType;
use App\Models\Text\TextLanguage;
use Config;
use Validator;

class TextTypeController extends TypeMenuController {

    public $text;

    public function __construct( TextRepository $text ) {
        parent::__construct();
        $this->text = $text;
        $this->view_directory = 'texts';
        $this->item = new TextType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(TextsDataTable $dataTable)
    {
        return $dataTable->render('texts.index', []);

        //return view('main.languages.index', compact('languages'/*, 'links'*/));
    }
    
    public function create() {
        return view('texts.create');
    }

    public function show() {
        return view('texts.create');
    }
}