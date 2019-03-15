<?php
namespace App\Http\Controllers; //admin add

//use App\Repositories\LanguageRepository;
use App\DataTables\Settings\SettingsDataTable;
use App\Repositories\Eloquent\SettingsRepository;
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
use App\Models\Settings\Settings;
use App\Models\Language;
use App\Models\Region;
use App\Models\Settings\SettingsType;
use App\Models\Settings\SettingsLanguage;
use Config;
use Validator;

class SettingsTypeController extends TypeMenuController {

    public $settings;

    public function __construct( SettingsRepository $settings ) {
        parent::__construct();
        $this->settings = $settings;
        $this->view_directory = 'settings';
        $this->item = new SettingsType;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(SettingsDataTable $dataTable)
    {
        return $dataTable->render('settings.index', []);

        //return view('main.languages.index', compact('languages'/*, 'links'*/));
    }
    
    public function create() {
        return view('settings.create');
    }

    public function show() {
        return view('settings.create');
    }

    
}