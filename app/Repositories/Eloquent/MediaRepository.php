<?php 
namespace App\Repositories\Eloquent;

use App\Repositories\BaseRepository;
use App\Repositories\Contracts\MediaInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\Tvoyo\User;
use App\Models\Product\Product;
use App\Models\Language;
use App\Models\Country;
use App\Models\Region;
use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryType;
use Config;
use Image;

class MediaRepository extends BaseRepository implements MediaInterface  {

	/**
	 * @param Discount
	 */
	public function __construct(Gallery $model, GalleryType $model_type) {
		$this->model = $model->filterRegion(false);
		$this->model_type = $model_type->filterRegion(false);
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

	public function update($id, array $attributes) {

		$discount = $this->model->findOrFail($id);

		$discount->update($attributes);

		return $discount;
	}

	public function delete($id) {

		return $this->model->find($id)->delete() ? true : false;
	}


    //Update image data for item

	public function update_img($model, $data) {

		$id = $data['id'] ?? null;
        $message = null;
        $language_id = $data['language_id'] ?? null;

        if (isset($data['action'])) {

            $item = $model->getById($id);

            if ($item) {
                $fname = $item->manage_img($data);
                return $fname;
            } 
        }

        return false;

	}

	//Open image form
	
	public function attach_image_form($model, $data) {

		$id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;
        $item = null;
        $translated = null;
        $language_id = null;

        if ($id) {

            $item = $model->getById($id);

            if(isset($data['language_id']) && method_exists($item, 'translated')) {

            	$language_id = $data['language_id'];
            	$translated = $item->translated()->where('language_id', $language_id)->select('id', 'img', 'created_at')->first();

            }
        }

        return ['item' => $item, 'translated' => $translated, 'language_id' => $language_id];

	}

	//Attach image(s) to item

	public function attach_images($model, $data) {

		$id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;
		$img = (isset($data['images']) && !empty($data['images'])) ? json_encode($data['images']) : null;
        $item = null;

		if ($id) {
            
            $item = $model->getById($id);

            if(isset($data['language_id']) && method_exists($item, 'translated')) {

            	$translated = $item->translated()->where('language_id', $data['language_id'])->first();

	            if ($translated) {

		            $siblings = $translated->siblings()->withNoImage()->get();

		            foreach ($siblings as $sibling) {
		                $sibling->img = $img;
		                $sibling->save();
		            }

		            $translated->img = $img;
		            $translated->save();
		        }

	        } elseif (!method_exists($item, 'translated')) {

	        	$item->img = $img;
	            $item->save();

	        }

            return true;
        }

        return false;

	}

	//Load attached image(s) for item

	public function load_attached_images($model, $data) {

		$id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;
        $item = null;
        $translated = null;
        $language_id = null;
        $image_names_arr = [];
        $gallery_items = [];
        $layout_grid_gallery = (isset($data['layout_grid_gallery']) ? $data['layout_grid_gallery'] : 'l3 m4 s12');

		if ($id) {

            $item = $model->getById($id);

            if(isset($data['language_id']) && method_exists($item, 'translated')) {

	            $translated = $item->translated()->where('language_id', $data['language_id'])->select('id', 'img', 'created_at')->first();

	            if ($translated) {

	            	$image_names_arr = json_decode($translated->img, 1) ?? [];
		            $gallery_items = $this->model->whereIn('img', $image_names_arr)->get();

	            }
	        } elseif (!method_exists($model, 'translated')) {
	        	$image_names_arr = json_decode($item->img, 1);
	        }
        }

        return ['item' => $item, 'translated' => $translated, 'gallery_items' => $gallery_items, 'layout_grid_gallery' => $layout_grid_gallery];

	}

	//Load gallery items for image(s) selection

	public function load_gallery_items($model, $data) {

		$id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;
		$gallery_type = null;
		$item = null;
        $translated = null;
        $current_menu_item_id = null;
        $layout_grid_gallery = (isset($data['layout_grid_gallery']) ? $data['layout_grid_gallery'] : 'l4 m6 s12');
        $gallery_items = [];
        
		if ($id) {

			$item = $model->getById($id);

			if(isset($data['language_id']) && method_exists($item, 'translated')) {

            	$translated = $item->translated()->where('language_id', $data['language_id'])->select('id', 'img', 'created_at')->first();
            }

        }

        if (isset($data['current_menu_item_id'])) {

             $gallery_type = $this->model_type->find($data['current_menu_item_id']);

        } elseif (isset($data['menu_item_name'])) {

             $gallery_type = $this->model_type->where('title', $data['menu_item_name'])->first();

        }

        if ($gallery_type) {

        	$gallery_items = $gallery_type->content()->get();
        	$current_menu_item_id = $gallery_type->id;

        }

        return ['status' => true, 'item' => $item, 'translated' => $translated, 'gallery_items' => $gallery_items, 'gallery_type' => $gallery_type, 'layout_grid_gallery' => $layout_grid_gallery];

	}

	//Remove image from item

	public function remove_img($model, $data) {

		$id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;
		$image_names_arr = [];

		if ($id && isset($data['img'])) {

            $item = $model->getById($id);

            if(isset($data['language_id']) && method_exists($item, 'translated')) {

            	$translated = $item->translated()->where('language_id', $data['language_id'])->first();

            	if ($translated && isset($translated->img)) {

		            $image_names_arr = json_decode($translated->img, 1);

		            if (($key = array_search($data['img'], $image_names_arr)) !== false) {

		                unset($image_names_arr[$key]);

		            }

		            $translated->img = json_encode($image_names_arr);
		            $translated->save();

		        }

	        } elseif (!method_exists($model, 'translated') && isset($model->img)) {

	        	$image_names_arr = json_decode($item->img, 1);

	            if (($key = array_search($data['img'], $image_names_arr)) !== false) {

	                unset($image_names_arr[$key]);
	                
	            }

	            $item->img = json_encode($image_names_arr);
	            $item->save();

	        }

            
            return true;
        }

        return false;

	}

}
