<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Order\Order;
use App\Models\BillingLog;
use App\Models\Settings\Settings;


class CartuTransaction extends Model  {

    use SoftDeletes;
    protected $default_region_id = 1;
    protected $region = null;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->region = session('region_id') ? session('region_id') : $this->default_region_id;
        $this->connection = config('database.region.'.$this->region.'.database');
    }
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cartu_transactions';
    protected $dates = ['deleted_at'];
    protected $fillable = ['id', 'region_id', 'm_msg_type', 'merchant_id', 'm_trans_id', 'm_amt', 'm_currency', 'm_desc', 'm_leng', 'm_ip', 'b_trans_id', 'result', 'result_code', 'rrn', 'approval_code', 'card_number', 'user_id', 'date', 'created_at', 'updated_at', 'deleted_at'];
    /**
     * The timestamps.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */

    //scopes

    public function scopeFilterRegion($query, $flag = true) {
        $region = $this->region;
        if ($flag) {
            return $query->where('region_id', $region);
        }
        return $query;
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\Tvoyo\User', 'user_id');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction', 'id', 'trans_id');
    }

    public static function update_transaction($data) {
        if (isset($data['id']) && isset($data['type']) && isset($data['count']) && isset($data['order_id']) && isset($data['domain'])) {
            $url_prefix = 'http://'.$data['domain'].'/ru/remote/changetransaction';

            $query = http_build_query($data);

            $crl = curl_init();
            curl_setopt($crl, CURLOPT_URL, $url_prefix . '?' . $query);
            //curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 5);
            //curl_setopt($crl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
            curl_setopt($crl, CURLOPT_AUTOREFERER, true); 
            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($crl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($crl, CURLOPT_VERBOSE, true);
            //curl_setopt($crl, CURLOPT_HEADER, false); 
//             curl_setopt($crl, CURLOPT_POST, true);
//             curl_setopt($crl, CURLOPT_HTTPHEADER, array(
// 'Content-Type: application/x-www-form-urlencoded'));
//             curl_setopt($crl, CURLOPT_POSTFIELDS, $query);

            $ret = curl_exec($crl);
            curl_close($crl);

            $result = null;
            if ($ret) {
                $result = json_decode($ret, true);
            }

            return $result;
        }
    }

    public static function update_transactions_automatic() {
        $days_before_confirmation_settings = Settings::filterRegion(false)->find(3)->translated()->first();

        if ($days_before_confirmation_settings) {
            $days_before_confirmation = $days_before_confirmation_settings->value;
        } else {
            $days_before_confirmation = 5;
        }

        $current_time = Carbon::now()->subDays($days_before_confirmation);

        $orders_queue = Order::filterRegion()
            ->where('transop1', 0)
            ->where('transop2', 0)
            ->where('transop3', 0)
            ->whereIn("orders_status_id", [2, 6])
            ->where(function ($query) use($current_time) {
                $query->where(function ($query) use($current_time) {
                    $query->where('first_order', 1)
                    ->whereDate("date", "=", $current_time->toDateString());
                })->orWhere(function ($query) {
                    $query->whereHas('user', function ($query) {
                        $query->whereHas('orders_products', function ($query) {}, '>', 1 );
                    })->where('first_order', null);
                });
            })
            ->get();

        $result_arr = [];

        foreach ($orders_queue as $order) {
            if ($order->pay_type == 1 || $order->pay_type == 0) {
                $cartu_transaction = CartuTransaction::filterRegion()->where("m_desc", $order->orderid)->where("result", "Y")->first();

                if($cartu_transaction != null && isset($cartu_transaction->b_trans_id)) {
                    $trans_sum = round($order->get_order_price($order->id) - $order->pay_from_score, 2)*100;
                    if($order->transop_date == '0000-00-00 00:00:00') {
                        $transop_date=$order->date;
                    } else {
                        $transop_date=$order->transop_date;
                    }

                    $transaction_data = ['type' => 1, 'id' => $cartu_transaction->b_trans_id, 'count' => $trans_sum, 'order_id' =>  $order->id, 'domain' => '91.237.51.22'/*config('database.region.'.$order->region_id.'.domain')*/];

                    $result_arr[$order->id] = CartuTransaction::update_transaction($transaction_data);
                }                            
            }
        }

        BillingLog::billing_log_update(['text' => json_encode($result_arr, true), 'type' => 'cartu_transaction_update']);

        //DB::insert('insert into test1 (text) values (?)', ['[CARTU TRANSACTIONS UPDATE]----'.json_encode($result_arr, true)]);

        return $result_arr;
    }
}