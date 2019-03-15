<?php 
namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use Carbon\Carbon;
use Curl;
use Mail;
use App\Models\Tvoyo\User;
use App\Models\BillingLog;
use App\Models\ServiceAccount;
use App\Models\Product\ProductStatus;
use App\Models\Product\ProductStatusLog;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderProduct extends Model  {

	use SoftDeletes;
    protected $default_region_id = 1;
    protected $region = null;
    protected $prefix = null;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->region = session('region_id') ? session('region_id') : $this->default_region_id;
        $this->connection = config('database.region.'.$this->region.'.database');
        $this->prefix = config('database.region.'.$this->region.'.prefix');
    }
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'orders_products';
	protected $dates = ['deleted_at'];

    protected $fillable = ['id', 'region_id', 'product_id', 'order_id', 'prolong_id', 'prolong2_id', 'prolong3_id', 'prolong3_num', 'quantity', 'price', 'bonus', 'color_code', 'color_name', 'color_price', 'color_id', 'products_statuse_id', 'mac', 'date_start', 'date_end', 'date_stop', 'date_month_end', 'stoped', 'merchant_transaction_id', 'code', 'name', 'p_name', 'comment', 'is_service', 'is_p', 'user_id', 'date', 'yearly', 'pay_date', 'iteration', 'button', 'owner', 'acted', 'stsid1', 's_code', 'region', 'country', 'mobile', 'sume1', 'owner', 'order_stb', 'service_password', 'multir_ip', 'multir_ind', 'multir_macs', 'active_service_id', 'month_count', 'prev_multir_service_id', 'mob_account_id', 'pin_code', 'promo_discount', 'created_at', 'updated_at', 'deleted_at'];

    //scopes

    public function scopeFilterRegion($query, $flag = true) {
        $region = $this->region;
        if ($flag) {
            return $query->where('region_id', $region);
        }
        return $query;
    }

    public function scopeFilterStatus($query, $status = null){
        if ($status) {
            return $query->whereHas('logs', function ($q) use($status) {
                 $q->latest()->where('products_statuse_id', $status);
            });
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

	public function order()
    {
        return $this->belongsTo('App\Models\Order\Order', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product\Product', 'product_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Tvoyo\User', 'user_id');
    }

    public function status()
    {
        return $this->belongsToMany('App\Models\Product\ProductStatus', 'orders_products_statuses', 'orders_product_id', 'products_statuse_id');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\Product\ProductStatusLog', 'orders_product_id');
    }

    public function latestLog(){
        return $this->hasOne('App\Models\Product\ProductStatusLog', 'orders_product_id')->latest();
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message', 'orders_product_id');
    }

    /*public function orders_products_status()
    {
        return $this->hasMany('App\OrderProductStatus', 'orders_product_id');
    }*/

    public function get_stb_service(){
        return $this->belongsToMany('App\Models\Order\OrderProduct', 'multiroom_control', 'stb_id', 'service_id')->withPivot('stb_id', 'service_id', 'status', 'created_at');
    }

    public function get_service_stbs(){
        return $this->belongsToMany('App\Models\Order\OrderProduct', 'multiroom_control', 'service_id', 'stb_id')->withPivot('stb_id', 'service_id', 'status', 'created_at');
    }
    
    public function generate_service_code($id, $region_id = null) {
        $prefix = $this->prefix['service'];
        if ($region_id) {
            $prefix  = config('database.region.'.$region_id.'.prefix')['service'];
        } 
        return $prefix  . str_pad($id, 8, '0', STR_PAD_LEFT);
    }

    public function generate_stb_code($id, $region_id = null) {
        $prefix = $this->prefix['product'];
        if ($region_id) {
            $prefix  = config('database.region.'.$region_id.'.prefix')['product'];
        } 
        return $prefix  . str_pad($id, 8, '0', STR_PAD_LEFT);
    }

    public function generate_goods_code($id, $region_id = null) {
        $prefix = $this->prefix['product'];
        if ($region_id) {
            $prefix  = config('database.region.'.$region_id.'.prefix')['product'];
        } 
        return $prefix . str_pad($id, 8, '0', STR_PAD_LEFT);
    }

    public function generate_mob_account_code($id, $region_id = null) {
        $prefix = $this->prefix['mob_account'];
        if ($region_id) {
            $prefix  = config('database.region.'.$region_id.'.prefix')['mob_account'];
        } 
        return $prefix + $id;
    }

    public function generate_code($region_id = null) {
        $prefix_key = null;
        if ($this->is_p == 1) {
            $prefix_key = 'product';
        } elseif ($this->is_service == 1) {
            $prefix_key = 'service';
        }

        if ($prefix_key) {
            $prefix = $this->prefix[$prefix_key];
            if ($region_id) {
                $prefix  = config('database.region.'.$region_id.'.prefix')[$prefix_key];
            } 
            return $prefix . str_pad($this->id, 8, '0', STR_PAD_LEFT);
        }

        return null;
    }

    public static function generate_code_static($op) {
        $prefix_key = null;
        if ($op->is_p == 1) {
            $prefix_key = 'product';
        } elseif ($op->is_service == 1) {
            $prefix_key = 'service';
        }

        if ($prefix_key) {
            return config('database.region.'.$op->region_id.'.prefix')[$prefix_key] . str_pad($op->id, 8, '0', STR_PAD_LEFT);
        }

        return null;
    }
    

    public function last_status() {
        $st = $this
                ->logs()
                //->where("products_statuse_id","!=",8)
                ->orderBy("id", "asc")
                ->first();

        if ($st) {
            return $st->product_status()->first();
        }

        return "";
    }

    public function service_statuses() {
        $region = session('region_id') ? session('region_id') : 1;
        $statuses ['all']= ProductStatus::where(function($query) use($region) {
            $query->where('region_id', $region)
            ->orWhere('region_id', null);
        })->where('is_service', 1)->get();
        $statuses ['selected']= $this->status()->where('is_service', 1)->pluck('products_statuses.id')->toArray();

        return $statuses;
    }

    public function get_macs($uid) {
        $region = session('region_id') ? session('region_id') : 1;
        $macs = $this
                ->where('region_id', $region)
                ->where("user_id", $uid)
                ->where("is_p", 1)
                ->get();
        $macs_array = [];
        foreach ($macs as $one) {
            if (!in_array($one->mac, $macs_array, true)) {
                array_push($macs_array, $one->mac);
            }
        }
        return $macs_array;
    }

    public function get_aps($region_id = null) {
        $region = session('region_id') ? session('region_id') : 1;
        if ($region_id) {
            $region = $region_id;
        }
        $uid = $this->user_id;
        $mac = $this->mac;

        if (!trim($mac))
            return [];

        $as = null;

        if($this->multir_ind == 1) {
            $as = OrderProduct::where('region_id', $region)->where("user_id", "=", $uid)
                ->where("is_service", "=", 1)
                ->get();

            foreach ($as as $service) {
                if($service->multir_ind == 1) {
                    $macs_arr = json_decode($service->multir_macs, true);

                    if (isset($macs_arr[$this->id]) && $macs_arr[$this->id] == $mac) {
                        //var_dump($service->code);
                        return [$service];
                    }
                }
            }

            return null;

        } else {
            $as = OrderProduct::where('region_id', $region)->where("user_id", "=", $uid)
                ->where("is_service", "=", 1)
                ->where("mac", "=", $mac)
                ->get();
        }
        
        return $as;
    }

    public function get_active_service ($region_id = null) {
        $region = session('region_id') ? session('region_id') : 1;
        if ($region_id) {
            $region = $region_id;
        }
        $connection = $this->getConnectionName();
        $active_service = null;
        if ($this->active_service_id) {
            $active_service = OrderProduct::where('region_id', $region)->on($connection)->find($this->active_service_id);
        }
        return $active_service;
    }

    public function get_attached_stbs () {
        $region = session('region_id') ? session('region_id') : 1;
        $connection = $this->getConnectionName();
        return OrderProduct::where('region_id', $region)->where('is_p', 1)->where('active_service_id', $this->id)->get();
    }

    public function manage_statuses($process_arr, \App\User $curr_user = null) {
        //dd($process_arr);
        foreach ($process_arr as $action => $package_ids) {
            foreach ($package_ids as $package_id) {
                $rel = $this->status()->find($package_id);
                $log = new ProductStatusLog;
                $log->orders_product_id = $this->id;
                $log->products_statuse_id = 12;
                $tx = null;
                if ($action == 'activate') {
                    if (!$rel) {
                        $this->status()->attach($package_id);
                        $tx = "Пакет " . ProductStatus::find($package_id)->name . " добавлен (Service ON)";
                    }
                } elseif ($action == 'deactivate') {
                    if ($rel) {
                        $this->status()->detach($package_id);
                        $tx = "Пакет " . $rel->name . " удален (Service OFF)";
                    }
                }
                $log->comment = $tx;

                if ($curr_user) {
                    $log->owner = $curr_user->username;
                }
                
                $log->save();
            }
        }

        if ($this->status()->count() > 0) {
            if (!$this->date_start)
                $this->date_start = time();
            if ($this->date_end && $this->date_stop > 0) {
                $this->date_end = time() + $this->date_end - $this->date_stop;
                $this->date_start = time();
            } else {
                $this->date_end = time() + $this->product->days * 24 * 60 * 60;
                if($this->product->yearly == 1) $this->date_month_end = strtotime(date('Y-m-d', time()).' +'.$this->product->per_month_count.' month');
            }

            $this->stoped = 0;
            $this->acted = 1;
            
        } else {
            if ($this->date_end > time()) {
                $this->date_stop = time();
                $this->stoped = 1;
            } else {
                $this->date_stop = time();
                $this->stoped = 1;
            }
        }

        $this->save();

        return $this;
    }

    public static function get_products($region_id = null) {
        $region = session('region_id') ? session('region_id') : 1;
        if ($region_id) {
            $region = $region_id;
        }
        $as = OrderProduct::where('region_id', $region)->where("is_p", "=", 1)
        	->whereHas('product', function ($query) {
          		$query->where('products.for_mobile', '=', 0)
          		->where('products.is_goods', '!=', 1);
		    })
		    ->where(function ($query) {
		    	$query->where('order_id', "=", 0)
		    	->orWhere(function ($query) {
			    	$query->whereHas('order', function ($query) {
				         $query->where('orders.orders_status_id', '!=', 3);
				    });  
			    });
            })->select();

        return $as;
    }

    public static function get_all_products($region_id = null) {
        $as = OrderProduct::where("is_p", "=", 1)
            ->whereHas('product', function ($query) {
                $query->where('products.for_mobile', '=', 0)
                ->where('products.is_goods', '!=', 1);
            })
            ->where(function ($query) {
                $query->where('order_id', "=", 0)
                ->orWhere(function ($query) {
                    $query->whereHas('order', function ($query) {
                         $query->where('orders.orders_status_id', '!=', 3);
                    });  
                });
            })->select();

        return $as;
    }

    public static function get_goods($region_id = null) {
        $region = session('region_id') ? session('region_id') : 1;
        if ($region_id) {
            $region = $region_id;
        }
        $as = OrderProduct::where('region_id', $region)->whereHas('product', function ($query) {
          		$query->where('products.is_goods', '=', 1);
		    })
		    ->where(function ($query) {
		    	$query->where('order_id', "=", 0)
		    	->orWhere(function ($query) {
			    	$query->whereHas('order', function ($query) {
				         $query->where('orders.orders_status_id', '!=', 3);
				    });  
			    });
            })->select();

        return $as;
    }

    public static function get_services($products_statuse_id = false, $region_id = null) {
        $region = session('region_id') ? session('region_id') : 1;
        if ($region_id) {
            $region = $region_id;
        }
    	if (!$products_statuse_id) {

    		$as = OrderProduct::where('region_id', $region)->where("is_service", "=", 1)->where('prolong_id', "=", 0)
	        	->whereHas('user', function ($query) {})
	        	->whereHas('product', function ($query) {
	          		$query->where('products.for_mobile', '=', 0)
	          		->where('products.is_goods', '!=', 1);
			    })
			    ->where(function ($query) {
                    $query->where('order_id', "=", 0)
			    	->orWhere(function ($query) {
				    	$query->whereHas('order', function ($query) {
					         $query->where('orders.orders_status_id', '!=', 3);
					    });  
				    });
	            })->select();

    	} else {

    		$as = OrderProduct::where('region_id', $region)->where("is_service", "=", 1)->where('prolong_id', "=", 0)
	        	->whereHas('user', function ($query) {})
	        	->whereHas('product', function ($query) {
	          		$query->where('products.for_mobile', '=', 0)
	          		->where('products.is_goods', '!=', 1);
			    })
                ->where(function ($query) {
                    $query->where('order_id', "=", 0)
                    ->orWhere(function ($query) {
                        $query->whereHas('order', function ($query) {
                             $query->where('orders.orders_status_id', '!=', 3);
                        });  
                    });
                })
	            ->havingRaw('(select products_statuses_logs.products_statuse_id from products_statuses_logs where orders_products.id=products_statuses_logs.orders_product_id order by products_statuses_logs.created_at desc limit 1) = '.$products_statuse_id)
	            ->select();
    	}
        

        return $as;
    }

    public static function get_all_services() {
        $as = OrderProduct::where("is_service", "=", 1)->where('prolong_id', "=", 0)
            ->whereHas('user', function ($query) {})
            ->whereHas('product', function ($query) {
                $query->where('products.for_mobile', '=', 0)
                ->where('products.is_goods', '!=', 1);
            })
            ->where(function ($query) {
                $query->where('order_id', "=", 0)
                ->orWhere(function ($query) {
                    $query->whereHas('order', function ($query) {
                         $query->where('orders.orders_status_id', '!=', 3);
                    });  
                });
            })->select();

        return $as;
    }

    public static function get_mobile_services($products_statuse_id = false, $region_id = null) {
        $region = session('region_id') ? session('region_id') : 1;
        if ($region_id) {
            $region = $region_id;
        }
        if (!$products_statuse_id) {

            $as = OrderProduct::where('region_id', $region)->where("is_service", "=", 1)
                ->whereHas('user', function ($query) {})
                ->whereHas('product', function ($query) {
                    $query->where('products.for_mobile', '=', 1)
                    ->where('products.is_goods', '!=', 1);
                })
                ->where(function ($query) {
                    $query->where('order_id', "=", 0)
                    ->orWhere(function ($query) {
                        $query->whereHas('order', function ($query) {
                             $query->where('orders.orders_status_id', '!=', 3);
                        });  
                    });
                })->select();

        } else {

            $as = OrderProduct::where('region_id', $region)->where("is_service", "=", 1)
                ->whereHas('user', function ($query) {})
                ->whereHas('product', function ($query) {
                    $query->where('products.for_mobile', '=', 1)
                    ->where('products.is_goods', '!=', 1);
                })
                ->havingRaw('(select products_statuses_logs.products_statuse_id from products_statuses_logs where orders_products.id=products_statuses_logs.orders_product_id order by products_statuses_logs.id desc limit 1) = '.$products_statuse_id)
                ->select();
        }
        

        return $as;
    }

    public static function timeout($region_id = null) {
        $current_time = Carbon::now()->addDays(10);

        $op = OrderProduct::where("is_service", "=", 1)
        ->where("stoped", "=", 0)
        //->where("user_id", "=", 304)
        ->where("date_end", "<=", $current_time->timestamp);

        if ($region_id) {
            $op->where('region_id', $region_id);
        }

        $op->whereHas('product', function ($query) {
      		$query->where('products.for_mobile', '=', 0)
            ->where('products.is_goods', '!=', 1);
      		//->where('products.trial_service', '=', 0);
	    })
	    ->whereHas('get_service_stbs', function ($query) {}, '>', 0);

        return $op->select();
    }

    public static function timeout_subscriptions($region_id = null) {
        $current_time = Carbon::now();

        $op = OrderProduct::where('is_service', 1)
        /*->where("stoped", "=", 0)*/
        ->where('date_month_end', '>', 0)
        ->where('date_month_end', '<=', $current_time->addDays(10)->timestamp)
        ->where(function ($query) {
            $query->where('products_statuse_id', 5)
            ->orWhere('products_statuse_id', 6);
        });

        /*if ($region_id) {
            $op->where('region_id', $region_id);
        }*/

        $op->whereHas('product', function ($query) {
            $query/*->where('products.for_mobile', '=', 0)*/
            ->where('products.yearly', 1)
            ->where('products.per_month_count', '>', 0)
            ->where('products.is_goods', '!=', 1);
            //->where('products.trial_service', '=', 0);
        })
        ->whereHas('get_service_stbs', function ($query) {}, '>', 0);

        return $op->select();
    }

    public static function timeout_mobile($region_id = null) {
        $current_time = Carbon::now();

        $op = OrderProduct::where("is_service", "=", 1)
            ->where("stoped", "=", 0)
            ->where("date_end", "<=", $current_time->timestamp);

        if ($region_id) {
            $op->where('region_id', $region_id);
        }

        $op->whereHas('product', function ($query) {
          		$query->where('products.for_mobile', '=', 1)
                ->where('products.is_goods', '!=', 1);
		});

        return $op->select();
    }

    public static function yearly($region_id = null) {
        $region = session('region_id') ? session('region_id') : 1;
        if ($region_id) {
            $region = $region_id;
        }
        return OrderProduct::where('region_id', $region)->where("is_service", "=", 1)
            ->where("stoped", 0)
            ->where("pay_date", "<=", strtotime("-1 month", time()))
            ->where("iteration", "<=", 12)
            ->where("iteration", ">", 0)
            ->where(function ($query) {
		    	$query->where('mac', '!=', NULL)
		    	->where('mac', '!=', '')
		    	->orWhere('multir_macs', '!=', null);
            })
            ->select();
    }

    public static function get_p_by_mac($mac) {
        $region = session('region_id') ? session('region_id') : 1;
        return OrderProduct::where('region_id', $region)->where("mac", $mac)
            ->where("is_p", 1)
            ->first();
    }

    public static function last_log_stat($region_id = null) {
        $region = session('region_id') ? session('region_id') : 1;
        if ($region_id) {
            $region = $region_id;
        }
        return OrderProduct::where('region_id', $region)->logs()->first()->product_status()->name;
    }

    /*public function delete($region_id = null) {
        $region = session('region_id') ? session('region_id') : 1;
        if ($region_id) {
            $region = $region_id;
        }
        $oid = $this->order_id;
        $a = parent::delete();
        $check = OrderProduct::where('region_id', $region)->where("order_id", $oid)->count();
        if ($check == 0) {
            $order = $this->order()->first();
            $order->delete();
        }
        return $a;
    }*/

    public function manage_stb_before_remove () {

        //CLEAN UP BEFORE DELETE

        $service = $this->get_stb_service()->first();
        if ($service) {
            $current_packages = $service->service_statuses()['selected'];
            $answer = [];

            foreach ($current_packages as $package_id) {
                $package = ProductStatus::find($package_id);
                if ($package) {
                    $query = ['action' => 'service_off', 'mac' => str_replace(':', '', $this->mac), 'snum' => $package->get];
                    $answer [$atts->mac]= OrderProduct::billing_update($query);
                }
            }

            $service->detach();
        }
        
        return ['status' => true, 'message' => null];
    }

    public function manage_service_before_remove () {

        //CLEAN UP BEFORE DELETE
        
        $attached_stbs = $this->get_service_stbs();
        $current_packages = $this->service_statuses()['selected'];
        $answer = [];

        foreach ($attached_stbs as $atts) {
            $atts->active_service_id = null;
            $atts->save();
            foreach ($current_packages as $package_id) {
                $package = ProductStatus::find($package_id);
                if ($package) {
                    $query = ['action' => 'service_off', 'mac' => str_replace(':', '', $atts->mac), 'snum' => $package->get];
                    $answer [$atts->mac]= OrderProduct::billing_update($query);
                }
            }
        }

        $attached_stbs->detach();
        return ['status' => true, 'message' => null];
    }

    public function check_product_before_remove() {

        $product_type = null;

        $product = $this->product;

        $result = ['status' => true, 'message' => null];

        if ($product->is_service == 1) {
            if ($product->for_mobile == 1) {
                $product_type = 'mobile_service';
            } elseif ($product->for_mobile == 0) {
                $product_type = 'service';
            }
        } elseif ($product->is_p == 1) {
            $product_type = 'stb';
        }

        if ($product->is_goods == 1) {
            $product_type = 'goods';
        }

        switch ($product_type) {
            case 'service': $result = $this->check_service_before_remove(); break;
            case 'mobile_service': $result = $this->check_mobile_service_before_remove(); break;
            case 'stb': $result = $this->check_stb_before_remove(); break;
            case 'goods': $result = $this->check_goods_before_remove(); break;
        }

        return $result;

    }

    public function check_service_before_remove() {

        $message = [];
        $status = true;

        $attached_stbs = $this->get_service_stbs()->get();

        if ($attached_stbs->count()) {
            $text = 'Service is active on ';
            foreach ($attached_stbs as $stb) {
                 $text .= '<a class="dialog-inline-list-1" target="_blank" href="'.route('users-show', $stb->user->id).'">STB <b>'.$stb->code.'</b> ('. $stb->mac .')</a>';
            }

            $message []= $text . ', Please deactivate service before removing';

            $status = false;
        }

        $message = '<li><b>'.$this->code.'</b>: ' . implode('; ', $message) . '</li>';

        return ['status' => $status, 'message' => $message];

    }

    public function check_mobile_service_before_remove() {

        $message = [];
        $status = true;

        if ($this->products_statuse_id == 5) {

            $message []= 'Please deactivate mobile service before removing';

            $status = false;
        }

        $message = '<li><b>'.$this->code.'</b>: ' . implode('; ', $message) . '</li>';

        return ['status' => $status, 'message' => $message];

    }

    public function check_stb_before_remove() {

        $message = [];
        $status = true;

        $activated_service = $this->get_stb_service()->first();

        if ($this->products_statuse_id == 1) {

            $message []= 'STB is activated, please deactivate before removing';

            $status = false;
        }

        if ($activated_service) {

            $message []= 'STB has active service <b>'.$activated_service->code.'</b>; Please detach service from STB before removing';

            $status = false;
        }

        $message = '<li><b>'.$this->code.'</b>: ' . implode('; ', $message) . '</li>';

        return ['status' => $status, 'message' => $message];

    }

    public function check_goods_before_remove() {

        $message = [];
        $status = true;

        // if ($this->products_statuse_id == 1) {

        //     $message []= 'STB is activated, please deactivate before removing';

        //     $status = false;
        // }

        $message = '<li><b>'.$this->code.'</b>: ' . implode('; ', $message) . '</li>';

        return ['status' => $status, 'message' => $message];

    }

    public function detach_service ($op, $active_service = null) {

        $region = session('region_id') ? session('region_id') : 1;
        $errors = [];

        if ($active_service) {
            $stb_ids_arr = explode(',', $active_service->order_stb);
            $stb_ids_str = '';

            if(($key1 = array_search($op->id, $stb_ids_arr)) !== false) {
                unset($stb_ids_arr[$key1]);               
            }

            $service_switch_off_result = true;
            if ($service_switch_off_result ) {
                $tmp_macs_arr = null;
                if ($active_service->multir_ind == 1) {
                    $tmp_macs_arr = json_decode($active_service->multir_macs, true);
                    if(($key1 = array_search($op->mac, $tmp_macs_arr)) !== false) {
                        unset($tmp_macs_arr[$key1]);
                    }
                    if (count($tmp_macs_arr) == 1) {
                        reset($tmp_macs_arr);
                        $active_service->mac = current($tmp_macs_arr);
                        $active_service->multir_macs = null;
                    } elseif (count($tmp_macs_arr) > 1) {
                        $active_service->multir_macs = json_encode($tmp_macs_arr);
                    }

                } elseif ($active_service->mac == $op->mac) {
                    $active_service->mac = null;
                }

                foreach ($stb_ids_arr as $stb_1) {
                    $stb_ids_str .= $stb_1 . ',';
                }

                if (count($stb_ids_arr) <= 1) {
                    $active_service->multir_ind = 0;
                    foreach ($stb_ids_arr as $stb_id_t) {
                        $tmp_stb = OrderProduct::where('region_id', $region)->find($stb_id_t);
                        if ($tmp_stb) {
                            $tmp_stb->multir_ind = 0;
                            $tmp_stb->active_service_id = $active_service->id;
                            //$tmp_stb->save();
                        }
                    }
                        
                    if (count($stb_ids_arr) == 0) {
                        $active_service->products_statuse_id = 6;
                    }
                } else {
                    $active_service->multir_ind = 1;
                    foreach ($stb_ids_arr as $stb_id1) {
                        $tmp_stb = OrderProduct::where('region_id', $region)->find($stb_id1);
                        if ($tmp_stb) {
                            $tmp_stb->multir_ind = 1;
                            $tmp_stb->active_service_id = $active_service->id;
                            //$tmp_stb->save();
                        }
                    }
                }
                $active_service->order_stb = rtrim($stb_ids_str, ',');

                var_dump($active_service->order_stb, $active_service->mac, $active_service->multir_macs, $active_service->multir_ind); die;
                //$active_service->save();
            } else $errors []= 'could not turn off service '.$active_service->code.' (MAC: '.$op->mac.')';


            
        }
        
        //DEACTIVATE STB ON GIVEN MAC
        $stb_deactivate_query = ProductStatus::where('region_id', $region)->find(3);
        $query = 'mac='.str_replace(':', '', $op->mac).$stb_deactivate_query->get;
        $result = self::billing_update($query);

        return $result;
    }

    //HELPERS
    public function get_data_end() {
        $ret = [];
        $ret["ok"] = null;
        $date_end = '';
        $ret["error"] = false;
        /*$ret["ok"] = $this->products_statuse_id;
        return $ret;*/
        if ((!$this->id || $this->get_service_stbs()->count() == 0) && $this->products_statuse_id == null) {
            $ret['result'] = false;
            $ret["error"] = true;
            $ret["ok"] = "Услуга не обработан";
        } else {
            if ($this->stoped == 1 && $this->date_end) {
                $dif = $this->date_end - $this->date_stop;
                $dif = $dif / 60 / 60 / 24;
                $ret['result'] = false;
                $ret['diff'] = $dif;
                $ret["ok"] = 'Услуга приостановлена ('.date('Y-m-d', $this->date_stop).')';
                $ret["date"] = date('Y-m-d', $this->date_end);
                $ret["date_month"] = date('Y-m-d', $this->date_month_end);
                if (isset($this->date_quarter_end)) {
                    $ret["date_quarter"] = date('Y-m-d', $this->date_quarter_end);
                }
                $ret["date_month_afterpay"] = date('Y-m-d', strtotime(date('Y-m-d', $this->date_month_end)." +1 month"));
            } elseif ($this->date_start && $this->date_end) {
                $dif = $this->date_end - time();
                $dif = $dif / 60 / 60 / 24;
                $ret['result'] = true;
                $ret['diff'] = $dif;
                $ret["ok"] = date('Y-m-d', $this->date_end);
                $ret["date_month"] = date('Y-m-d', $this->date_month_end);
                if (isset($this->date_quarter_end)) {
                    $ret["date_quarter"] = date('Y-m-d', $this->date_quarter_end);
                }
                $ret["date_month_afterpay"] = date('Y-m-d', strtotime(date('Y-m-d', $this->date_month_end)." +1 month"));
            } else {
                $ret["error"] = true;
            }
        }
        
        return $ret;
    }

    public function unpause() {
        if (!$this->date_start)
            $this->date_start = time();
        if ($this->date_end && $this->date_stop > 0) {
            $this->date_end = time() + $this->date_end - $this->date_stop;
            if($this->yearly == 1) $this->date_month_end = strtotime(date('Y-m-d', time())." +1 month");
            $this->date_start = time();
           // var_dump(date('Y-m-d', $this->date_start), date('Y-m-d', $this->date_end));
        } else {
            $this->date_end = time() + $this->product()->first()->days * 24 * 60 * 60;
        }
        $this->stoped = 0;
        $this->save();
    }

    public static function billing_update($query) {

        return ['STATUS' => 1, 'MESSAGE' => 'test'];
        /*$url_prefix = 'http://82.113.35.125/billing.php';

        $answer = Curl::to($url_prefix)
            ->withData($query)
            ->get();

        DB::insert('insert into test1 (text) values (?)', [json_encode($query) . '------' . $answer]);

        if ($answer) {
            $answer = json_decode($answer, true);
        } else $answer = null;

        return $answer;*/
    }

    public static function qarva_service_update($action, $query) {

        return ['status' => true, 'qcmnd_id' => 'test', 'msgcode' => 'test'];

        $url_prefix = 'http://10.10.34.197:8080/'.$action;

        // $answer = Curl::to($url_prefix)
        //     ->withData($query)
        //     ->get();

        // if ($answer) {
        //     $answer = json_decode($answer, true);
        // } else $answer = null;

        // return $answer;
        // 
        return true;
    }

    public static function set_message_update($query) {

        $url_prefix = 'http://91.237.51.39/setMessages.php';

        $answer = Curl::to($url_prefix)
            ->withData($query)
            ->get();

        //DB::insert('insert into test1 (text) values (?)', [json_encode($query) . '------' . $answer]);

        if ($answer) {
            $answer = json_decode($answer, true);
        } else $answer = null;

        return $answer;
    }

    public static function check_billing_status($answer) {
        if ($answer['STATUS'] != 1) {
            /*if ($answer['STATUS'] != -1 && $answer['STATUS'] != -2 && $answer['STATUS'] != -3 && $answer['STATUS'] != -6) {
                return $answer['message'];
            }*/
            return false;
        }
        return true;
    }

    public static function check_billing_message($answer, $sid) {

        $ex = strpos($answer['MESSAGE'], "STB is active already");
        $ex1 = strpos($answer['MESSAGE'], "Cant Deactivate, STB is switched on");
        $ex2 = strpos($answer['MESSAGE'], "STB must be active to switch on");
        $ex3 = strpos($answer['MESSAGE'], "STB must be active to switch off");
        $ex4 = strpos($answer['MESSAGE'], "STB is already deactivated");
        $ex11 = strpos($answer['MESSAGE'], "STB is switched off already");
        $ex22 = strpos($answer['MESSAGE'], "STB Deactivated, moved on BaseAccount");
        $ex33 = strpos($answer['MESSAGE'], "STB Activated, moved on New Account");
        $ex44 = strpos($answer['MESSAGE'],"STB must be active to service_on");
        $ex55 = strpos($answer['MESSAGE'], "Service Switched Off");
        $ex66 = strpos($answer['MESSAGE'], "Service Switched On");
        $ex77 = strpos($answer['MESSAGE'], "STB switched on");
        $ex88 = strpos($answer['MESSAGE'], "STB switched off");
        $ex99 = strpos($answer['MESSAGE'], "STB is switched on already");
        $ex100 = strpos($answer['MESSAGE'], "Invalid PIN Code");
        $ex101 = strpos($answer['MESSAGE'], "PIN Set Successfully");

        if ($ex != false) {
            $answer = ["sid" => $sid, "notice" => 'STB is active already'];
        } elseif ($ex4 !== false) {
            $answer = ["sid" => $sid, "notice" => "STB is already deactivated"];
        } elseif ($ex11 !== false) {
            $answer = ["sid" => $sid, "notice" => "STB is switched off already"];
        } elseif ($ex1 !== false) {
            $answer = ["sid" => $sid, "notice" => "Cant Deactivate, STB is switched on"];
        } elseif ($ex2 !== false) {
            $answer = ["sid" => $sid, "notice" => "STB must be active to switch on"];
        } elseif ($ex3 !== false) {
            $answer = ["sid" => $sid, "notice" => "STB must be active to switch of"];
        } elseif ($ex22 !== false) {
            $answer = ["sid" => $sid, "notice" => "STB Deactivated, moved on BaseAccount"];
        } elseif ($ex33 !== false) {
            $answer = ["sid" => $sid, "notice" => "STB Activated, moved on New Account"];
        } elseif ($ex44 !== false) {
            $answer = ["sid" => $sid, "notice" => "STB must be active to service_on"];
        } elseif ($ex55 !== false) {
            $answer = ["sid" => $sid, "notice" => "Service Switched Off"];
        } elseif ($ex66 !== false) {
            $answer = ["sid" => $sid, "notice" => "Service Switched On"];
        } elseif ($ex77 !== false) {
            $answer = ["sid" => $sid, "notice" => "STB switched on"];
        } elseif ($ex88 !== false) {
            $answer = ["sid" => $sid, "notice" => "STB switched off"];
        } elseif ($ex99 !== false) {
            $answer = ["sid" => $sid, "notice" => "STB is switched on already"];
        } elseif ($ex100 !== false) {
            $answer = ['notice', 'Invalid PIN Code'];
        } elseif ($ex101 !== false) {
            $answer = ['notice', 'PIN Set Successfully'];
        } else {
            $answer = ["sid" => $sid, "notice" => $answer];
        }

        return $answer;
    }

    public function resume_service() {
        $current_time = Carbon::now();

        if ($this->is_service) {
            if (!$this->date_start)
                $this->date_start = time();
            if ($this->date_end && $this->date_stop > 0) {
                $this->date_end = (int)(time() + $this->date_end - $this->date_stop);
                $product = $this->product()->first();
                if($product->yearly == 1 && $product->per_month_count) {
                    $expired = ($this->date_month_end <= $current_time->timestamp ? true : false);
                    if ($expired) {
                        $this->date_month_end = strtotime(date('Y-m-d', time())." +".(int)$product->per_month_count." month");
                    }
                }
                $this->date_start = time();
            } else {
                $this->date_end = (int)(time() + $this->product->days * 24 * 60 * 60);
            }
            $this->stoped = 0;

            if ($this->save()) return true;
        }

        return false;
    }

    public function pause_service() {
        if ($this->date_end > time()) {
            $this->date_stop = time();
            $this->stoped = 1;
        }

        if ($this->save()) return true;

        return false;
    }

    public function addtomonth($user) {
        $region = session('region_id') ? session('region_id') : 1;
        $services = OrderProduct::where('region_id', $region)->where('user_id', $user->id)->where('product_id', 9)->where('is_service', 1)->where('date_end', '>', 0)->whereHas('get_service_stbs', function ($query) {}, '>', 0)->orderBy('date_end')->first();

        /*if($services) {
            $buyers2 = DB::query(Database::SELECT, 'SELECT * FROM year_free WHERE user_id='.$user->id.' AND active=1 AND action=0')
                ->execute('admin_panel')
                ->as_array();
            if($buyers2) {
                $date_end = $services['date_end'];
                foreach($buyers2 as $b) {
                    $user2 = DB::query(Database::SELECT, 'SELECT * FROM users WHERE id='.$b['friend_id'].' LIMIT 1')
                        ->execute('admin_panel')
                        ->current();
                    $date_end = $date_end + 3600*24*30;
                    $query = DB::query(Database::UPDATE, 'UPDATE orders_products SET date_end="'.$date_end.'" WHERE id='.$services['id'].' LIMIT 1')
                        ->execute('admin_panel');
                    orm::factory("orders_products")->sync_remote('update', (string) $query);

                    $query = DB::query(Database::INSERT, 'INSERT INTO products_statuses_logs 
                        (orders_product_id, products_statuse_id, comment) VALUES 
                        ('.$services['id'].', "5", "Уважаемый клиент, ваш друг '.mysql_real_escape_string($user2['name']).' обеспечил вам продление вашей подписки еще на один месяц. Новый срок действия до '.date('d.m.Y',$date_end).'")')
                            ->execute('admin_panel');
                    orm::factory("products_statuses_logs")->sync_remote('insert', (string) $query);

                    $query = DB::query(Database::UPDATE, 'UPDATE year_free SET action=1 WHERE id="'.$b['id'].'" AND active=1 LIMIT 1')
                        ->execute('admin_panel');
                    orm::factory("year_free")->sync_remote('update', (string) $query);



                    $friend = (object)$user2;
                    $dateend = date('d.m.Y',$date_end);
                    // $this->view->assign($data);
                    // $mail_body = $this->view->fetch("emails/year_free.tpl");
                    $domain = 'tvoyo.tv';
                    $mail_body = '
                        <!DOCTYPE HTML>
                            <html>
                                <head>
                                    <title></title>
                                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                                </head>
                                <body>
                                    <table bgcolor="#e8e9ea" cellpadding="10" cellspacing="0" style="font-family: Arial,Helvetica,sans-serif; color: #333333; font-size: 12px; width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="top">
                                                    <table align="center" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="padding: 20px; border: 1px solid #cccccc; width: 80%;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td align="left" style="padding: 5px 10px;" valign="middle" width="40%"></td>
                                                                                <td align="right" style="padding: 5px 10px;" valign="middle" width="60%">
                                                                                    <a href="http://' . $domain . '/" target="_blank">
                                                                                        <img src="http://' . $domain . '/Images/logo.png" alt="" />
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                     <div style="padding: 5px; margin: 10px;">
                                                                        <p>Уважаемый/уважаемая <b>'.$user->name.'</b>,</p>
                                                                        <p>Поздравляем, Ваш друг '.$friend->name.' обеспечил вам продление вашей подписки еще на один месяц! Новый срок действия до '.$dateend.'</p>
                                                                        <p>Наша служба поддержки работает круглосуточно и доступна по телефону: +420 212 342 222 или по адресу: service@tvoyo.tv</p>
                                                                    </div>
                                                                    <div style="font-size: 11px; padding: 20px 10px 10px;">
                                                                        <p><b>Желаем приятного просмотра!</b></p>
                                                                        <p>С уважением и признательностью,</p>
                                                                        <p>Команда TVOYOTV</p>
                                                                        <p>Пожалуйста, не отвечайте на данное сообщение, так как оно генерируется автоматически и служит только для информации.</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <div style="color: #666; font-size: 11px; padding: 20px 50px 10px 50px;"><center>Copyright &copy; '.date('Y').' <a href="http://' . $domain . '/">http://' . $domain . '/</a></center></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </body>
                            </html>
                    ';
                    
                    $mail_subject = "=?utf-8?B?" . base64_encode("Продление подписки на месяц") . "?=";
                    $mail_to = $user->email;
                    $mail_from = ORM::factory('settings_name')->get('email')->get_one_value()->value;
                    Helper_Mail::send_mail($mail_to, $mail_from, $mail_body, $mail_subject);
                }
            }
        }*/

        return true;
    }

    //********** CHANGE MOBILE SERVICE STATUS **********//
    public function turn_on_off_m_s($status) {
        if($this->id){
            $log = new ProductStatusLog;
            $tx = null;
            if ($status == 1) {
                if ($this->product()->first()->for_mobile == 1) {
                    $this->products_statuse_id = 5;
                    $this->stoped = 0;
                    $this->date_stop = null;
                    
                    if (!$this->date_start)
                        $this->date_start = time();
                    if (!$this->date_end && $this->date_stop > 0) {
                        $this->date_end = time() + $this->date_end - $this->date_stop;
                        $this->date_start = time();
                    } else {
                        $this->date_end = time() + $this->product->days * 24 * 60 * 60;
                    }
                }
                
                $log->products_statuse_id = 5;
                $tx = "Уважаемый клиент, мобильный сервис был активирован. Срок действия до " . date('Y-m-d', $this->date_end) . ". Желаем приятного просмотра.";
                //QARVA ACTIVATE
                $qarva_add_account_res = $this->add_account($this->user_id);

                if ($qarva_add_account_res['result']) {
                    //die(json_encode(array("error" => "1", 'error_message' => 'Service account was not created.')));
                    $qarva_srvice_on_off_account_res = $this->service_on_off_account($status);
                    $subscribe_on_off = $this->subscribe_on_off($status);

                    BillingLog::billing_log_update(['text' => json_encode($subscribe_on_off, true), 'type' => 'mobile_service_activation', 'description' => $this->code]);
                }

            } elseif ($status == 2) {
                if ($this->product()->first()->for_mobile == 1) {
                    $this->products_statuse_id = 6;
                    $this->stoped = 1;
                    $this->date_stop = date('Y-m-d h:i:s');
                }

                $log->products_statuse_id = 14;
                $tx = "Уважаемый клиент, мобильный сервис был приостановлен, пожалуйста обратитесь в службу технической поддержки.";
                //QARVA DEACTIVATE
                $qarva_add_account_res = $this->add_account($this->user_id);

                if ($qarva_add_account_res['result']) {
                    $subscribe_on_off = $this->subscribe_on_off($status);

                    BillingLog::billing_log_update(['text' => json_encode($subscribe_on_off, true), 'type' => 'mobile_service_deactivation', 'description' => $this->code]);
                }
            }
            
            $log->comment = $tx;
            $log->region_id = $this->region;
            $log->save();
            $this->logs()->save($log);
            $this->save();

            return $this;
        }
    }

    //********** CHANGE PIN **********//
    public function action_stb_change_pin($pin) {
        if ($pin) {
            $query = ['action' => 'pin_change', 'mac' => str_replace(':', '', $this->mac), 'pin' => $pin];
            $res = $this->billing_update($query);
            if ($res) {
                if ($this->check_billing_status($res)) {
                    $this->pin_code = $pin;
                    $this->save();
                    return true;
                }
            }
        }
        return false;
    }

    //********** ACTIVATE NEW SERVICE AFTER AUTOMATIC DEACTIVATION **********//
    
    function check_and_activate_services($deactivated_service, $stbs, $packages) {

        $stb_ids = $stbs->pluck('id')->toArray();
        $user = $this->user()->first();
        sort($stb_ids);
        $user_id = $this->user_id;

        $o_prod = $user->get_free_services_with_priority($deactivated_service->product_id)->get();

        $arr_builder['NEXT SERVICE'] = 'no new service available';

        if ($o_prod->count() > 0) {

            //dump($o_prod->first());

            $new_serv = null;
            $match = false;
            $new_stb_ids = [];
            foreach ($o_prod  as $prod) {
                $stb_ids_arr = array_map('intval', explode(',', $prod->order_stb));
                sort($stb_ids_arr);
                if ($stb_ids_arr == $stb_ids) {
                    $new_serv = $prod; 
                    $new_stb_ids = $stb_ids;
                    $match = true; 
                    break;
                }
            }

            //dump([1, $new_serv, $new_stb_ids]);

            if (!$new_serv) {
                foreach ($o_prod as $tmp_srv_1) {
                    if ($tmp_srv_1->order_stb != null && $tmp_srv_1->order_stb != 0 && $tmp_srv_1->order_stb != '') {
                        $new_stb_ids_tmp = array_map('intval', explode(',', $tmp_srv_1->order_stb));
                        $real_stbs_tmp = $user->get_free_active_stb()->find($new_stb_ids_tmp);
                        if (count($real_stbs_tmp) >= count($new_stb_ids_tmp) && !array_diff($new_stb_ids_tmp, $real_stbs_tmp)) {
                            $new_serv = $tmp_srv_1;
                            $new_stb_ids = array_map('intval', explode(',', $new_serv->order_stb));
                            //dump($new_serv, $new_serv->order_stb, $real_stbs_tmp);
                            break;
                        }
                    }
                }
            }

            //dump([2, $new_serv, $new_stb_ids]);

            if (!$new_serv) {
                foreach ($o_prod as $tmp_srv_1) {
                    if ($tmp_srv_1->order_stb == null || $tmp_srv_1->order_stb == 0 || $tmp_srv_1->order_stb == '') {
                        $new_serv = $tmp_srv_1; 
                        $new_stb_ids = $stb_ids;
                        $match = true; 
                        break;
                    }
                }
            }

            // dump([3, $new_serv, $new_stb_ids]);

            // dump($new_serv, $new_serv->order_stb, $real_stbs_tmp);
            // die;
            
            if ($new_serv && count($packages) > 0) {

                if ($new_serv->order && $new_serv->order->orders_status_id == 3) {
                    return ['result' => json_encode(['status' => false, 'message' => 'service has declined order.']), 'arr_builder' => $arr_builder];
                }

                if ($new_serv->stoped == 1) {
                    return ['result' => json_encode(['status' => false, 'message' => 'service was already deactivated.']), 'arr_builder' => $arr_builder];
                }

                $pid = 1;
                $new_status_stb = ProductStatus::where('region_id', $this->region_id)->orWhere('region_id', null)->where('is_p', 1)->find($pid);
                $new_status_service = ProductStatus::where('region_id', $this->region_id)->orWhere('region_id', null)->where('is_s', 1)->find($packages);

                $real_stbs = $user->get_free_active_stb()->find($new_stb_ids);
                
                //return [$real_stbs->pluck('code')->toArray(), $new_stb_ids];

 /*                DB::insert('insert into test1 (text) values (?)', ['|---------->['.$this->code.'][NEW SERVICE ACTIVATION]['.$new_serv->code.']['.json_encode($new_stb_ids, true).']['.json_encode($real_stbs->pluck('id')->toArray(), true).']---']);*/

                BillingLog::billing_log_update(['text' => json_encode($new_stb_ids, true), 'type' => 'new_service_activation', 'description' => $new_serv->code]);

                if(!$match && $real_stbs->count() != count($new_stb_ids)) {
                    return ['result' => json_encode(['status' => false, 'message' => 'some stbs already have active service.']), 'arr_builder' => $arr_builder];
                }

                try {
                    $arr_builder['NEXT SERVICE'] = [$new_serv->code => []];
                    $multiroom_ind = $real_stbs->count() > 1 ? 1 : 0;
                    $multiroom_macs = null;
                    $macs = [];
                    $stb_ids_str = '';

                    //ACTIVATE STB
                    foreach($real_stbs as $real_stb){
                        $real_stb->multir_ind = $multiroom_ind;
                        $real_stb->active_service_id = $new_serv->id;

                        $macs[$real_stb->id] = $real_stb->mac;
                        $stb_ids_str .= $real_stb->id . ',';

                         //switch on stb
                        $query = ['action' => $new_status_stb->action, 'mac' => str_replace(':', '', $real_stb->mac)];
                        $answer = OrderProduct::billing_update($query);
                        $res = OrderProduct::check_billing_status($answer);
                        $resArr [$real_stb->mac]['stb']= $res;
                        $resArr1 [$real_stb->mac]['stb']= $answer;

                        /*DB::insert('insert into test1 (text) values (?)', ['|---------->[Activation - stb_status]['.$real_stb->mac.']---' . json_encode($resArr1, true)]);*/

                        BillingLog::billing_log_update(['text' => json_encode($resArr1, true), 'type' => 'service_activation_stb_status', 'description' => $real_stb->mac]);

                        $new_serv->get_service_stbs()->attach($real_stb->id);
                        $real_stb->products_statuse_id = $pid;
                        $real_stb->save();

                        if ($res) {
                            $log = new ProductStatusLog;
                            $log->region_id = $real_stb->region_id;
                            $log->products_statuse_id = $pid;
                            $log->save();

                            $real_stb->logs()->save($log);
                            $real_stb->save();

                            $arr_builder['NEXT SERVICE'][$new_serv->code]['MACS'][]= $real_stb->mac;
                        }
                    }

                    //SET SERVICE PARAMETERS 
                    if ($real_stbs->count() > 0) {
                        $new_serv->multir_ind = $multiroom_ind;
                        $new_serv->order_stb = rtrim($stb_ids_str, ',');
                        $new_serv->save();
                    } 

                    $process_arr = [
                        'activate' => $packages
                    ];
                    
                    //update packages
                    $package_update_result = OrderProduct::manage_packages($real_stbs, $process_arr);
                    $status = $package_update_result['status'];
                    $messages = $package_update_result['messages'];

                    /*DB::insert('insert into test1 (text) values (?)', ['|---------->[Activation - new service]['.$new_serv->code.']['.$status.']---' . json_encode($messages, true)]);*/

                    BillingLog::billing_log_update(['text' => json_encode($messages, true), 'type' => 'service_activation_new', 'description' => $new_serv->code]);

                    //ACTIVATE SERVICE
                    if ($status) {
                        $new_serv = $new_serv->manage_statuses($process_arr);
                        $arr_builder['NEXT SERVICE'][$new_serv->code]['PACKAGES'] = $packages;
                        $resArr = [];
                        $resArr1 = [];
                        foreach ($real_stbs as $ob) {

                            $query = ['action' => 'stb_switchon', 'mac' => str_replace(':', '', $ob->mac)];
                            $answer = OrderProduct::billing_update($query);
                            $res = OrderProduct::check_billing_status($answer);
                            $resArr [$ob->mac]['stb']= $res;
                            $resArr1 [$ob->mac]['stb']= $answer;

                           /* DB::insert('insert into test1 (text) values (?)', ['|---------->[Activation - turn on service]['.$new_serv->code.']---' . json_encode($resArr1, true)]);*/

                            BillingLog::billing_log_update(['text' => json_encode($resArr1, true), 'type' => 'service_activation_switch_on', 'description' => $new_serv->code]);
                            
                            if ($res) {
                                $query = ['action' => 'assign_account_number', 'mac' => str_replace(':', '', $ob->mac), 'account_number' => $ob->user->code.'-'.$ob->code];
                                
                                $answer1 = OrderProduct::billing_update($query);
                                $res = OrderProduct::check_billing_status($answer);
                            }
                        }

                        if ($new_serv) {
                            $log = new ProductStatusLog;
                            $log->region_id = $new_serv->region_id;
                            $log->comment = "Уважаемый клиент, сервис " . $new_serv->name . " активирован. Срок действия до " . date('Y-m-d', $new_serv->date_end) . ". Желаем приятного просмотра. ";
                            $log->products_statuse_id = 5;
                            $log->save();

                            $new_serv->products_statuse_id = 5;
                            $new_serv->save();
                            $new_serv->logs()->save($log);

                            //QARVA ACTIVATION
                            $qarva_add_account_res = $new_serv->add_account($this->user->id);

                            if ($qarva_add_account_res['result']) {
                                    //die(json_encode(array("error" => "1", 'error_message' => 'Service account was not created.')));
                                $qarva_srvice_on_off_account_res = $new_serv->service_on_off_account(1);
                                $subscribe_on_off = $new_serv->subscribe_on_off(1);
                            } 

                            return [ 'result' => json_encode(['status' => true, 'message' => 'service '.$new_serv->code.' activated on MACs '.json_encode($new_serv->get_service_stbs()->pluck('mac')->toArray(), true)]), 'arr_builder' => $arr_builder];
                        }
                    }
                } catch (Exception $e) {
                    return ['result' => json_encode(['status' => false, 'message' => $e]), 'arr_builder' =>  $arr_builder];
                }
            }
        }

        return ['result' => json_encode(['status' => false, 'message' => 'action not performed']), 'arr_builder' => $arr_builder];
    }

    //********** DEACTIVATE SERVICES **********//
    public static function deactivate_services($services) {
        $current_time = Carbon::now();
        $result_array = [];
        $arr_builder = [];
        foreach ($services as $one) {
            $text = null;
            $products_statuse_id = $one->products_statuse_id;
            $translated = $one->product->translated()->first();
            $expired = ($one->date_end <= $current_time->timestamp ? true : false);
            $date_end = Carbon::createFromTimestamp($one->date_end);
            if (!$expired) {
                //send expiration warning message
                $day = $current_time->diffInDays($date_end);
                if(in_array($day, array(5, 3, 1))) {
                    $subject = 'tvoyo.tv - Подписка заканчивается через '.$day.' дней';
                    $text = 'Уважаемый клиент, срок подписки <b>'.$one->code.'</b> "'.$translated->name . ($translated->short_text ? ' (' . $translated->short_text . ')' : '') . '" истекает через '.$day.' дней.<br/>';
                    $notice = 'Уважаемый клиент, сервис '.$translated->name . ($translated->short_text ? ' (' . $translated->short_text . ')' : '') . ' истекает через '.$day.' дней.';
                    $res = OrderProduct::construct_message($one, $day, $subject, $text, $notice);
                }
            } else {
                //deactivate service
                if ($stbs = $one->get_service_stbs()->get()) {
                    $res = null;
                    $resArr = [];
                    $ansArr = [];
                    $status = true;

                    $current_packages = $one->service_statuses()['selected'];
                    $process_arr = [
                        'deactivate' => $current_packages
                    ];

                    //update packages
                    $package_update_result = OrderProduct::manage_packages($stbs, $process_arr);
                    $status = $package_update_result['status'];
                    $messages = $package_update_result['messages'];
                    $log = $package_update_result['log'];

                    //RECORD SERVICE PACKAGES DEACTIVATION LOG
                    $arr_builder[$one->code] = [ 'STATUS' => $status, 'PACKAGES DEACTIVATION LOG' => $log];

                    foreach ($stbs as $stb) {
                        //switch off stb
                        $query = ['action' => 'stb_switchoff', 'mac' => str_replace(':', '', $stb->mac)];
                        $answer = OrderProduct::billing_update($query);
                        $res = OrderProduct::check_billing_status($answer);
                        $resArr [$stb->mac]['stb']= $res;
                        $resArr1 [$stb->mac]['stb']= $answer;

                        $arr_builder[$one->code]['STBS SWITCHOFF LOG'][$stb->mac] = $answer;
                        $stb->multir_ind = ($stbs->count() > 1 ? 1 : 0);
                        $stb->save();
                    }

                    if ($status) {
                        //construct message
                        $subject = 'tvoyo.tv - Срок вашей подписки истек.';
                        $text = 'Уважаемый клиент, срок подписки <b>'.$one->code.'</b> "' . $translated->name . ($translated->short_text ? ' (' . $translated->short_text . ')' : '') . '" истек.<br/>';
                        $notice = 'Уважаемый клиент, срок подписки <b>'.$one->code.'</b> "'.$translated->name . ($translated->short_text ? ' (' . $translated->short_text . ')' : '') . '" истек.<br/>';
                        $products_statuse_id = 14;
                        $res = OrderProduct::construct_message($one, 0, $subject, $text, $notice);

                        $one = $one->manage_statuses($process_arr);
                        $one->products_statuse_id = 14;
                        $one->get_service_stbs()->detach();

                        // deactivate mobile service
                        $one->turn_on_off_m_s(2);
                        $res = $one->check_and_activate_services($one, $stbs, $current_packages);
                        $arr_builder[$one->code]['ACTIVATE SERVICE IN QUEUE'] = $res['arr_builder'];
                    }
                }
            }

            if ($text) {
                 $log = new ProductStatusLog;
                $log->region_id = $one->region_id;
                $log->products_statuse_id = $products_statuse_id;
                $log->comment = $text;
                $log->save();
                $one->logs()->save($log);
                $one->save();
            }
        }

        if (count($services) > 0) {
           /* DB::insert('insert into test1 (text) values (?)', ['[DAILY SERVICE DEACTIVATION RESULTS]---'.json_encode($arr_builder, true)]);*/

            BillingLog::billing_log_update(['text' => json_encode($arr_builder, true), 'type' => 'daily_service_deactivation_results']);
        }
    }

    public static function manage_subscriptions($services) {
        $current_time = Carbon::now();
        $deactivation_arr = [];
        $arr_builder = [];
        /*foreach ($services as $one) { 
            DB::insert('insert into test1 (text) values (?)', ['SUBSCRIPTIONS-TEST --- test:'.$one->code.'---'.$one->region_id]);
        }*/

       /* foreach ($services as $one) {
            
            $domain = config('database.region.'.$one->region_id.'.domain');
            $date_month_end = Carbon::createFromTimestamp($one->date_month_end);
            $date_end = Carbon::createFromTimestamp($one->date_end);
            $per_month_count = $one->product->per_month_count;
            $day = $current_time->diffInDays($date_month_end);
            $day = $date_month_end > $current_time ? $day : -$day;

            $switchOffDate = clone $date_end;
            $pauseDate = clone $date_month_end;
            if (($one->products_statuse_id == 6 && $day < 0) || $date_end->gte($date_month_end)) {
                $switchOffDate = $pauseDate->addMonths($per_month_count);
            }

            if(in_array($day, array(5,3,1)) || $day <= 0) {
                if ($day > 0 && $switchOffDate->gt($current_time) && $one->products_statuse_id == 5) {
                    DB::insert('insert into test1 (text) values (?)', ['--SUBSCRIPTIONS --- warning:'.$one->code]);
                } elseif ($day <= 0 && $switchOffDate->gt($current_time) && $one->products_statuse_id == 5) {
                    DB::insert('insert into test1 (text) values (?)', ['--SUBSCRIPTIONS --- pause:'.$one->code]);
                } elseif ($switchOffDate->lte($current_time)) {
                    DB::insert('insert into test1 (text) values (?)', ['--SUBSCRIPTIONS --- deactivate:'.$one->code]);
                }
                
            }
        }

        die;*/

        foreach ($services as $one) {
            
            $domain = config('database.region.'.$one->region_id.'.domain');
            $subject = null;
            $text = null;
            $notice = null;
            $send_message = false;
            $products_statuse_id = $one->products_statuse_id;
            $translated = $one->product->translated()->first();
            $date_month_end = Carbon::createFromTimestamp($one->date_month_end);
            $date_end = Carbon::createFromTimestamp($one->date_end);
            $per_month_count = $one->product->per_month_count;
            $day = $current_time->diffInDays($date_month_end);
            $day = $date_month_end > $current_time ? $day : -$day;

            $switchOffDate = clone $date_end;
            $pauseDate = clone $date_month_end;
            if (($one->products_statuse_id == 6 && $day < 0) || $date_end->gte($date_month_end)) {
                $switchOffDate = $pauseDate->addMonths($per_month_count);
                //$date_month_end->addMonths(-$per_month_count);
            }

            /*var_dump($one->code,$one->products_statuse_id, $date_month_end->toDateTimeString(), $date_end->toDateTimeString(),$switchOffDate->toDateTimeString(), '<br>' );*/

            if(in_array($day, array(5, 3, 1)) || $day <= 0) {
                if ($day > 0 && $switchOffDate->gt($current_time) && $one->products_statuse_id == 5) {
                    //send warning message
                    $subject = 'tvoyo.tv - Подписка заканчивается через '.$day.' дней';
                    $text = 'Срок подписки <b>'.$one->code.'</b> "' . $translated->name . ($translated->short_text ? ' (' . $translated->short_text . ')' : '') . '" истекает через '.$day.' дней. Необходимо продлить подписку.<br/>
                    1. Через личный кабинет <a href="http://'.$domain.'/profile/services">http://'.$domain.'/profile/services</a><br/>
                    2. По ссылке продления <a href="http://'.$domain.'/cart/prolong/'.$one->id.'">http://'.$domain.'/cart/prolong/'.$one->id.'</a><br/>';
                    $notice = 'Уважаемый клиент, сервис ' . $translated->name . ($translated->short_text ? ' (' . $translated->short_text . ')' : '') . ' истекает через '.$day.' дней. Необходимо продлить сервис.';
                    $products_statuse_id = 5;
                    $send_message = true;

                    $arr_builder[$one->code] = ['message' => 'send warning message', 'day(s) left' => $day];

                    //DB::insert('insert into test1 (text) values (?)', ['SUBSCRIPTIONS --- warning:'.$notice]);
                    //var_dump('--------', $one->code, $day, 'send warning message', '<br>' );

                } elseif ($day <= 0 && $switchOffDate->gt($current_time) && $one->products_statuse_id == 5) {
                    //pause service
                    $subject = 'tvoyo.tv - Срок подписки истек';
                    $text = 'Срок подписки <b>'.$one->code.'</b> "' . $translated->name . ($translated->short_text ? ' (' . $translated->short_text . ')' : '') . '" истек. Услуга приостановлена. Необходимо продлить подписку.<br/>
                    1. Через личный кабинет <a href="http://'.$domain.'/profile/services">http://'.$domain.'/profile/services</a><br/>
                    2. По ссылке продления <a href="http://'.$domain.'/cart/prolong/'.$one->id.'">http://'.$domain.'/cart/prolong/'.$one->id.'</a><br/>';
                    $notice = 'Уважаемый клиент, сервис ' . $translated->name . ($translated->short_text ? ' (' . $translated->short_text . ')' : '') . ' приостановлен. Продлите сервис.';
                    $products_statuse_id = 6;
                    $send_message = true;

                    $status = ProductStatus::where('region_id', $one->region_id)->orWhere('region_id', null)->find($products_statuse_id);
                    $query = ['action' => $status->action, 'mac' => str_replace(':', '', $one->mac)];
                    $answer = OrderProduct::billing_update($query);
                    $res = OrderProduct::check_billing_status($answer);

                    if ($res) {
                        $one->products_statuse_id = $products_statuse_id;
                        $one->stoped = 1;
                        $one->date_stop = $current_time->timestamp;
                        $one->save();
                    }

                    $arr_builder[$one->code] = ['message' => 'subscription paused'];

                    //DB::insert('insert into test1 (text) values (?)', ['SUBSCRIPTIONS --- pause:'.$notice]);
                    //var_dump('--------', $one->code, $day, 'pause service', '<br>' );
                    
                } elseif ($switchOffDate->lte($current_time)) {
                    //deactivate service
                    $subject = 'tvoyo.tv - Подписка отключена';
                    $text = 'Подписка <b>'.$one->code.'</b> отключена';
                    $notice = 'Уважаемый клиент, сервис <b>'.$one->code.'</b> "' . $translated->name . ($translated->short_text ? ' (' . $translated->short_text . ')' : '') . '" отключен.';
                    $products_statuse_id = 14;
                    $send_message = true;

                    $deactivation_arr []= $one;
                    //var_dump('--------', $one->code, $day, 'deactivate service', '<br>' );
                } else {
                    //var_dump('--------', $day, 'do nothing (INSIDE)', '<br>' );
                }

                if ($text) {
                    $log = new ProductStatusLog;
                    $log->region_id = $one->region_id;
                    $log->products_statuse_id = $products_statuse_id;
                    $log->comment = $notice;
                    $log->save();
                    $one->logs()->save($log);
                }

                if ($send_message) {
                    $res = OrderProduct::construct_message($one, $day, $subject, $text, $notice);
                }

            }else {
                
            }
        }

        /*DB::insert('insert into test1 (text) values (?)', ['[DAILY SUBSCRIPTION MANAGEMANT RESULTS]---'.json_encode($arr_builder, true)]);
*/
        BillingLog::billing_log_update(['text' => json_encode($arr_builder, true), 'type' => 'daily_subscription_managemant_results']);

        OrderProduct::deactivate_services($deactivation_arr);

        return true;
    }

    //********** DEACTIVATE MOBILE SERVICES **********//
    public static function deactivate_mobile_services($services) {
        foreach ($services as $one) {
            $one->turn_on_off_m_s(2);
        }
    }

    public static function construct_message($service, $day, $subject, $text, $notice) {

        $domain = config('database.region.'.$service->region_id.'.domain');
        $crlf = chr(13) . chr(10);
        $day_ref_ru = null;
        $day_ref_en = null;
        $message_arr = [
            'days' => $day,
            'type' => 2,
            'number' => $service->price,
            'name' => $service->name,
            'payment_date' => $service->date_end,
        ];

        $stbs = $service->get_service_stbs()->get();

        foreach ($stbs as $stb) {
            $message = new Message;
            $message->mac = $stb->mac;
            $message->message = $text;
            $message->region_id = $stb->region_id;
            $message->save();
            $stb->messages()->save($message);
        }
        

        $res = OrderProduct::send_message_to_stb($service, $message_arr);

        // $user = $service->user;

        $user = User::find(215);

        // Mail::send('emails.service_status_change', ['user' => $user, 'domain' => $domain, 'text' => $text], function ($m) use ($user, $subject) {
        //     $m->from('tvoyotv@hotmail.com', 'TVOYO');
        //     $m->to($user->email, $user->username)->subject($subject);
        // });
    }


    public static function send_message_to_stb($service, $message_arr) {

        $stbs = $service->get_service_stbs()->get();
        foreach ($stbs as $stb) {
            $message_arr['mac'] = '00:09:DF:6D:8C:C3';
            $res = OrderProduct::set_message_update($message_arr);
        }
    }

    public static function send_message_to_selected_stbs($macs_arr, $message_arr) {
        $stbs = DB::table('orders_products')
            ->select('mac', 'id')                
            ->Where(function ($query) use($macs_arr) {
                 for ($i = 0; $i < count($macs_arr); $i++){
                    $query->orwhere('mac', 'like',  '%' . $macs_arr[$i] .'%');
                 }      
            })->get();

        dd(count($stbs));

        foreach ($stbs as $stb) {
            $message_arr['mac'] = '00:09:DF:6D:8C:C3';
            $res = OrderProduct::set_message_update($message_arr);
        }
    }

    public function deleteHelper() {
        $region = session('region_id') ? session('region_id') : 1;
        $connection = config('database.region.'.$region.'.database_remote');
        $ops = [];
        $errors = [];
        $messages = [];

        $ops ['remote']= OrderProduct::where('region_id', $region)->find($this->id);
        $ops ['local']= $this;
        
        foreach ($ops as $k => $op) {
            if ($op) {
                //$res = ['status' => true, 'message' => null];

                $res = $op->check_product_before_remove();
                if ($res['status']) {

                    if ($op->is_service) {
                        $res = $op->manage_service_before_remove();
                    } elseif ($op->is_p) {
                        $res = $op->manage_stb_before_remove();
                    }

                    if ($res['status']) {
                        $messages [$op->code]= 'Product '.$op->code.' deleted';

                        $op->logs()->delete();
                        $op->delete();

                    }
                } else $errors [$op->code]= $res['message'];
            }
        }

        $status = (count($errors) > 0 ? false : true);

        $error_str = '';
        foreach ($errors as $reg => $error) {
            $error_str .= '<div class="error_holder-dialog"><ul class="error_log-alert-1">'.$error.'</ul></div>';
        }

        $messsage_str = '';
        foreach ($messages as $reg => $message) {
            $messsage_str .= '<div class="success_holder-dialog">'.$message.'</div>';
        }

        return ['status' => $status, 'errors' => $error_str, 'messages' => $messsage_str];
    }

    public function saveHelper($data) {
        $region = session('region_id') ? session('region_id') : 1;
        $connection = config('database.region.'.$region.'.database_remote');
        $ops = [];
        $errors = [];
        $status = true;
        $ops ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);
            foreach ($ops as $connection => $op) {
                if ($op) {
                    if ($op->is_p == 1) {
                        if (isset($data['mac'])) {
                            $billing_check = $this->billing_update(['action' => 'stb_check', 'mac' => str_replace(':', '', $data['mac'])]);
                            if ($item = $op->macCheck(trim($data['mac'])) && $billing_check['MESSAGE'] == 1) {
                                return ['status' => false, 'errors' => '<div class="error_holder-dialog">MAC ' . $data['mac'] . ' already used by ' . $item->code.'</div>'];
                            }
                        }
                    }
                    
                    foreach ($op->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $op->$attr = $data[$attr];
                        }
                    }

                    $op->region_id = $region;
                    
                    if ($id) {
                        $op->id = $id;
                    }

                    if(!$op->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        if ($op->is_service == 1) {
                            if (isset($data['stbs'])) {
                                $ids_arr = [];
                                foreach ($data['stbs'] as $id => $status) {
                                    $stb = OrderProduct::where('region_id', $region)->where('id', $id)->first();
                                    if ($stb) {
                                        $ids_arr []= $id;
                                    }
                                }

                                if (count($ids_arr) == 0) {
                                    $op->get_service_stbs()->detach();
                                } else $op->get_service_stbs()->sync($ids_arr);
                            } else { 
                                $op->get_service_stbs()->detach(); 
                            }

                            if (!$op->mob_account_id) {
                                $op->mob_account_id = $op->generate_mob_account_code($op->id);
                            }

                            if (!$op->service_password) {
                                $op->service_password = $op->generate_service_password();
                            }



                            if ($op->status()->count() > 0) {
                                $op->order_stb = implode(',', $op->get_service_stbs()->pluck('orders_products.id')->toArray());
                            } elseif (!isset($data['order_stb'])) {
                                $op->order_stb = null;
                            } elseif (isset($data['order_stb']) && isset($data['preselected_order_stb'])) {
                                $op->order_stb = $data['order_stb'];
                            }
                            
                            $op->save();
                        }

                        $id = $op->id;
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

    public static function manage_packages($stbs, $process_arr) {
        $resArr = [];
        $ansArr = [];
        $logArr = [];
        $status = true;
        foreach ($stbs as $stb) {
            $logArr[$stb->mac]['CHANGE PACKAGE'] = [$process_arr];
            //DB::insert('insert into test1 (text) values (?)', ['[MAC CHANGE PACKAGE]['.$stb->mac.']' . '------' . json_encode($process_arr, true)]);
            foreach ($process_arr as $action => $package_ids) {
                foreach ($package_ids as $package_id) {
                    $package = ProductStatus::where('region_id', $stb->region_id)->orWhere('region_id', null)->find($package_id);
                    if ($package) {
                        $url = null;
                        if ($action == 'activate') {
                            $query = ['action' => 'service_on', 'mac' => str_replace(':', '', $stb->mac), 'snum' => $package->get];
                            //$stb->active_service_id = $one->id;
                        } elseif ($action == 'deactivate') {
                            $query = ['action' => 'service_off', 'mac' => str_replace(':', '', $stb->mac), 'snum' => $package->get];
                            //$stb->active_service_id = null;
                        }

                        $answer = OrderProduct::billing_update($query);
                        $res = OrderProduct::check_billing_status($answer);

                        if (!$res) {
                            $status = false;
                        }

                        $resArr [$stb->mac]['package'][$package_id] = $res;
                        $ansArr [$stb->mac]['package'][$package_id] = $answer;
                        $logArr [$stb->mac]['CHANGE PACKAGE RESULT'][$package_id] = $res;
                    }
                }  
            }
        }

        return ['status' => $status, 'messages' => $ansArr, 'log' => $logArr];
    }

    public function macCheck ($mac) {
        $region = session('region_id') ? session('region_id') : 1;
        if ($this->id) {
            $result = OrderProduct::where('region_id', $region)->where('id', '!=', $this->id)->where('is_p', 1)->where('mac', $mac)->first();
        } else $result = OrderProduct::where('region_id', $region)->where('is_p', 1)->where('mac', $mac)->first();

        return $result;
    }

    //ADD SERVICE ACCOUNT
    public function add_account($user_id) {

        $answer = true;
        $result = true;
        $account = null;

        $curr_acc = ServiceAccount::where('region_id', $this->region)->where('ref_account_id', $this->mob_account_id)->first();

        if (!$curr_acc) {
            $ref_account_id = $this->mob_account_id;
            $ref_profile_id = $this->mob_account_id;
            $ref_account_name = $this->code;
            $dispaly_name = $this->code;
            $username = $this->mob_account_id;
            $password = $this->service_password;
            $pin = null;
            $result = false;

            $action = 'add_account';
            $query = ['ref_account_id' => $ref_account_id, 'ref_profile_id' => $ref_profile_id, 'ref_account_name' => $ref_account_name, 'dispaly_name' => $dispaly_name, 'username' => $username, 'password' => $password];
            $answer = $this->qarva_service_update($action, $query);

            if ($answer) {
                $account = new ServiceAccount;
                $account->ref_account_id    = $ref_account_id;
                $account->ref_profile_id    = $ref_profile_id;
                $account->ref_account_name  = $ref_account_name;
                $account->dispaly_name      = $dispaly_name;
                $account->username          = $username;
                $account->password          = $password;
                $account->status            = 0;
                $account->service_status    = 0;
                $account->pin               = $pin;
                $account->user_id           = $user_id;
                $account->region_id         = $this->region;
                $account->save();

                $result = true;
            }
        } else {
            $result = true;
            $account = $curr_acc;
        }

        return ['qarva_answer' => $answer, 'acc_id' => $account->id, 'result' => $result];
    }

    //CHANGER ACCOUNT SERVICE STATUS
    public function service_on_off_account($status) {
        $ref_account_id = $this->mob_account_id;
        $ref_profile_id = $this->mob_account_id;
        $ref_service_id = 100;
        $result = false;

        $action = 'service_on_off_account';
        $query = ['ref_account_id' => $ref_account_id, 'ref_profile_id' => $ref_profile_id, 'ref_service_id' => $ref_service_id, 'on_off' => $status];
        $answer = $this->qarva_service_update($action, $query);

        if ($answer) {
            $account = ServiceAccount::where('region_id', $this->region)->where('ref_account_id', $ref_account_id)->first();
            if ($account) {
                $account->ref_service_id = $ref_service_id;
                $account->service_status = $status;
                $account->save();

                $result = true;
            }
        }

        return ['qarva_answer' => $answer, 'acc_id' => $account->id, 'result' => $result];
    }

    //CHANGER ACCOUNT STATUS
    public function subscribe_on_off($status) {
        $ref_account_id = $this->mob_account_id;
        $ref_profile_id = $this->mob_account_id;
        $result = false;

        $action = 'subscribe_on_off';
        $query = ['ref_account_id' => $ref_account_id, 'on_off' => $status];
        $answer = $this->qarva_service_update($action, $query);

        if ($answer) {
            $account = ServiceAccount::where('region_id', $this->region)->where('ref_account_id', $ref_account_id)->first();
            if ($account) {
                $account->status = $status;
                $account->qcmnd_id = $answer['qcmnd_id'];
                $account->msgcode = $answer['msgcode'];
                $account->save();

                $result = true;
            }
        }
        
        return ['qarva_answer' => $answer, /*'acc_id' => $account->id,*/ 'result' => $result];
    }

    //DELETE ACCOUNT
    public function del_account($status) {
        $ref_account_id = $this->mob_account_id;
        $result = false;

        $action = 'del_account';
        $query = ['ref_account_id' => $ref_account_id];
        $answer = $this->qarva_service_update($action, $query);

        if ($answer) {
            $account = ServiceAccount::where('region_id', $this->region)->where('ref_account_id', $ref_account_id)->first();
            if ($account->id) {
                $account->delete();

                $result = true;
            }
        }
        
        return ['qarva_answer' => $answer, 'acc_id' => $account->id, 'result' => $result];
    }

    public function generate_service_password($length = 6) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function update_stbs_activation() {

        $stbs = OrderProduct::get_all_products()->get();

        foreach ($stbs as $stb) {
            $service = $stb->get_stb_service->first();
            if ($service) {
                $stb->active_service_id = $service->id;
                if ($service->get_service_stbs->count() > 1) {
                    $stb->multir_ind = 1;
                } else {
                    $stb->multir_ind = 0;
                }
            } else {
                $stb->active_service_id = null;
                $stb->multir_ind = 0;
            }

            $stb->save();
        }

        return true;
    }
}