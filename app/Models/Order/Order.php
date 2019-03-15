<?php 
namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use Illuminate\Http\Request;

class Order extends Model  {

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
	protected $table = 'orders';
	protected $dates = ['deleted_at'];

    protected $fillable = ['id', 'region_id', 'orders_status_id', 'payment_method_id', 'region_id', 'user_id', 'prolong_id', 'prolong2_id', 'date', 'name', 'email', 'adress', 'phone', 'city', 'user_comment', 'admin_comment', 'code', 'used_bonus', 'price', 'method', 'street', 'postcode', 'named', 'phoned', 'text', 'dic', 'ic', 'firm', 'fname', 'ffirm', 'fstreet', 'fcity', 'fpostcode', 'surname', 'gender', 'bday', 'bmonth', 'byear', 'flat', 'region', 'country', 'mobile', 'sume1', 'owner', 'ups', 'ups_code', 'ups_key', 'ups_img', 'country_code', 'weight', 'ups_price', 'stsid', 'tracking', 'payed', 'orderid', 'dealer_user', 'is_hide', 'dealer_score', 'pay_type', 'pay_from_score', 'transop1', 'transop2', 'transop3', 'transop_date', 'promocode', 'promoskidka', 'promostr', 'promopercent', 'promoinfo', 'globtotal', 'sum_x', 'sum_y', 'buy_year', 'buy_any', 'buy_half_year', 'buy_year_monthly', 'created_at', 'updated_at', 'deleted_at'];

    //scopes

    public function scopeFilterRegion($query, $flag = true) {
        $region = $this->region;
        if ($flag) {
            return $query->where('region_id', $region);
        }
        return $query;
    }
    

	/**
	 * The timestamps.
	 *
	 * @var bool
	 */
	public $timestamps = true;

	/**
	 * One to Many relation
	 *
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	/*public function language() 
	{
		return $this->belongsToMany('App\Language', 'languages_products');
	}*/

	/*public function menu_item() 
	{
		return $this->belongsToMany('App\ProductMenu', 'eshop_menus_products', 'product_id', 'eshop_menu_id');
	}*/

	public function product()
    {
        return $this->belongsTo('App\Models\Product\Product', 'product_id');
    }

    public function orders_products()
    {
        return $this->hasMany('App\Models\Order\OrderProduct', 'order_id');
    }

    public function promos_used()
    {
        return $this->hasMany('App\Models\Promo\PromoUsed', 'order_id');
    }

    public function dealer_stats()
    {
        return $this->hasMany('App\Models\Dealer\DealerStat', 'order_id');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'order_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Order\OrderStatus', 'orders_status_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Tvoyo\User', 'user_id');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public static function delete_with_img($id) {
        $region = session('region_id') ? session('region_id') : 1;
    	$product = Product::where('region_id', $region)->find($id);
    	$path = 'img/products/'.trim($product->img);
    	if(file_exists($path)) {
            unlink($path);
        }
        $product->delete();
        return true;
    }

    public function generate_code() {
        return date("Y") . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function generate_code_op() {

        if ($this->product->is_service == 1) {
            $this->code = "S" . str_pad($this->id, 8, '0', STR_PAD_LEFT);
        } else {
            $this->code = "P" . str_pad($this->id, 8, '0', STR_PAD_LEFT);
        }
        $this->save();
    }

    public function get_status($id) {
        $order = $this->find($id);
        return $this->status()->name;
    }

    public function get_order_price() {

        $cart_items = [];
        $index = 0;
        $price = 0;
        
        foreach ($this->orders_products()->get() as $o_products) {
            $price = $price + ($o_products->price*$o_products->quantity);
            $index++;
        }

        if($this->promoskidka) {
            $price = $price - $this->promoskidka;
            $price = round($price, 2);
        }
        
        if ($this->ups_price)
            return $price + $this->ups_price;
        else
            return $price;
    }

    public function check_order_before_remove() {

        $status = true;
        $result = ['status' => $status, 'message' => null];

        $orders_products = $this->orders_products()->get();
        foreach ($orders_products as $o_p) {
            $product = $o_p->product;
            if ($product->is_service && !$product->for_mobile) {
                $res = $o_p->check_service_before_remove();
                if (!$res['status']) {
                    $result['services'][$o_p->code] = $res['message'];
                    $result['status']= false;
                }
            } elseif ($product->is_service && $product->for_mobile) {
                $res = $o_p->check_mobile_service_before_remove();
                if (!$res['status']) {
                    $result['mobile_services'][$o_p->code] = $res['message'];
                    $result['status']= false;
                }
            } elseif ($product->is_p && !$product->is_goods) {
                $res = $o_p->check_stb_before_remove();
                if (!$res['status']) {
                    $result['stbs'][$o_p->code] = $res['message'];
                    $result['status']= false;
                }
            }
        }

        if (isset($result['services']) && count($result['services'])) {
            $result['message'] .= '<h5>Services</h5><ul class="error_log-alert-1">' . implode('', $result['services']) . '</ul>';
            unset($result['services']);
        }

        if (isset($result['stbs']) &&  count($result['stbs'])) {
            $result['message'] .= '<h5>STBs</h5><ul class="error_log-alert-1">' . implode('', $result['stbs']) . '</ul>';
            unset($result['stbs']);
        }

        if (isset($result['mobile_services']) && count($result['mobile_services'])) {
            $result['message'] .= '<h5>Mobile services</h5><ul class="error_log-alert-1">' . implode('', $result['mobile_services']) . '</ul>';
            unset($result['mobile_services']);
        }

        if (isset($result['goods']) &&  count($result['goods'])) {
            $result['message'] .= 'Goods</h5><ul class="error_log-alert-1">' . implode('', $result['goods']) . '</ul>';
            unset($result['goods']);
        }

        return $result;
    }

    public function manage_order_before_remove() {

        $message = [];

        $message []= '<h5>Associated Data</h5><ul class="message_log-alert-1">';

        $orders_products = $this->orders_products()->get();

        if ($orders_products->count()) {

            $o_p_tags = '';

            foreach ($orders_products as $o_p) {
                $o_p->get_stb_service()->detach();
                $o_p->get_service_stbs()->detach();
                $o_p->logs()->delete();

                $o_p_tags .= '<a class="dialog-inline-list-1" target="_blank" href="'.route('users-show', $o_p->user->id).'"><b>'.$o_p->code.'</b></a>';

                $o_p->delete();
            }

            $message []= '<li>Order products deleted ('.$o_p_tags.')</li>';
        }
       
        //Delete related dealer stats
        $dealer_stats = $this->dealer_stats()->get();

        if ($dealer_stats->count()) {

            $dealer_stats_tags = '';

            foreach ($dealer_stats as $d_s) {
                $dealer_user = $d_s->user()->first();
                if ($dealer_user) {
                    if ($d_s->type == 1) {
                        $added_score = $d_s->summ;
                        $dealer_user->score = $dealer_user->score - $added_score;
                    } elseif ($d_s->type == 2) {
                        $added_score = $d_s->summ;
                        $dealer_user->score = $dealer_user->score + $added_score;
                    } elseif ($d_s->type == 3) {
                        $added_score = $d_s->summ;
                        $dealer_user->score = $dealer_user->score - $added_score;
                    }

                    $dealer_stats_tags .= '<a class="dialog-inline-list-1" target="_blank" href="'.route('users-show', $dealer_user->id).'"><b>'.$dealer_user->code.'</b></a>';

                    $dealer_user->save();
                }
                
                $d_s->delete();
            }

            $message []= '<li>Order related dealer statistics updated ('.$dealer_stats_tags.')</li>';
        }

        $message []= '</ul>';

        $message = implode('', $message);

        return ['status' => true, 'message' => $message];
    }

    public function deleteHelper() {

        $orders = [];
        $errors = [];
        $messages = [];
        $orders ['local']= $this;
        
        foreach ($orders as $k => $order) {
            if ($order) {

                $res = $order->check_order_before_remove();
                if ($res['status']) {
                    $res = $order->manage_order_before_remove();

                    if ($res['status']) {
                        $messages [$order->code]= 'Order <b>'.$order->code.'</b> deleted' . $res['message'];

                        $order->delete();
                    }
                } else $errors [$order->code]= $res['message'];
            }
        }

        $status = (count($errors) > 0 ? false : true);

        $error_str = '';
        foreach ($errors as $reg => $error) {
            $error_str .= '<div class="error_holder-dialog">'.$error.'</div>';
        }

        $messsage_str = '';
        foreach ($messages as $reg => $message) {
            $messsage_str .= '<div class="success_holder-dialog">'.$message.'</div>';
        }

        return ['status' => $status, 'errors' => $error_str, 'messages' => $messsage_str];
    }

    public function saveHelper($data) {
        $region = session('region_id') ? session('region_id') : 1;
        $connection = config('database.region.'.$region.'.database');
        $orders = [];
        $errors = [];
        $status = true;
        $orders ['local']= $this;
        if (count($data)) {
            /*if ($this->id) {
                $orders ['remote']= Order::where('region_id', $region)->find($this->id);
            } else {
                $object = new Order;
                $object->setConnection($connection);
                $orders ['remote'] = $object;
            }*/

            $id = ($this->id ? $this->id : null);

            foreach ($orders as $connection => $order) {
                if ($order) {
                    foreach ($order->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $order->$attr = $data[$attr];
                        }
                    }

                    if ($id) {
                        $order->id = $id;
                    }

                    if(!$order->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; break; 
                    } else {
                        $id = $order->id;
                    }
                } else { $errors [$connection]= 'something went wrong (could not save item on '.$connection.')'; $status = false; }
            }
        } else {
            $errors []= 'data not provided';
            $status = false;
        }

        $error_str = '';
        foreach ($errors as $reg => $error) {
            $error_str .= '<div class="error_holder-dialog">'.$error.'</div>';
        }

        return ['status' => $status, 'errors' => $error_str];
    }

    public function get_sticker_img() {


        $xmlRequest1 = '<?xml version="1.0" encoding="ISO-8859-1"?>
    <AccessRequest>
        <AccessLicenseNumber>8CDD80E0DEFCB8D6</AccessLicenseNumber>
        <UserId>E96R02_api</UserId>
        <Password>!UPSapi1234</Password>
    </AccessRequest>
    <?xml version="1.0" encoding="ISO-8859-1"?>
    <ShipmentAcceptRequest>
        <Request>
            <TransactionReference>
                <CustomerContext>Customer Comment</CustomerContext>
            </TransactionReference>
            <RequestAction>ShipAccept</RequestAction>
            <RequestOption>1</RequestOption>
        </Request>
        <ShipmentDigest>' . $this->ups_key . '</ShipmentDigest>
    </ShipmentAcceptRequest>
    ';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onlinetools.ups.com/ups.app/xml/ShipAccept");
// uncomment the next line if you get curl error 60: error setting certificate verify locations
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
// uncommenting the next line is most likely not necessary in case of error 60
// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);

//if ($this->logfile) {
//   error_log("UPS REQUEST: " . $xmlRequest . "\n", 3, $this->logfile);
//}
        $xmlResponse = curl_exec($ch); // SHIP ACCEPT RESPONSE
//echo curl_errno($ch);

        $xml = $xmlResponse;

        preg_match_all("/\<ShipmentAcceptResponse\>(.*?)\<\/ShipmentAcceptResponse\>/s", $xml, $bookblocks);

        foreach ($bookblocks[1] as $block) {
            preg_match_all("/\<GraphicImage\>(.*?)\<\/GraphicImage\>/", $block, $author); // GET LABEL

            preg_match_all("/\<TrackingNumber\>(.*?)\<\/TrackingNumber\>/", $block, $tracking); // GET TRACKING NUMBER
//echo( $author[1][0]."\n" );
        }
        //echo( $tracking[1][0]."\n" );
        $this->tracking = $tracking[1][0];
        $this->save();
        return $author[1][0];
    }

    public function get_sticker_code() {
        $type = explode("-", $this->ups);
        $xmlRequest1 = '<?xml version="1.0"?>
        <AccessRequest xml:lang="en-US">
            <AccessLicenseNumber>8CDD80E0DEFCB8D6</AccessLicenseNumber>
            <UserId>E96R02_api</UserId>
            <Password>!UPSapi1234</Password>
        </AccessRequest>
        <?xml version="1.0"?>
        <ShipmentConfirmRequest xml:lang="en-US">
            <Request>
                <TransactionReference>
                    <CustomerContext>Customer Comment</CustomerContext>
                    <XpciVersion/>
                </TransactionReference>
                <RequestAction>ShipConfirm</RequestAction>
                <RequestOption>validate</RequestOption>
            </Request>
            <LabelSpecification>
                <LabelPrintMethod>
                    <Code>GIF</Code>
                    <Description>gif file</Description>
                </LabelPrintMethod>
                <HTTPUserAgent>Mozilla/4.5</HTTPUserAgent>
                <LabelImageFormat>
                    <Code>GIF</Code>
                    <Description>gif</Description>
                </LabelImageFormat>
            </LabelSpecification>
            <Shipment>
            <ReferenceNumber>
                        <Code>28</Code>
                        <Value>' . $this->code . '</Value>
                    </ReferenceNumber>
                <RateInformation>
                    <NegotiatedRatesIndicator/>
                </RateInformation>
                <Description>TVOYO TV order number:' . $this->id . '</Description>

                <Shipper>
                    <Name>J.S.tel s.r.o.</Name>
                    <PhoneNumber>+420212342222</PhoneNumber>
                    <ShipperNumber>E96R02</ShipperNumber>
                    <TaxIdentificationNumber>1234567890</TaxIdentificationNumber>
                    <AttentionName>JSTEL</AttentionName>

                    <Address>
                        <AddressLine1>Hybernska 1009/24</AddressLine1>
                        <City>Praha</City>
                        <StateProvinceCode>1</StateProvinceCode>
                        <PostalCode>11000</PostalCode>
                        <PostcodeExtendedLow></PostcodeExtendedLow>
                        <CountryCode>CZ</CountryCode>
                    </Address>
                </Shipper>
                <ShipTo>
                    <CompanyName>' . $this->name . '</CompanyName>
                    <AttentionName>' . $this->name . '</AttentionName>
                    <PhoneNumber>' . $this->phone . '</PhoneNumber>
                     <Description>TVOYO TV order number:' . $this->id . '</Description>
                    <Address>
                        <AddressLine1>' . $this->flat . '</AddressLine1>
                        <City>' . $this->city . '</City>
                        <StateProvinceCode>' . $this->country_code . '</StateProvinceCode>
                        <PostalCode>' . $this->postcode . '</PostalCode>
                        <CountryCode>' . $this->country_code . '</CountryCode>
                    </Address>
                </ShipTo>
                <ShipFrom>
                    <CompanyName>jstel</CompanyName>
                    <AttentionName>jstel</AttentionName>
                    <PhoneNumber>00420212343351</PhoneNumber>
                    <TaxIdentificationNumber>1234567877</TaxIdentificationNumber>
                    <Address>
                        <AddressLine1>Hybernska 1009/24</AddressLine1>
                        <City>Praha</City>
                        <StateProvinceCode>CZ</StateProvinceCode>
                        <PostalCode>11000</PostalCode>
                        <CountryCode>CZ</CountryCode>
                    </Address>
                </ShipFrom>
                <PaymentInformation>
                    <Prepaid>
                        <BillShipper>
                            <AccountNumber>E96R02</AccountNumber>
                        </BillShipper>
                    </Prepaid>
                </PaymentInformation>
                <Service>
                    <Code>' . trim($type[0]) . '</Code>
                    <Description>' . trim($type[1]) . '</Description>
                </Service>
                <Package>
                    <PackagingType>
                        <Code>02</Code>
                        <Description>Customer Supplied</Description>
                    </PackagingType>
                    <Description>TVOYO TV order number:' . $this->id . '</Description>

                    <PackageWeight>
                        <UnitOfMeasurement/>
                        <Weight>' . $this->weight . '</Weight>
                    </PackageWeight>
                </Package>
            </Shipment>
        </ShipmentConfirmRequest>
        ';

        /* <ReferenceNumber>
          <Code>00</Code>
          <Value>Package</Value>
          </ReferenceNumber> */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onlinetools.ups.com/ups.app/xml/ShipConfirm");
        // uncomment the next line if you get curl error 60: error setting certificate verify locations
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // uncommenting the next line is most likely not necessary in case of error 60
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3600);

        //if ($this->logfile) {
        //   error_log("UPS REQUEST: " . $xmlRequest . "\n", 3, $this->logfile);
        //}
        $xmlResponse = curl_exec($ch); // SHIP CONFORMATION RESPONSE
        //print_r($xmlResponse);
        //echo curl_errno($ch);

        $xml = $xmlResponse;
        // echo $xml;
        preg_match_all("/\<ShipmentConfirmResponse\>(.*?)\<\/ShipmentConfirmResponse\>/s", $xml, $bookblocks);
        //print_r($bookblocks);
        foreach ($bookblocks[1] as $block) {
            preg_match_all("/\<ShipmentDigest\>(.*?)\<\/ShipmentDigest\>/", $block, $author); // SHIPPING DIGEST
        //echo( $author[1][0]."\n" );
        }
        return $cd = $author[1][0];
    }

}