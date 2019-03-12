<?php 
namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\TransactionInterface;
use App\Models\Region;
use App\Models\CartuTransaction;
use App\Models\Paypal\PaypalTransaction;
use Validator;

class TransactionRepository extends BaseRepository implements TransactionInterface  {

	/**
	 * @param Discount
	 */
	public function __construct(CartuTransaction $model_cartu, PaypalTransaction $model_paypal) {
		$this->model_cartu = $model_cartu->filterRegion(true);
		$this->model_paypal = $model_paypal->filterRegion(true);
		$this->region = session('region_id') ? session('region_id') : $this->default_region_id;
	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function validator(array $data)
    {
        return Validator::make($data, []);
    }

	/**
	 * @return [type]
	 */
	public function getAll() {

	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function getById($id) {

	}

	public function getByIds(array $ids) {

	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function create(array $data) {

	}

	/**
	 * @param  [type]
	 * @param  array
	 * @return [type]
	 */
	public function update($id, array $data) {

	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function delete($id) {
	}

	public function filterData(array $data) {

	}

	/**
	 * @return [type]
	 */
	public function getDiscountUsers() {

	}

}
