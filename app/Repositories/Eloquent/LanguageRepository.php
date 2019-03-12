<?php 
namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\LanguageInterface;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Region;
use Config;

class LanguageRepository extends BaseRepository implements LanguageInterface  {

	/**
	 * @param Discount
	 */
	public function __construct(Language $model) {
		$this->model = $model->filterRegion(true);
		$this->region = session('region_id') ? session('region_id') : $this->default_region_id;
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

	/**
	 * @param  array
	 * @return [type]
	 */
	public function create(array $attributes) {

		return $this->model->create($attributes);
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

		return ['language' => $item];
	}

	public function update($id = null, array $data) {

		$arr = [];
        $message = null;
        $result = ['success' => false, 'status' => false, 'message' => null];

        if ($id) {
            //update existing language
            $language = $this->getById($id);
        } else {
            //add new language
            $language = new Language;
        }

        if ($language) {

        	$arr['language']      = ($data['language']) ? $data['language'] : null;
            $arr['language_code'] = ($data['language_code']) ? $data['language_code'] : null;
            $arr['watch']         = (isset($data['watch']) && $data['watch'] == 'on') ? 1 : 0;

            $res = $language->saveHelper($arr);

            if ($res['errors']) {
                $message = $res['errors'];
            }

            if ($res['status']) {

                $message = trans('main.mics.success_responses.language updated');

                $result = ['success' => true, 'status' => $res['status'], 'message' => $message];
            }
        }

		return $result;
	}

	public function delete(array $ids) {

		$errors = [];
        $messages = [];
        $status = 1; 

        foreach ($ids as $id) {
            $language = $this->getById($id);

            if ($language) {
                $name = $language->language;

                if ($result = $language->deleteHelper()) {
                    if ($result['errors']) {
                        $errors [$name]= $result['errors'];

                        $status = $result['status'];

                    } else $messages [$name]= $result['messages'];
                }
            }
        }

        return ['errors' => $errors, 'messages' => $messages, 'status' => $status];
	}

	public function changeStatus(array $ids) {

		$errors = '';
        $status = null;

        foreach ($ids as $id) {

            $item = $this->getById($id);
            $item->watch = ( $item->watch == 1 ? 0 : 1 );

            if ($result = $item->save()) {

                $errors .= ($result['errors'] ? $result['errors'] : '');
                
                $status = $item->watch;

            }
        }

        return ['status' => $status, 'errors' => $errors];
	}

}
