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
use Illuminate\Http\Request;
use App\Models\Tvoyo\User;
use App\Models\User as Admin;
use App\Models\Language;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Models\Order\OrderProduct;
use App\Models\Paypal\PaypalTransaction;
use App\Models\CartuTransaction;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Region;
use App\Models\Discount\Discount;
use App\Models\Product\Product;
use App\Models\Product\ProductMenu;
use App\Models\Product\ProductMenuItem;
use Carbon\Carbon;
use Config;
use Image;



class HomeController extends Controller {


  public function index() {
        if (Auth::guest()) {
            if (Session::has('message')) {
                $message = Session::get('message');
            } else {
                $message = 'Please enter your email address';
            }
            return Redirect::route('admin-login')->with('message', $message)->with('mesType', 'message');
        } else {
            $options  = [
              'catalog_count' => ProductMenuItem::filterRegion()->count(),
              'order_count_new' => Order::filterRegion()->whereDate('created_at', '=', Carbon::today()->toDateString())->count(),
            ];
            $currency = Currency::filterRegion()->first();
            return view('index', compact('options', 'currency'));
        }
  }

  public function usersChart(Request $request) {
      if ($request->ajax()) {
          $groupBy = 'WEEK(reg_date)';
          $dateFormat = '%W';

          $users = User::select('id', 'reg_date', 'region_id', DB::raw('count(*) as total'), DB::raw("DATE_FORMAT(reg_date, '".$dateFormat."') as datepoint"))
          /*->where('deleted_at', null)*/
          ->where('region_id',$this->region)
          //->whereBetween( DB::raw('created_at'), [$fromDate, $tillDate] )
          ->groupBy(DB::raw($groupBy))
          ->orderBy('reg_date', 'ASC')
          ->get();

            //dd($this->region);
          return response()->json(['success' => true, 'status' => 1, 'items' => $users, 'total_count' => User::/*where('deleted_at', null)->*/filterRegion()->count() ]);
      }
  }

  public function changeRegion(Request $request){
    if ($request->ajax()) {
      $data = $request->all();
      if ($data['region_id']) {
        $result = Region::make_current($data['region_id']);
        if ($result) {
          return response()->json(['success' => true, 'status' => 1, 'message' => 'region changed']);
        }
      }
      return response()->json(['success' => false, 'status' => 0, 'message' => 'could not change region']);
    }
  }

  public function getEditSocLinks() {
    $info = SiteInfo::where('name', 'soc_lionk_fb')->first();
    return view('admin.main.extra.soc', compact('info'));
  }

  public function updateSocialLinks(Request $request) {
    if ($request->ajax()) {
        $data = $request->all();
        if (!isset($data['status'])) {
            $data['status'] = 0;
        }
        $info = SiteInfo::where('name', 'soc_lionk_fb')->first();
        $info->url = $data['url'];
        $info->status = $data['status'];
        
        if ($info->save()) {
            return response()->json(['success' => true, 'status' => 1, 'message' => 'პარამეტრები შეიცვალა']);
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'პარამეტრების შეცვლა ვერ მოხერხდა']);
    }
  }

  public function getContactEmail() {
    $info = SiteInfo::where('name', 'site_contact_email')->first();
    return view('admin.main.extra.contact', compact('info'));
  }

  public function updateContactEmail(Request $request) {
    if ($request->ajax()) {
        $data = $request->all();
        $info = SiteInfo::where('name', 'site_contact_email')->first();
        $info->description = $data['email'];
        if ($info->save()) {
            return response()->json(['success' => true, 'status' => 1, 'message' => 'პარამეტრები შეიცვალა']);
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'პარამეტრების შეცვლა ვერ მოხერხდა']);
    }
  }

    public function updateActiveAdminsList(Request $request) { 
        if ($request->ajax()) {
            $data = $request->all();
            $active_admins = Admin::where('activity', $data['activity'])->get();

            $html = View::make('parts.right_sidebar_users', compact('active_admins'))->render();

            return response()->json(['success' => true, 'status' => 1, 'html' => $html]);
        }
    }

    public function updateTransactionsList(Request $request) { 
        if ($request->ajax()) {
            $data = $request->all();
            $cartu_transactions = CartuTransaction::filterRegion(false)->whereIn('result', ['Y', 'U'])->select('id', 'region_id', 'm_msg_type', 'm_trans_id', 'm_amt', 'm_currency', 'm_ip', 'result', 'date')->orderBy('date', 'desc')->limit(5)->get();
            $paypal_transactions = PaypalTransaction::filterRegion(false)->where('after_payment_status', 'Completed Successfully')->select('id', 'region_id', 'transaction_id', 'user_id', 'type', 'after_payment_status', 'after_payment_data', 'created_at')->orderBy('created_at', 'desc')->limit(5)->get();

            $html = View::make('parts.right_sidebar_transactions', compact('cartu_transactions', 'paypal_transactions'))->render();

            return response()->json(['success' => true, 'status' => 1, 'html' => $html]);
        }
    }


    

}