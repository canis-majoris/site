<?php 
namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\TvoyoUserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\Tvoyo\User;
use App\Models\Language;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Region;
use App\Models\Discount\Discount;
use App\Models\Product\Product;
use App\Models\Product\ProductStatusLog;
use App\Models\Product\ProductStatus;
use Config;
use Image;

class TvoyoUserRepository extends BaseRepository implements TvoyoUserInterface  {

	/**
	 * @param Discount
	 */
	public function __construct(User $user) {

		$this->model = $user->filterRegion();

		$this->region = session('region_id') ? session('region_id') : $this->default_region_id;
	}

	/**
	 * @return [type]
	 */
	public function getAll() {

		return $this->model->get();
	}

	public function selectAll() {

		return $this->model->select();
	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function getById($id) {

		return $this->model->find($id);
	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function create(array $attributes) {

		return $this->model->create($attributes);
	}

	public function getAllDealers() {

		return $this->model->selectDealers()->get();
	}

	public function selectDefaultRegionUsers() {

		$region = $this->region;

		return $this->model->where('is_user', 1)->where('dealer_id', $region)->select();
	}


    public function show($id) {

        $user = $this->getById($id);

        $result = ['status' => false];

        if ($user) {
        	Region::make_current($user->region_id);
        	
	        $countries = Country::all();
	        $currency = Currency::filterRegion()->first();
	        $s_statuses = ProductStatus::filterRegion()->where('is_s', 1)->get();
	        $p_statuses = ProductStatus::filterRegion()->where('is_p', 1)->get();

            $mobile_services = $user->get_mobile_services()->get();
            $services = $user->get_services2()->get();
            $stbs = $user->get_ps2()->get();

            $result = ['status' => true, 'data' => compact('user', 'countries', 'currency', 'mobile_services', 'services', 'stbs', 's_statuses', 'p_statuses')];
            
        }

        return $result;
    }

    public function showStatistics($id) {
       
        $user = $this->getById($id);

        $result = ['status' => false];

        if ($user) {
        	$currency = Currency::filterRegion()->first();

        	$result = ['status' => true, 'data' => compact('user', 'currency')];
        } 

        return $result;
    }

    public function showBalance($id) {

        $user = $this->getById($id);

        $result = ['status' => false];

        if ($user) {
        	$currency = Currency::filterRegion()->first();

        	$result = ['status' => true, 'data' => compact('user', 'currency')];
            
        } 

        return $result;
    }

    /**
     * @param  array
     * @return [type]
     */
    public function getUpdate(array $data) {

        $id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

        $item = null;
        
        if ($id) {
            $item = $this->getById($id);
        }

        $countries = Country::all();

        return ['user' => $item, 'countries' => $countries];
    }

    public function update($id = null, array $data) {

    	$arr = [];
        $message = null;
        $result = ['status' => false, 'message' => null];

        if ($id) {
            //update existing user
            $user = $this->getById($id);
        } else {
            //add new user
            $user = new User;
        }

        if ($user) {

            $arr['username']      = ($data['username']) ? $data['username'] : null;
            $arr['reg_date']      = (isset($data['reg_date']) && $data['reg_date']) ? $data['reg_date'] : null;
            $arr['email']         = ($data['email']) ? $data['email'] : null;
            $arr['name']          = ($data['name']) ? $data['name'] : null;
            $arr['flat']          = ($data['flat']) ? $data['flat'] : null;
            $arr['region']        = ($data['region']) ? $data['region'] : '';
            $arr['postcode']      = ($data['postcode']) ? $data['postcode'] : '';
            $arr['city']          = ($data['city']) ? $data['city'] : '';
            $arr['phone']         = ($data['phone']) ? $data['phone'] : null;
            $arr['mobile']        = ($data['mobile']) ? $data['mobile'] : null;
            $arr['gender']        = (isset($data['gender'])) ? $data['gender'] : null;
            $arr['activated']     = (isset($data['activated']) && $data['activated'] == "on") ? 1 : 0;
            $arr['is_diller']     = (isset($data['is_diller']) && $data['is_diller'] == "on") ? 1 : 0;
            $arr['percent']       = ($data['percent']) ? $data['percent'] : '';
            $arr['percent_first'] = ($data['percent_first']) ? $data['percent_first'] : '';

            if ($data['country']) {
                $country = Country::find($data['country']);
                $arr['country'] = $country->countryName;
            } else { 
                $arr['country'] = null;
            }

            if ($data['bith_date']) {
                $bdate = explode('-', $data['bith_date']);
                $arr['byear']  = $bdate[0];
                $arr['bmonth'] = $bdate[1];
                $arr['bday']   = $bdate[2];
            } else {
                $arr['byear']  = null;
                $arr['bmonth'] = null;
                $arr['bday']   = null;
            }

            if (isset($data['password']) && $data['password'] != null) {
                $password = User::hash_password($data['password']);

                $arr['password'] = $password;
            }

            $res = $user->saveHelper($arr);

            if ($res['errors']) {
                $message = $res['errors'];
            }

            if ($res['status']) {
                if (!isset($data['user_id'])) {
                    $user->code = $user->generate_code($this->region);

                    $user->save();
                }

                $message = trans('main.mics.success_responses.user updated');

                $result = ['status' => $res['status'], 'message' => $message];
            }
        }

        return $result;
    }

    //change user status
    public function changeStatus(array $ids) {

		$errors = '';
        $status = null;

        foreach ($ids as $id) {
            $item = $this->getById($id);

            $item->activated = ( $item->activated == 1 ? 0 : 1 );

            if ($result = $item->save()) {
                $errors .= ($result['errors'] ? $result['errors'] : '');

                $status = $item->activated;

            }
        }

        return ['status' => $status, 'errors' => $errors];
	}

    public function delete(array $ids) {

        $errors = [];
        $messages = [];
        $status = 1; 

        foreach ($ids as $id) {
            $user = User::filterRegion()->find($id);

            if ($user) {
                $code = $user->code;

                if ($result = $user->deleteHelper()) {
                    if ($result['errors']) {
                        $errors [$code]= $result['errors'];

                        $status = $result['status'];

                    } else $messages [$code]= $result['messages'];
                }
            }
        }

        return ['errors' => $errors, 'messages' => $messages, 'status' => $status];
    }

    public function restore_deleted(array $ids) {

    	$errors = [];
        $messages = [];
        $status = 1; 

        if (User::withTrashed()->filterRegion()->whereIn('id', $ids)->restore()) {
            $messages ['Restore users']= trans('main.mics.success_responses.users_restored', ['count' => count($ids)]);
        }

        return ['errors' => $errors, 'messages' => $messages, 'status' => $status];
    }

    public function addProduct(array $data) {

    	$user_id = $data['user_id'] ?? null;
    	$type = $data['type'] ?? null;
    	$products = [];
        $redraw_type = '';
        $status = false;

        if ($user_id && $type) {
            switch ($type) {

                case 'stb':
                    $products = Product::get_stbs()->get(); $redraw_type = 'stbs'; break;

                case 'service':
                    $products = Product::get_services()->get(); $redraw_type = 'services'; break;

                case 'mobile_service':
                    $products = Product::get_mobile_services()->get(); $redraw_type = 'mobile_services'; break;

                case 'goods':
                    $products = Product::get_goods()->get(); $redraw_type = 'goods'; break;
            }

            $status = true;
        }

        return ['status' => $status, 'data' => compact('products', 'type', 'user_id', 'redraw_type')];
    }

    public function updateProduct(array $data) {

    	$user_id = $data['user_id'] ?? null;
    	$product = $data['product'] ?? null;

    	if ($user_id && $product) {
    		if (User::updateProductHelper($user_id, $product)) {
                return true;
            }
    	}

        return false;
    }

    public function loadFilter(array $data) {

    	$type = $data['type'] ?? null;
    	$countries = [];
    	$regular_users = [];
    	$all_services = [];
    	$showcolumns = [];
    	$status = false;

        if ($type) {
            switch ($type) {
                case 'all': $showcolumns = [1,1,1,1,1,1,1,1,1,1]; break;

                case 'cash_payers': $showcolumns = [1,1,1,1,1,1,1,1,1,1]; break;
            }

            $status = true;

            $countries = Country::all();
            $regular_users = User::filterRegion()->get();
            $all_services = Product::get_services()->get();
        }

        return ['status' => $status, 'type' => $type, 'showcolumns' => $showcolumns, 'data' => compact('type', 'countries', 'regular_users', 'all_services')];
    }

    public function addCashPayer(array $data) {
    	$user_id = $data['user_id'] ?? null;

        if ($user_id) {
            $user = $this->getByid($user_id);

            if ($user) {
                $user->cash_payment_status = ($user->cash_payment_status == 1 ? 0 : 1);

                $user->save();

                return true;
            }
        }

        return false;
    }
}
