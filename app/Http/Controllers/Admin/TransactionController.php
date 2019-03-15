<?php
namespace App\Http\Controllers; //admin add

//use App\Repositories\LanguageRepository;
//use App\DataTables\MobileServicesDataTable;
use App\DataTables\Transactions\CartuTransactionsDataTable as CartuTransactionsDataTable;
use App\DataTables\Transactions\PaypalTransactionsDataTable as PaypalTransactionsDataTable;
use App\DataTables\LanguagesDataTable;
use App\Repositories\Eloquent\TransactionRepository;
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
use App\Models\Region;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Transaction;
use App\Models\CartuTransaction;
use App\Models\Paypal\PaypalTransaction;
use Config;

class TransactionController extends Controller {

    protected $transaction;

    /**
     * Create a new TransactionRepository instance.
     *
     * @param  TransactionRepository $transaction
     * @return void
     */
    public function __construct(TransactionRepository $transaction) {
        parent::__construct();
        $this->item = $transaction;
        $this->view_directory = 'transaction';
        $this->transaction = $transaction;
    }

	public function index(Request $request) {
        $provider = ($request->has('provider') ? $request->get('provider') : null);
        if ($provider && ($provider == 'cartu' || $provider == 'paypal')) {
            $filter = View::make('transactions.filters.'.$provider/*, compact()*/)->render();
            $table = View::make('transactions.providers.'.$provider/*, compact()*/)->render();
            return view('transactions.index', compact('provider', 'filter', 'table'));
        }

        abort(404);
    }

	public function getCartuTransactionsDataTable(CartuTransactionsDataTable $dataTable) {
        $countries = Country::all();
        return $dataTable->render('transactions.providers.cartu', compact('countries'));
    }

    public function getPaypalTransactionsDataTable(PaypalTransactionsDataTable $dataTable) {
        $countries = Country::all();
        return $dataTable->render('transactions.providers.paypal', compact('countries'));
    }

    public function loadFilter(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();

            if (isset($data['type'])) {
                $provider = $data['type'];
                switch ($provider) {
                    case 'cartu': $showcolumns = [1,1,1,1,1,1,1,1,1,1]; break;
                    case 'paypal': $showcolumns = [1,1,1,1,1,1,1,1,1,1]; break;
                }
                $countries = Country::all();
                $filter = View::make('transactions.filters.'.$provider, compact('provider', 'countries'))->render();
                return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $filter, 'showcolumns' => $showcolumns]);
            }
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong..']);
    }

    public function loadTable(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();

            if (isset($data['provider'])) {
                $provider = $data['provider'];
                $table = View::make('transactions.providers.'.$provider/*, compact()*/)->render();
                return response()->json(['success' => true, 'status' => 1, 'message' => '', 'html' => $table]);
            }
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong..']);
    }

}