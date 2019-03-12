<?php 
namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\TextInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\Tvoyo\User;
use App\Models\Product\Product;
use App\Models\Language;
use App\Models\Text\Text;
use App\Models\Text\TextType;
use App\Models\Text\TextLanguage;
use App\Models\Region;
use Config;
use Image;

class TextRepository extends BaseRepository implements TextInterface  {

	/**
	 * @param Discount
	 */
	public function __construct(Text $model, TextType $model_type, TextLanguage $model_language, Language $language) {
		$this->model 		  = $model->filterRegion();
		$this->model_type	  = $model_type->filterRegion();
		$this->model_language = $model_language->filterRegion();
		$this->language 	  = $language->filterRegion();
		$this->region = session('region_id') ? session('region_id') : $this->default_region_id;
	}

	/**
	 * @return [type]
	 */
	public function getAll() {
		return $this->model->get();
	}

	/**
	 * @return [type]
	 */
	public function getTranslated() {
		return $this->model_language->get();
	}

	/**
	 * @return [type]
	 */
	public function getTypes() {
		return $this->model_type->get();
	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function getById($id) {

		return $this->model->find($id);
	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function getCount() {

		return $this->model->count();
	}

	/**
	 * @param  [type]
	 * @return [type]
	 */
	public function getSelect($id, array $select_array) {

		return $this->model->select($select_array)->find($id);
	}

	public function getCreate(array $attributes) {

	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function createNew($item_type, array $arr, array $update_arr) {

		$ind = 1;
        if ($this->getCount() > 0) {
            $ind = $this->model->orderBy('ind', 'desc')->first()->ind + 1;
        }

        $arr['ind'] = $ind;

        $languages = $this->language->get();
        $item = new Text;
        $result = $item->saveHelper($arr);
        $item_type->items()->save($item);

        foreach ($languages as $lang) {

            $update_arr['language_id'] = $lang->id;
            $translated = new TextLanguage;
            $translated->saveHelper($update_arr);
            $item->translated()->save($translated);

        }

        return ['result' => $result, 'item' => $item];
	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function updateExisting($id, $language_id, array $arr, array $update_arr) {

		$languages = $this->language->get();
		$item = $this->getByid($id);
        $result = $item->saveHelper($arr);
        $translated = $item->getTranslatedByLanguageId($language_id, ['*']);

        if ($translated) {

            $translated->saveHelper($update_arr);

        } else {

            foreach ($languages as $lang) {

                $update_arr['language_id'] = $lang->id;
                $translated = new TextLanguage;
                $translated->saveHelper($update_arr);
                $item->translated()->save($translated);

            }

        }

        return ['result' => $result, 'item' => $item];
	}

	/**
	 * @param  array
	 * @return [type]
	 */
	public function getUpdate(array $data) {

		$id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;
		$language_id = null;
        $item = null;
        $translated = null;
        $item_type = null;
        $uploaded_img = null;

		if (isset($data['language_id'])) {

            $language_id = $data['language_id'];

            if ($id) {
                $item = $this->getSelect($id, ['id', 'texts_type_id', 'ind', 'date', 'name_trans', 'score', 'source', 'tags', 'created_at']);
                $item_type = $item->type;
                $translated = $item->getTranslatedByLanguageId($language_id, ['language_id', 'name', 'seo_title', 'seo_keywords', 'seo_description', 'img', 'alt', 'text', 'short_text', 'watch', 'video', 'created_at', 'updated_at']);

                $uploaded_img = $item->translated()->where('img', '!=', '')->whereNotNull('img')->count();

            } else {
                if (isset($data['current_menu_item_id'])) {
                    $item_type = $this->getTypes()->find($data['current_menu_item_id']);
                }
            }
        }

        return ['text' => $item, 'translated' => $translated, 'texts_type' => $item_type, 'language_id' => $language_id, 'uploaded_img' => $uploaded_img];
	}

	//Manage Text Item
	/**
	 * @param  array
	 * @return [type]
	 */

	public function update($id = null, array $data) {

        $arr = [];
        $update_arr = [];
        $language_id = $data['language_id'];
        $images = null;
        $message = null;
        $result = null;

        $arr['score']                  = (isset($data["score"]) ? $data["score"] : null);
        $arr['source']                 = (isset($data["source"]) ? $data["source"] : null);
        $arr['tags']                   = (isset($data["tags"]) ? json_encode($data["tags"]) : "");
        $arr['name_trans']             = (isset($data["name_trans"]) ? $data["name_trans"] : null);
        $update_arr['watch']           = (isset($data["watch"]) && $data["watch"] == "on") ? 1 : 0;
        $update_arr['video']           = isset($data["video"]) ? $data["video"] : null;
        $update_arr['text']            = isset($data["text"]) ? $data["text"] : null;
        $update_arr['alt']             = isset($data["alt"]) ? $data["alt"] : null;
        $update_arr['seo_title']       = isset($data["seo_title"]) ? $data["seo_title"] : null;
        $update_arr['seo_keywords']    = isset($data["seo_keywords"]) ? json_encode($data["seo_keywords"]) : "";
        $update_arr['seo_description'] = isset($data["seo_description"]) ? $data["seo_description"] : null;
        $update_arr['img']             = isset($data["text_image_name"]) ? trim($data["text_image_name"]) : null;
        $update_arr['name']            = htmlspecialchars($data["name"]);
        $update_arr['short_text']      = htmlspecialchars($data["short_text"]);

        if (isset($data['gallery_images'])) {

            $images = json_encode($data['gallery_images']);

        } elseif (isset($data["image_name"]) ) {

            $images = json_encode([$data["image_name"]]);

        }

        $update_arr['img'] = $images;

        if (!isset($data['current_menu_item_id']) && isset($data['text_id'])) {

            $item_type = $this->getById($data['text_id'])->type;

        } elseif (isset($data['current_menu_item_id'])) {

            $item_type = $this->getTypes()->find($data['current_menu_item_id']);

        }

        if (isset($data['text_id']) && $data['text_id'] != null) {

            //update existing content

        	$result = $this->updateExisting($data['text_id'], $language_id, $arr, $update_arr);

            $id = $result['item']->id;
            $message = 'Text item updated';

        } else {

            //add new content
            
            $result = $this->createNew($item_type, $arr, $update_arr);

            $id = $result['item']->id;
            $message = 'New text item created';
        }

        if (isset($result['result']['errors']) && !empty($result['result']['errors'])) {

            $message = $result['result']['errors'];

        }

        return ['success' => $result['result']['status'], 'status' => $result['result']['status'], 'message' => $message, 'result' => ['id' => $id]];
	}

	public function delete(array $ids) {

        $errors = [];
        $messages = [];
        $status = 1; 

        foreach ($ids as $id) {
            $text = $this->getById($id);
            if ($text) {
                $translated = $text->translated()->first();
                $name = $translated->name . ( $translated->short_text ? ' (' . $translated->short_text . ')' : null );

                if ($result = $text->deleteHelper()) {
                    if ($result['errors']) {
                        $errors [$name]= $result['errors'];
                        $status = $result['status'];
                    } else $messages [$name]= $result['messages'];
                }
            }
        }

        return ['errors' => $errors, 'messages' => $messages, 'status' => $status];
	}

	public function reorder(array $data) {

		$tmp_arr = [];

        if (isset($data['current_menu_item_id']) && $data['current_menu_item_id'] != '' && isset($data['diff'])) {

            $item_type = $this->getTypes()->find($data['current_menu_item_id']);
            $i = 0;
            $for_ids = array_keys($data['diff']);
            $item = $item_type->items()->whereIn('ord', $for_ids)->orderBy('id', 'asc')->get();

            foreach ($item as $s) {

                $i++;
                $s->ord = $data['diff'][$s->ord];
                $tmp_arr[$s->ord] = $data['diff'][$s->ord];
                $s->save();

            }

            return true;
       
        }

        return false;
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
