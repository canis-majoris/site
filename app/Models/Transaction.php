<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use Illuminate\Http\Request;

class Transaction extends Model  {

	use SoftDeletes;
    protected $default_region_id = 1;
    protected $region = null;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $region = session('region_id') ? session('region_id') : $this->default_region_id;
        $this->connection = config('database.region.'.$this->region.'.database');
        $this->prefix = config('database.region.'.$region.'.prefix');
    }
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'transactions';
	protected $dates = ['deleted_at'];

	protected $fillable = ['id', 'region_id', 'date', 'text', 'transaction_id', 'url', 'merchant_transaction_id', 'order_id', 'user_id', 'use_rec', 'payed', 'created_at', 'updated_at', 'deleted_at'];

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
	public function order() 
	{
		return $this->belongsTo('App\Models\Order\Order', 'order_id');
	}

	public function user() 
	{
		return $this->belongsTo('App\Models\Tvoyo\User', 'user_id');
	}

	public function generate_code($region_id = null) {
        $prefix = $this->prefix['transaction'];
        if ($region_id) {
            $prefix  = config('database.region.'.$region_id.'.prefix')['transaction'];
        } 
        return $prefix . str_pad($this->id, 7, '0', STR_PAD_LEFT);
    }

    public function generate_merchant_id() {
        return 'transaction' . $this->id;
    }
    
}