<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\User as Admin;
use App\Models\Language;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Models\Order\OrderProduct;
use App\Models\Order\OrderProductStatus;
use App\Models\CartuTransaction;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Region;
use App\Models\Discount\Discount;
use App\Models\Product\Product;
use App\Models\Product\ProductMenu;
use App\Models\Product\ProductMenuItem;
use Carbon\Carbon;
use Charts;
use Config;
use Image;
use Redis;



class ChartsPageController extends Controller {

    protected $colors;

    public function __construct() {
        ///parent::__construct();
        View::share('for_all_regions', true);
        $this->colors = [
            'default' => 'rgba(168,139,223,1)',
            1 => 'rgba(13, 195, 170, 1)',
            2 => 'rgba(252, 194, 0, 1)',
            3 => 'rgba(231, 76, 60, 1)',
            4 => 'rgba(52, 152, 219, 1)',
            5 => 'rgba(130, 3, 123, 1)',
        ];

        $this->colors_reverse = [
            'active' => 'rgb(139, 195, 74)',
            'passive' => 'rgba(224, 224, 224, 0.9)',
        ];

        $this->color_hex_main = '#8BC34A';
      //$this->remote_connection = config('database.region.'.$this->region.'.database_remote');
    }

    public function index() {   

       // Redis::set('name', 'Taylor');

        // die;
        return view('admin.charts.index');
    }

    public function show(Request $request){
        if ($request->ajax()) {
            $data = $request->all();
            if ($name = $data['name']) {
                $chart = $this->$name($data);
                return response()->json(['success' => true, 'status' => 1, 'chart' => $chart, 'data' => $data ]);
            }
        }
        return response()->json(['success' => false, 'status' => 10, 'message' => 'Something went wrong..']);
    }


    public function all_users($data) {
        //USERS
        $users = User::filterRegion(false)->select('users.id', 'users.created_at', 'users.region_id', 'users.active_service', DB::raw('count(*) as total'), DB::raw('YEAR(users.created_at) year, MONTH(users.created_at) month'))
        ->where('users.deleted_at', null)
        //->whereBetween('created_at', [$fromDate, $tillDate] )
        ->groupBy('active_service')
        ->orderBy('created_at', 'ASC')
        ->get();

        $users_count = 0;

        //pie
        $pie_arr = [];
        foreach ($users as $u) {
            $label = '';
            switch ($u->active_service) {
                case 0: $label = trans('main.charts.Inactive'); break;
                case 1: $label = trans('main.charts.Active mobile services, no active STB services'); break;
                case 2: $label = trans('main.charts.Active STB services, no active mobile services'); break;
                case 3: $label = trans('main.charts.Active STB services as well as mobile services'); break;
                case 4: $label = trans('main.charts.Active STB services with multiroom, no active mobile services'); break;
                case 5: $label = trans('main.charts.Active STB services with multiroom as well as mobile services'); break;
            }
            $pie_arr []= ['y' => $u->total, 'name' => $label, 'color' => $this->hex2rgba($this->color_hex_main, ($u->active_service > 0 ? min((2*($u->active_service + 1)/10), 1) : 0.1)), 'visible' => ($u->active_service > 0 ? true : false) ];

            $users_count += ($u->active_service > 0 ? $u->total : $u->total);
        }
        $pie_options = ['values' => ['name' => 'Users', 'colorByPoint' => true, 'data' => $pie_arr], 'misc' => [/*'users_count' => $users_count*/]];

        return $pie_options;
    }

    public function users_by_region($data) {
        //USERS
        $users = User::filterRegion(false)
        ->join(DB::raw('tvoyo_administration.regions AS regions'), 'regions.id', '=', 'users.region_id')->select('users.id', 'users.created_at', 'users.region_id', DB::raw('count(*) as total'), DB::raw('YEAR(users.created_at) year, MONTH(users.created_at) month'), DB::raw('regions.name as region'))
        ->where('users.deleted_at', null)
        //->whereBetween('created_at', [$fromDate, $tillDate] )
        ->groupBy('region_id')
        ->orderBy('created_at', 'ASC')
        ->get();

        $users_count = 0;
        
        //area
        $area_options = ['values' => $users->pluck('total'), 'labels' => array_column($users->toArray(), 'region')];

        //pie
        $pie_arr = [];
        $drilldown_arr = [];
        foreach ($users as $u) {
            $region_users = User::where('region_id', $u->region_id)->select('users.id', 'users.created_at', 'users.region_id', 'users.active_service', DB::raw('count(*) as total'))
            ->groupBy('active_service')->get();
            $region_users_arr = [];
            foreach ($region_users as $r_user) {
                $label = '';
                switch ($r_user->active_service) {
                    case 0: $label = 'Inactive'; break;
                    case 1: $label = 'Active mobile services, no active STB services'; break;
                    case 2: $label = 'Active STB services, no active mobile services'; break;
                    case 3: $label = 'Active STB services as well as mobile services'; break;
                    case 4: $label = 'Active STB services with multiroom, no active mobile services'; break;
                    case 5: $label = 'Active STB services with multiroom as well as mobile services'; break;
                }

                $region_users_arr []= ['name' => $label, 'y' => $r_user->total, 'color' => $this->hex2rgba($this->color_hex_main, ($r_user->active_service > 0 ? min((2*($r_user->active_service + 1)/10), 1) : 0.1)), 'visible' => ($r_user->active_service > 0 ? true : false) ];
            }
            $pie_arr []= ['y' => $u->total, 'name' => $u->region, 'drilldown' => $u->region];
            $drilldown_arr []= ['name' => $u->region, 'id' => $u->region, 'color' => $this->colors[$u->region_id], 'data' => $region_users_arr];
        }

        $pie_options = ['values' => ['name' => 'Users', 'colorByPoint' => true, 'data' => $pie_arr], 'drilldown' => $drilldown_arr, 'misc' => [/*'users_count' => $users_count*/]];

        return $pie_options;
    }

    public function all_stbs($data) {

        //STBS
        //
        $stbs = OrderProduct::filterRegion(false)
        //->join(DB::raw('tvoyo_billing.multiroom_control AS m_c'), 'm_c.stb_id', '=', 'orders_products.id')
        ->join(DB::raw('tvoyo_billing.products AS products'), 'products.id', '=', 'orders_products.product_id')
        ->select('orders_products.id', 'orders_products.created_at', 'orders_products.region_id', 'orders_products.is_p', 'orders_products.user_id', 'orders_products.product_id', 'orders_products.multir_ind', 'orders_products.active_service_id', 'products.for_mobile', 'products.is_p', DB::raw('count(*) as total'), DB::raw('(
                CASE 
                    WHEN ISNULL(orders_products.active_service_id) THEN 0
                    WHEN !ISNULL(orders_products.active_service_id) && orders_products.multir_ind = 0 THEN 1
                    WHEN !ISNULL(orders_products.active_service_id) && orders_products.multir_ind = 1 THEN 2
                END) AS has_service'), /*DB::raw('products.for_mobile as for_mobile'), DB::raw('products.is_p as is_p'), DB::raw('count(m_c.stb_id) as attached_services_count'),*/ DB::raw('YEAR(orders_products.created_at) year, MONTH(orders_products.created_at) month'))
        ->where('products.is_p', 1)
        ->where('products.for_mobile', 0)
        //->whereBetween('created_at', [$fromDate, $tillDate] )
        ->groupBy(['has_service'])
        ->orderBy('created_at', 'ASC')
        ->get();


        $stbs_count = 0;
        //pie
        $pie_arr = [];
        foreach ($stbs as $stb) {
            $label = '';
            switch ($stb->has_service) {
                case 0: $label = 'No service'; break;
                case 1: $label = 'Active service'; break;
                case 2: $label = 'Active multiroom service'; break;
            }
            $pie_arr []= ['y' => $stb->total, 'name' => $label, 'color' => $this->hex2rgba($this->color_hex_main, ($stb->has_service > 0 ? min((2*($stb->has_service + 3)/10), 1) : 0.1))/*, 'visible' => ($stb->has_service > 0 ? true : false) */];

            $stbs_count += $stb->total;
        }

        $pie_options = ['values' => ['name' => 'STBs', 'colorByPoint' => true, 'data' => $pie_arr], 'misc' => [/*'stbs_count' => $stbs_count*/]];

        return $pie_options;
    }

    public function stbs_by_region($data) {

        //STBS

        $stbs = OrderProduct::filterRegion(false)
        //->join(DB::raw('tvoyo_billing.multiroom_control AS m_c'), 'm_c.stb_id', '=', 'orders_products.id')
        ->join(DB::raw('tvoyo_administration.regions AS regions'), 'regions.id', '=', 'orders_products.region_id')
        ->join(DB::raw('tvoyo_billing.products AS products'), 'products.id', '=', 'orders_products.product_id')
        ->select('orders_products.id', 'orders_products.created_at', 'orders_products.region_id', 'orders_products.is_p', 'orders_products.user_id', 'orders_products.product_id', 'orders_products.multir_ind', 'orders_products.active_service_id', 'products.for_mobile', 'products.is_p', DB::raw('count(*) as total'), DB::raw('(
                CASE 
                    WHEN ISNULL(orders_products.active_service_id) THEN 0
                    WHEN !ISNULL(orders_products.active_service_id) && orders_products.multir_ind = 0 THEN 1
                    WHEN !ISNULL(orders_products.active_service_id) && orders_products.multir_ind = 1 THEN 2
                END) AS has_service'), DB::raw('YEAR(orders_products.created_at) year, MONTH(orders_products.created_at) month'), DB::raw('regions.name as region'))
        ->where('products.is_p', 1)
        ->where('products.for_mobile', 0)
        //->whereBetween('created_at', [$fromDate, $tillDate] )
        ->groupBy('region_id')
        ->orderBy('created_at', 'ASC')
        ->get();

        $stbs_count = 0;
        
        //area
        $area_options = ['values' => $stbs->pluck('total'), 'labels' => array_column($stbs->toArray(), 'region')];

        //pie
        $pie_arr = [];
        $drilldown_arr = [];
        foreach ($stbs as $stb) {
            $region_stbs = OrderProduct::join(DB::raw('tvoyo_billing.products AS products'), 'products.id', '=', 'orders_products.product_id')
            ->where('orders_products.region_id', $stb->region_id)->select('orders_products.id', 'orders_products.created_at', 'orders_products.region_id', 'orders_products.active_service_id', DB::raw('count(*) as total'), DB::raw('count(*) as total'), DB::raw('(
                CASE 
                    WHEN ISNULL(orders_products.active_service_id) THEN 0
                    WHEN !ISNULL(orders_products.active_service_id) && orders_products.multir_ind = 0 THEN 1
                    WHEN !ISNULL(orders_products.active_service_id) && orders_products.multir_ind = 1 THEN 2
                END) AS has_service'))
            ->where('products.is_p', 1)
            ->where('products.for_mobile', 0)
            ->groupBy('has_service')
            ->get();

            $region_stbs_arr = [];
            foreach ($region_stbs as $r_stb) {
                $label = '';
                switch ($r_stb->has_service) {
                    case 0: $label = 'No service'; break;
                    case 1: $label = 'Active service'; break;
                    case 2: $label = 'Active multiroom service'; break;
                }

                $region_stbs_arr []= ['name' => $label, 'y' => $r_stb->total, 'color' => $this->hex2rgba($this->color_hex_main, ($r_stb->has_service > 0 ? min((2*($r_stb->has_service + 3)/10), 1) : 0.1))/*, 'visible' => ($r_stb->has_service > 0 ? true : false) */];
                $stbs_count += $r_stb->total;
            }
            $pie_arr []= ['y' => $stb->total, 'name' => $stb->region, 'drilldown' => $stb->region];
            $drilldown_arr []= ['name' => $stb->region, 'id' => $stb->region, 'color' => $this->colors[$stb->region_id], 'data' => $region_stbs_arr];
        }

        $pie_options = ['values' => ['name' => 'Users', 'colorByPoint' => true, 'data' => $pie_arr], 'drilldown' => $drilldown_arr, 'misc' => [/*'stbs_count' => $stbs_count*/]];

        return $pie_options;
    }

    public function new_users($data) {
        if (isset($data['number']) && isset($data['range'])) {
            $fromDate = Carbon::now()->subMonth()->toDateString();
            $gb_arr = [];
            switch ($data['range']) {
                case 'full': $fromDate = $first_date = Carbon::now()->subYears(50)->toDateString(); $gb_arr = ['year', 'month']; $dateFormat = '%M %Y'; break;
                case 'day': $fromDate = Carbon::now()->subDays($data['number'])->toDateString(); $gb_arr = ['day', 'hour']; $dateFormat = '%H %I %S'; break;
                case 'week': $fromDate = Carbon::now()->subWeeks($data['number'])->toDateString(); $gb_arr = ['day']; $dateFormat = '%D'; break;
                case 'month': 
                    $fromDate = Carbon::now()->subMonths($data['number'])->toDateString();
                    if ($data['number'] > 1) {
                        $gb_arr = ['month']; $dateFormat = '%M';
                    } else {
                        $gb_arr = ['month', 'day']; $dateFormat = '%M %D';
                    }
                    break;
                case 'year': $fromDate = Carbon::now()->subYears($data['number'])->toDateString(); $gb_arr = ['year', 'month']; $dateFormat = '%M %Y'; break;
                default: break;
            }

            $count_parameter = 'count(*)';
            $user_status_val = [0, 1, 2, 3, 4, 5];

            if (isset($data['only_active']) && $data['only_active'] != 0) {
                //var_dump($data['only_active']); die;
                $user_status_val = $data['only_active'];
            }

            $regions =  Region::all();
            $tillDate = Carbon::now()->toDateString();
            $values_arr = [];

            $labels = User::filterRegion(false)->select('id', 'reg_date', 'region_id', 'active_service', DB::raw("DATE_FORMAT(reg_date, '".$dateFormat."') as datepoint"), DB::raw('YEAR(reg_date) year, MONTH(reg_date) month, WEEK(reg_date) week, DAY(reg_date) day, HOUR(reg_date) hour'))
                    ->where('deleted_at', null)
                    ->whereIn('active_service', $user_status_val)
                    ->whereBetween('reg_date', [$fromDate, $tillDate] )
                    ->groupBy($gb_arr)
                    ->orderBy('reg_date', 'ASC')->pluck('datepoint');

           // return [$labels];

            $users_count = 0;
            foreach ($regions as $region) {

                $data_arr = [];
                $region_users = User::select('id', 'reg_date', 'region_id', 'active_service', DB::raw($count_parameter . ' as total'), DB::raw("DATE_FORMAT(reg_date, '".$dateFormat."') as datepoint"), DB::raw('('.$fromDate.' - datediff(CURDATE(), reg_date)) as from_today'), DB::raw('YEAR(reg_date) year, MONTH(reg_date) month, WEEK(reg_date) week, DAY(reg_date) day, HOUR(reg_date) hour'),
                    DB::raw("concat(DATE_FORMAT(reg_date, '".$dateFormat."'), '|',count(*)) as date_total"))
                    ->where('deleted_at', null)
                    ->whereIn('active_service', $user_status_val)
                    ->where('region_id', $region->id)
                    ->whereBetween('reg_date', [$fromDate, $tillDate] )
                    ->groupBy($gb_arr)
                    ->orderBy('reg_date', 'ASC')->get()->toArray();

                $r_u_data = [];
                foreach ($region_users as $r_u) {
                    $r_u_data[$r_u['datepoint']] = $r_u['total'];
                    $users_count += $r_u['total'];
                }

                foreach ($labels as $label) {
                    $data_arr []= (isset($r_u_data[$label]) ? $r_u_data[$label] : 0);
                }

                $values_arr []= [
                    'name'  => $region->name,
                    'color' => $this->colors[$region->id],
                    'data'  => $data_arr
                ];
            }

            $values = $values_arr;

            if (isset($data['by_region']) && $data['by_region'] == "0") {
                $sum = [];
                foreach ($values_arr as $d_v) {
                    for ($i=0; $i < count($d_v['data']); $i++) { 
                        if (!isset($sum[$i])) {
                            $sum[$i] = $d_v['data'][$i];
                        } else
                            $sum[$i] += $d_v['data'][$i];
                    }
                }
                $values = ['name'  => 'New users', 'color' => $this->colors['default'], 'data' => $sum];
                
            }


            // $orders_count = Order::filterRegion(false)
            // ->where('orders_status_id', '!=',  3)
            // ->whereBetween('created_at', [$fromDate, $tillDate] )
            // ->count();

            return ['values' => $values, 'labels' => $labels, 'misc' => ['new_users_count' => $users_count]];
        }
    }

    public function orders($data) {
        //ORDERS
        //
        if (isset($data['number']) && isset($data['range'])) {
            $fromDate = Carbon::now()->subMonth()->toDateString();
            $gb_arr = [];
            switch ($data['range']) {
                case 'full': $fromDate = $first_date = Order::filterRegion(false)->orderBy('created_at', 'ASC')->first()->created_at->toDateString(); $gb_arr = ['year', 'month']; $dateFormat = '%M %Y'; break;
                case 'day': $fromDate = Carbon::now()->subDays($data['number'])->toDateString(); $gb_arr = ['day', 'hour']; $dateFormat = '%H %I %S'; break;
                case 'week': $fromDate = Carbon::now()->subWeeks($data['number'])->toDateString(); $gb_arr = ['day']; $dateFormat = '%D'; break;
                case 'month': 
                    $fromDate = Carbon::now()->subMonths($data['number'])->toDateString();
                    if ($data['number'] > 1) {
                        $gb_arr = ['month']; $dateFormat = '%M';
                    } else {
                        $gb_arr = ['month', 'day']; $dateFormat = '%M %D';
                    }
                    break;
                case 'year': $fromDate = Carbon::now()->subYears($data['number'])->toDateString(); $gb_arr = ['year', 'month']; $dateFormat = '%M %Y'; break;
                default: break;
            }



            $count_parameter = 'count(*)';
            $filter_pay_type = [0, 1, 2, 3, 10];
            if (isset($data['by_revenue']) && $data['by_revenue'] == 1) {
                $count_parameter = 'sum(globtotal)';
                $filter_pay_type = [0, 3, 10];
            }

            $tillDate = Carbon::now()->toDateString();

            $orders = Order::filterRegion(false)->select('id', 'created_at', 'region_id', DB::raw($count_parameter . ' as total'), DB::raw("DATE_FORMAT(created_at, '".$dateFormat."') as datepoint"), DB::raw('('.$fromDate.' - datediff(CURDATE(), created_at)) as from_today'), DB::raw('YEAR(created_at) year, MONTH(created_at) month, WEEK(created_at) week, DAY(created_at) day, HOUR(created_at) hour'))
            ->where('deleted_at', null)
            ->where('orders_status_id', '!=',  3)
            ->whereIn('pay_type',  $filter_pay_type)
            ->whereBetween('created_at', [$fromDate, $tillDate] )
            ->groupBy($gb_arr)
            ->orderBy('created_at', 'ASC')
            ->get();

            $orders_count = Order::filterRegion(false)->where('deleted_at', null)->where('orders_status_id', '!=',  3)->count();
            $filter_orders_count = array_sum($orders->pluck('total')->toArray());

            if (isset($data['by_revenue']) && $data['by_revenue'] == 1) {
                $currency = Currency::filterRegion()->first();
                $filter_orders_count .= $currency->sign;
            }

            return ['values' => ['name'  => 'Orders', 'color' => $this->colors['default'], 'data' => $orders->pluck('total')], 'labels' => $orders->pluck('datepoint'), 
            'misc' => ['filter_orders_count' => $filter_orders_count/*, 'orders_count' => $orders_count*/]];
        }
    }

    public function orders_by_region($data) {

        // $orders = Order::where('id', '>', 2000)->where('id', '<', 2500)->get();
        // foreach ($orders as $order) {
        //     $order->region_id = 3;
        //     $order->save();
        // }
        //ORDERS
        //
        if (isset($data['number']) && isset($data['range'])) {
            $fromDate = Carbon::now()->subMonth()->toDateString();
            $gb_arr = [];
            switch ($data['range']) {
                case 'full': $fromDate = $first_date = Order::filterRegion(false)->orderBy('created_at', 'ASC')->first()->created_at->toDateString(); $gb_arr = ['year', 'month']; $dateFormat = '%M %Y'; break;
                case 'day': $fromDate = Carbon::now()->subDays($data['number'])->toDateString(); $gb_arr = ['day', 'hour']; $dateFormat = '%H %I %S'; break;
                case 'week': $fromDate = Carbon::now()->subWeeks($data['number'])->toDateString(); $gb_arr = ['day']; $dateFormat = '%D'; break;
                case 'month': 
                    $fromDate = Carbon::now()->subMonths($data['number'])->toDateString();
                    if ($data['number'] > 1) {
                        $gb_arr = ['month']; $dateFormat = '%M';
                    } else {
                        $gb_arr = ['month', 'day']; $dateFormat = '%M %D';
                    }
                    break;
                case 'year': $fromDate = Carbon::now()->subYears($data['number'])->toDateString(); $gb_arr = ['year', 'month']; $dateFormat = '%M %Y'; break;
                default: break;
            }

            $count_parameter = 'count(*)';
            $filter_pay_type = [0, 1, 2, 3, 10];
            $regions_revenue_string = '';

            if (isset($data['by_revenue']) && $data['by_revenue'] == 1) {
                $count_parameter = 'sum(globtotal)';
                $filter_pay_type = [0, 3, 10];
            }

            $regions =  Region::all();
            $tillDate = Carbon::now()->toDateString();
            $values_arr = [];

            $labels = Order::filterRegion(false)->select('id', 'created_at', DB::raw("DATE_FORMAT(created_at, '".$dateFormat."') as datepoint"), DB::raw('YEAR(created_at) year, MONTH(created_at) month, WEEK(created_at) week, DAY(created_at) day, HOUR(created_at) hour'))
                    ->where('deleted_at', null)
                    ->where('orders_status_id', '!=',  3)
                    ->whereIn('pay_type',  $filter_pay_type)
                    ->whereBetween('created_at', [$fromDate, $tillDate] )
                    ->groupBy($gb_arr)
                    ->orderBy('created_at', 'ASC')->pluck('datepoint');

           // return [$labels];

            $orders_count = Order::filterRegion(false)->where('deleted_at', null)->where('orders_status_id', '!=',  3)->count();
            $filter_orders_count = 0;
            $regions_revenue_arr = [];
            foreach ($regions as $region) {
                $regions_revenue_arr[$region->id] = 0;
                $data_arr = [];
                $region_orders = Order::select('id', 'created_at', 'region_id', DB::raw($count_parameter . ' as total'), DB::raw("DATE_FORMAT(created_at, '".$dateFormat."') as datepoint"), DB::raw('('.$fromDate.' - datediff(CURDATE(), created_at)) as from_today'), DB::raw('YEAR(created_at) year, MONTH(created_at) month, WEEK(created_at) week, DAY(created_at) day, HOUR(created_at) hour'),
                    DB::raw("concat(DATE_FORMAT(created_at, '".$dateFormat."'), '|',count(*)) as date_total"))
                    ->where('deleted_at', null)
                    ->where('orders_status_id', '!=',  3)
                    ->whereIn('pay_type',  $filter_pay_type)
                    ->where('region_id', $region->id)
                    ->whereBetween('created_at', [$fromDate, $tillDate] )
                    ->groupBy($gb_arr)
                    ->orderBy('created_at', 'ASC')->get()->toArray();

                $r_o_data = [];
                foreach ($region_orders as $r_o) {
                    $r_o_data[$r_o['datepoint']] = $r_o['total'];
                    $filter_orders_count += $r_o['total'];
                    $regions_revenue_arr[$region->id] += $r_o['total'];
                }

                foreach ($labels as $label) {
                    $data_arr []= (isset($r_o_data[$label]) ? $r_o_data[$label] : 0);
                }


                $values_arr []= [
                    'name'  => $region->name,
                    'color' => $this->colors[$region->id],
                    'data'  => $data_arr
                ];
            }



            foreach ($regions_revenue_arr as $reg_rev_id => $reg_rev_v) {
                if ($reg_rev_v > 0) {
                    $region = Region::find($reg_rev_id);
                    $currency_sign = Currency::where('region_id', $region->id)->first()->sign;
                    $regions_revenue_string .= '<span class="custom-inline-badge-1 custom-color-6 white-text z-depth-1">' .$region->name . ':&nbsp;<b>' . $currency_sign . '<span class="counter">' . $reg_rev_v .'</span></b></span>';
                }
            }

            $values = $values_arr;

            if (isset($data['by_region']) && $data['by_region'] == "0") {
                $sum = [];
                foreach ($values_arr as $d_v) {
                    for ($i=0; $i < count($d_v['data']); $i++) { 
                        if (!isset($sum[$i])) {
                            $sum[$i] = $d_v['data'][$i];
                        } else
                            $sum[$i] += $d_v['data'][$i];
                    }
                }
                $values = ['name'  => 'Orders', 'color' => $this->colors['default'], 'data' => $sum];
                
            }

            $filter_orders_count =  (isset($data['by_revenue']) && $data['by_revenue'] == 1) ? '<small style="margin-right: 10px; margin-left: 0;"> '. trans('main.misc.in_total_revenue') . '</small><span class="">' . $regions_revenue_string . '</span>' : '<small style="margin-right: 10px; margin-left: 0;"> '. trans('main.misc.in_total') . '</small><span class="counter">' . $filter_orders_count . '</span>';

            return ['values' => $values, 'labels' => $labels, 'html' => ['filter_orders_count' => $filter_orders_count/*, 'orders_count' => $orders_count*/]];
        }
    }

    public function orders_products_by_region($data) {

        // OrderProduct::chunk(50, function ($ops){
        //     foreach ($ops as $op) {
        //         $update_arr = [];
        //         $user = $op->user;
        //         if ($user) {
        //             $update_arr['region_id'] = $user->region_id;
        //         }

        //         $op->update($update_arr);
        //     }
        // });
        //USERS

        $indexes_total = [];
        $indexes_total_by_region = [];
        $regions = Region::all();
        $column_chart = [];
        //foreach ($regions as $reg) {
        $orders_products = OrderProduct::filterRegion(false)
        ->join(DB::raw('tvoyo_administration.regions AS regions'), 'regions.id', '=', 'orders_products.region_id')
        ->join(DB::raw('tvoyo_billing.products AS products'), 'products.id', '=', 'orders_products.product_id')
        ->select('orders_products.id', 'orders_products.created_at', 'orders_products.region_id', 'orders_products.products_statuse_id', DB::raw('count(*) as total'), DB::raw('YEAR(orders_products.created_at) year, MONTH(orders_products.created_at) month'), DB::raw('regions.name as region'), DB::raw('products.ind as product_index'), DB::raw('products.id as product_id'))
        ->where('orders_products.deleted_at', null)
        ->where('orders_products.products_statuse_id', '!=', null)
        //->where('orders_products.region_id', $reg->id)
        ->whereHas('product', function($q) {
            $q->where('products.is_service', 1)
            ->where('products.for_mobile', 0);
        })
        //->whereBetween('created_at', [$fromDate, $tillDate] )
        ->groupBy(['region_id', 'product_index', 'products_statuse_id'])
        ->orderBy('created_at', 'ASC')
        ->get();

        
        foreach ($orders_products as $op) {
            if (!isset($indexes_total['active'][$op->product_index])) {
                $indexes_total['active'][$op->product_index] = 0;
            }

            if (!isset($indexes_total['paused'][$op->product_index])) {
                $indexes_total['paused'][$op->product_index] = 0;
            }

            if (!isset($indexes_total['deactivated'][$op->product_index])) {
                $indexes_total['deactivated'][$op->product_index] = 0;
            }

            if (!isset($indexes_total_by_region[$op->region]['active'][$op->product_index])) {
                $indexes_total_by_region[$op->region]['active'][$op->product_index] = 0;
            }

            if (!isset($indexes_total_by_region[$op->region]['paused'][$op->product_index])) {
                $indexes_total_by_region[$op->region]['paused'][$op->product_index] = 0;
            }

            if (!isset($indexes_total_by_region[$op->region]['deactivated'][$op->product_index])) {
                $indexes_total_by_region[$op->region]['deactivated'][$op->product_index] = 0;
            }

            if ($op->products_statuse_id == 5) {
                $indexes_total['active'][$op->product_index] += $op->total;
                $indexes_total_by_region[$op->region]['active'][$op->product_index] += $op->total;

                //$column_vals_active[$op->region][$op->product_index] += $op->total;
            } elseif ($op->products_statuse_id == 6) {
                $indexes_total['paused'] [$op->product_index]+= $op->total;
                $indexes_total_by_region[$op->region]['paused'] [$op->product_index] += $op->total;

                //$column_vals_pasued[$op->region][$op->product_index] += $op->total;
            } elseif ($op->products_statuse_id == 14) {
                $indexes_total['deactivated'][$op->product_index] += $op->total;
                $indexes_total_by_region[$op->region]['deactivated'][$op->product_index] += $op->total;

                //$column_vals_deactivated[$op->region][$op->product_index] += $op->total;
            }
        }

        if (isset($data['by_region']) && $data['by_region'] == "1") {
            $reg_index = 1;
            foreach ($indexes_total_by_region as $reg_name => &$reg_indexes) {
                foreach ($reg_indexes as $s_status => &$r_i) {

                    $missing_index = array_diff(array_keys($indexes_total['active']), array_keys($r_i));
                    // /var_dump($missing_index, '<br>');
                    foreach ($missing_index as $m_i) {
                        $r_i[$m_i] = 0;
                    }

                    ksort($r_i);

                    $status_color = $this->colors[$reg_index];
                    switch ($s_status) {
                        case 'active': $status_color = $this->changeRgbaOpacity($this->colors[$reg_index]); break;
                        case 'paused': $status_color = $this->changeRgbaOpacity($this->colors[$reg_index], 0.6); break;
                        case 'deactivated': $status_color = $this->changeRgbaOpacity($this->colors[$reg_index], 0.3); break;
                    }

                    $column_chart []= ['name' => $s_status, 'stack' => $reg_name, 'drilldown' => $reg_name, 'data' => array_values($r_i), 'color' => $status_color];

                }

                $reg_index++;
            }
        } else {
            foreach ($indexes_total as $s_status => &$r_i) {
                ksort($r_i);

                $status_color = $this->colors['default'];
                switch ($s_status) {
                    case 'active': $status_color = $this->changeRgbaOpacity($this->colors['default']); break;
                    case 'paused': $status_color = $this->changeRgbaOpacity($this->colors['default'], 0.6); break;
                    case 'deactivated': $status_color = $this->changeRgbaOpacity($this->colors['default'], 0.3); break;
                }

                $column_chart []= ['name' => $s_status, 'stack' => 'Services', 'drilldown' => 'Services', 'data' => array_values($r_i), 'color' => $status_color];
            }
        }

        $column_labels = [];
        ksort($indexes_total['active']);
        foreach ($indexes_total['active'] as $ind => $ind_val) {
            $product = Product::filterRegion(false)->select('id', 'region_id', 'ind', 'name')->where('ind', $ind)->first();
            if ($product) {
                $translated = $product->translated()->select('video_link', 'text', 'alt', 'seo_title', 'seo_keywords', 'seo_description', 'img', 'name', 'short_text')->first();
                if ($translated) {
                    $column_labels []= $translated->name . (isset($translated->short_text) ? ' (' . $translated->short_text . ')' : '');
                }
            }
        }

        // $pie_chart = ['type' => 'pie', 'name' => 'Services by region', 'data' => array_values($regions_val), 'center' => ['80%', '50px'], 'size' =>  100, 'showInLegend' => false];

        $values = $column_chart;
        //if (isset($data['by_region']) && $data['by_region'] == "1") $values []= $pie_chart;

        //area
        $options = ['values' => $values, 'labels' => $column_labels, 'misc' => ['orders_total_value' => 0]];


        return $options;

        $regions_val = [];
        $column_vals = [];
        $colum_data = [];

        foreach ($orders_products as $op) {
            $reg = Region::find($op->region_id);
            if ($reg) {
                if (!isset($regions_val[$reg->id])) {
                    $regions_val [$reg->id] = ['y' => $op->total, 'name' => $reg->name, 'color' => $this->colors[$reg->id]];
                } else {
                    $regions_val [$reg->id]['y'] += $op->total;
                }

                if (!isset($colum_data[$op->product_index])) {
                    $colum_data[$op->product_index] = [$reg->id => $op->total];
                } else {
                    $colum_data[$op->product_index][$reg->id] = $op->total;
                }
                
            }
        }

    }

    public function hex2rgba($color, $opacity = false) {
 
        $default = 'rgb(0,0,0)';
     
        //Return default if no color provided
        if(empty($color))
              return $default; 
     
        //Sanitize $color if "#" is provided 
            if ($color[0] == '#' ) {
                $color = substr( $color, 1 );
            }
     
            //Check if color has 6 or 3 characters and get values
            if (strlen($color) == 6) {
                    $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
            } elseif ( strlen( $color ) == 3 ) {
                    $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
            } else {
                    return $default;
            }
     
            //Convert hexadec to rgb
            $rgb =  array_map('hexdec', $hex);
     
            //Check if opacity is set(rgba or rgb)
            if($opacity){
                if(abs($opacity) > 1)
                    $opacity = 1.0;
                $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
            } else {
                $output = 'rgb('.implode(",",$rgb).')';
            }
     
            //Return rgb(a) color string
            return $output;
    }

    public function changeRgbaOpacity($color, $opacity = 1) {
        $split = explode(',', $color);
        $split[3] = $opacity . ')';
        $new_color = implode(',',  $split);

        return $new_color;
    }

    public function active_users_cumulative($data) {
        $users = User::filterRegion(false)->select('users.id', 'users.reg_date', 'users.region_id', 'users.active_service', DB::raw('count(*) as total'), DB::raw('YEAR(users.reg_date) year, MONTH(users.reg_date) month, WEEK(reg_date) week, DATE(reg_date) as e_date')/*, DB::raw('(
                  SELECT 
                     COUNT(id)
                  FROM users u
                  WHERE DATE(u.reg_date) <= e_date
               ) as total_interactions_per_month')*/)
        ->where('users.deleted_at', null)
        ->whereIn('active_service', [1, 2, 3, 4, 5] )
        ->groupBy(['month', 'year'])
        ->orderBy('reg_date', 'ASC')
        ->get();

        $values = $users->pluck('total')->toArray();
        $labels = $users->pluck('reg_date')->toArray();

        return $options = ['values' => ['name'  => 'Users', 'color' => '#43acff', 'data' => $values, 'labels' => $labels], 'renderTo' => 'active_users_inline', 'misc' => ['users_count' => array_sum ( $values )]];
    }

    public function orders_cumulative($data) {
        $orders = Order::filterRegion(false)->select('orders.id', 'orders.created_at', 'orders.region_id', DB::raw('count(*) as total'), DB::raw('YEAR(orders.created_at) year, MONTH(orders.created_at) month, WEEK(created_at) week, DATE(created_at) as e_date')/*, DB::raw('(
                  SELECT 
                     COUNT(id)
                  FROM orders u
                  WHERE DATE(u.created_at) <= e_date
               ) as total_interactions_per_month')*/)
        ->where('orders.deleted_at', null)
        ->where('orders_status_id', '!=',  3)
        ->whereIn('pay_type',  [0, 1, 2, 3, 10])
        ->groupBy(['month', 'year'])
        ->orderBy('created_at', 'ASC')
        ->get();

        $values = $orders->pluck('total')->toArray();
        $labels = $orders->pluck('created_at')->toArray();

        return $options = ['values' => ['name'  => 'Orders', 'color' => '#a8de69', 'data' => $values, 'labels' => $labels], 'renderTo' => 'orders_inline', 'misc' => ['orders_count' => array_sum ( $values )]];
    }

    public function stbs_cumulative($data) {

        $stbs = OrderProduct::filterRegion(false)
        //->join(DB::raw('tvoyo_billing.multiroom_control AS m_c'), 'm_c.stb_id', '=', 'orders_products.id')
        ->join(DB::raw('tvoyo_administration.regions AS regions'), 'regions.id', '=', 'orders_products.region_id')
        ->join(DB::raw('tvoyo_billing.products AS products'), 'products.id', '=', 'orders_products.product_id')
        ->select('orders_products.id', 'orders_products.date', 'orders_products.is_p', 'products.is_p', DB::raw('count(*) as total'), DB::raw('YEAR(orders_products.date) year, MONTH(orders_products.date) month, WEEK(orders_products.date) week, DATE(orders_products.date) as e_date')/*, DB::raw('(
                  SELECT 
                     COUNT(id)
                  FROM orders_products u
                  WHERE DATE(u.date) <= e_date
               ) as total_interactions_per_month')*/)
        ->where('products.is_p', 1)
        ->where('products.for_mobile', 0)
        ->groupBy(['month', 'year'])
        ->orderBy('date', 'ASC')
        ->get();

        $values = $stbs->pluck('total')->toArray();
        $labels = $stbs->pluck('created_at')->toArray();

        return $options = ['values' => ['name'  => 'STBs', 'color' => '#ffd965', 'data' => $values, 'labels' => $labels], 'renderTo' => 'stbs_inline', 'misc' => ['stbs_count' => array_sum ( $values )]];
    }

}