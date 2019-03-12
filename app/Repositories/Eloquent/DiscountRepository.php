<?php 
namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\DiscountInterface;
use App\Models\Region;
use App\Models\Discount\Discount;
use Validator;

class DiscountRepository extends BaseRepository implements DiscountInterface  {

	/**
	 * @param Discount
	 */
	public function __construct(Discount $model) {
		$this->model = $model->filterRegion(true);
		$this->region = session('region_id') ? session('region_id') : $this->default_region_id;
	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function validator(array $data)
    {
        return Validator::make($data, [
        	'code' => 'required',
            'limit1' => 'max:9999999999',
            'date_start' => 'required',
            'date_end' => 'required',
            'type' => 'required',
            'percent' => 'between:0,100',
            'type_discount' => 'required',
        ]);
    }

	/**
	 * @return [type]
	 */
	public function getAll() {
		return $this->model->get();
	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function getById($id) {
		return $this->model->find($id);
	}

	public function getByIds(array $ids) {
		return $this->model->whereIn('id', $ids)->get();
	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function create(array $data) {

		$arr = $this->filterData($data);

        $validator = $this->validator($arr);
        if($validator->fails()){
            $result = ['errors' => $validator->errors()->all()];
        } else {
        	$discount = new Discount;
        	$result = $discount->saveHelper($arr);
        }

        $result['status'] = 1;

		return $result;
	}

	/**
	 * @param  [type]
	 * @param  array
	 * @return [type]
	 */
	public function update($id, array $data) {

		$arr = $this->filterData($data);

        $validator = $this->validator($arr);
        if($validator->fails()){
            $result = ['errors' => $validator->errors()->all()];
        } else {
        	$discount = $this->model->findOrFail($id);
        	$result = $discount->saveHelper($arr);
        }

        $result['status'] = 1;

		return $result;
	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function delete($id) {
		return $this->model->find($id)->delete() ? true : false;
	}

	public function filterData(array $data) {

		$arr = [];
		
		$arr['code']                = ($data['code']) ? $data['code'] : null;
        $arr['limit1']              = ($data['limit']) ? $data['limit'] : 0;
        $arr['date_start']          = ($data['date_start']) ? $data['date_start'] : null;
        $arr['date_end']            = ($data['date_end']) ? $data['date_end'] : null;
        $arr['type']                = ($data['type']) ? $data['type'] : null;
        $arr['percent']             = ($data['percent']) ? trim($data['percent'], ' %') : null;
        $arr['type_discount']       = ($data['type_discount']) ? $data['type_discount'] : null;
        $arr['status']              = (isset($data['status']) && $data['status'] == 'on') ? 1 : 0;
        $arr['d_products']          = (isset($data['d_products'])) ? $data['d_products'] : null;
        $arr['d_percents']          = [];

        if (isset($data['d_percents'])) {
            foreach ($data['d_percents'] as $p_id => $perc) {
                $arr['d_percents'][$p_id] = trim($perc, ' %');
            }
        }

        return $arr;
	}

	/**
	 * @return [type]
	 */
	public function getDiscountUsers() {
		return $this->model->users()->filterRegion(true)->where('users.is_user', 1)->get();
	}

}
