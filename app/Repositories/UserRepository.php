<?php 
namespace App\Repositories;

use App\Models\Tvoyo\User;
use Validator;

class UserRepository extends BaseRepository {

	/**
	 * Create a new MenuRepository instance.
	 *
	 * @param  App\Models\Comment $comment
	 * @return void
	 */


	public function __construct(User $user)
	{
		$this->model = $user;
	}

	public function validator(array $data)
    {
        return Validator::make($data, [
        	/*'code' => 'required',
            'title' => 'required|max:1000',
            'subtitle' => 'required|max:65535',
            'description' => 'required|max:4294967295',
            'status' => 'required',
            'price' => 'required',
            'duration' => 'required',*/
        ]);
    }

	/**
	 * Get service collection.
	 *
	 * @param  int  $n
	 * @return Illuminate\Support\Collection
	 */
	public function all($n = null)
	{
		if ($n) {
			return $this->model->paginate($n);
		}
		return $this->model->all();
		
	}

	public function new_user($content) {

		$service = new Service;
	
		if(isset($content['code'])) $service->code = $content['code'];
		if(isset($content['title'])) $service->title = $content['title'];
		if(isset($content['subtitle'])) $service->subtitle = $content['subtitle'];
		if(isset($content['description'])) $service->description = $content['description'];
		
		if(isset($content['language_id'])) $service->language_id = $content['language_id'];
		if(isset($content['duration'])) $service->duration = $content['duration'];
		if(isset($content['price'])) $service->price = $content['price'];
		if(isset($content['created_at'])) {
			$format1_tms = strtotime($content['created_at']);
            $new_date_format = date('Y-m-d H:i:s', $format1_tms);
			$service->created_at = $new_date_format;
		}
		if(isset($content['updated_at'])) {
			$format1_tms = strtotime($content['updated_at']);
            $new_date_format = date('Y-m-d H:i:s', $format1_tms);
			$service->updated_at = $new_date_format;
		} 
		if(isset($content['status'])) $service->status = $content['status'];
		$service->save();
		//set tags
		if (isset($content['tags'])) {
			$tag_ids_arr = [];
			foreach ($content['tags'] as $tag) {
				if (is_numeric($tag)) {
					$tag_ids_arr[] = (int)$tag;
				} else {
					$tag1 = Tag::where('description', $tag)->first();
					if(!$tag1) {
						$tag1 = new Tag;
						$tag1->description = $tag;
						$tag1->save();
					}
					$tag_ids_arr[] = $tag1->id;
				}
			}
			$service->tags()->sync($tag_ids_arr, true);

		}
		
		$service->save();
		$image = null;
		if (isset($content['crop-editor-status']) && $content['crop-editor-status'] == 1) {
			if ($content['image'] != "undefined" && $content['image'] != null && $content['image'] != '') {
				$image = $content['image'];
			} else {
				$image = $service->pic_1;
			}

			if (file_exists($service->pic_1)) {
				unlink(str_replace("http://narikala.ge/","",$service->pic_1));
			}
			if (file_exists($service->pic_2)) {
				unlink(str_replace("http://narikala.ge/","",$service->pic_2));
			}
			
			$path = ['original' => 'img/services/image_'.$service->id.'.jpeg', 'thumbnail' => 'img/services/thumbnail_'.$service->id.'.jpeg'];
			$cropped = $this->crop_saved($image, (array)json_decode($content['image_crop_coordinates']), $path);

			if ($cropped) {
				$service->pic_1 = 'http://narikala.ge/img/services/image_'.$service->id.'.jpeg';
				$service->pic_2 = 'http://narikala.ge/img/services/thumbnail_'.$service->id.'.jpeg';
				$service->save();
			}

		} elseif ($content['crop-editor-status'] == 0) {


		}


		//if(isset($content['pic_1'])) $service->pic_1 = $content['pic_1'];


		return $service->save();
	}

	/**
	 * Update a language.
	 *
	 * @param  string $commentaire
	 * @param  int    $id
	 * @return void
	 */
 	public function update($content, $id) {

 		//dd($content);
		
		$service = $this->getById($id);
		if ($service->id) {
			if(isset($content['code'])) $service->code = $content['code'];
			if(isset($content['title'])) $service->title = $content['title'];
			if(isset($content['subtitle'])) $service->subtitle = $content['subtitle'];
			if(isset($content['description'])) $service->description = $content['description'];
			
			if(isset($content['language_id'])) $service->language_id = $content['language_id'];
			if(isset($content['duration'])) $service->duration = $content['duration'];
			if(isset($content['price'])) $service->price = $content['price'];
			if(isset($content['created_at'])) {
				$format1_tms = strtotime($content['created_at']);
                $new_date_format = date('Y-m-d H:i:s', $format1_tms);
				$service->created_at = $new_date_format;
			}
			if(isset($content['updated_at'])) {
				$format1_tms = strtotime($content['updated_at']);
                $new_date_format = date('Y-m-d H:i:s', $format1_tms);
				$service->updated_at = $new_date_format;
			} 
			if(isset($content['status'])) $service->status = $content['status'];
			//set tags
			if (isset($content['tags'])) {
				$tag_ids_arr = [];
				foreach ($content['tags'] as $tag) {
					if (is_numeric($tag)) {
						$tag_ids_arr[] = (int)$tag;
					} else {
						$tag1 = Tag::where('description', $tag)->first();
						if(!$tag1) {
							$tag1 = new Tag;
							$tag1->description = $tag;
							$tag1->save();
						}
						$tag_ids_arr[] = $tag1->id;
					}
				}
				$service->tags()->sync($tag_ids_arr, false);

			}

			$service->save();
			$image = null;
			if (isset($content['crop-editor-status']) && $content['crop-editor-status'] == 1) {
				if ($content['image'] != "undefined" && $content['image'] != null && $content['image'] != '') {
					$image = $content['image'];
				} else {
					$image = $service->pic_1;
				}

				/*$image1 = str_replace("http://narikala.ge/","",$service->pic_1);
				$thumbnail1 = str_replace("http://narikala.ge/","",$service->pic_2);

				if (file_exists($image1)) {
					unlink($image1);
				}
				if (file_exists($thumbnail1)) {
					unlink($thumbnail1);
				}*/
				
				$path = ['original' => 'img/services/image_'.$service->id.'.jpeg', 'thumbnail' => 'img/services/thumbnail_'.$service->id.'.jpeg'];
				$cropped = $this->crop_saved($image, (array)json_decode($content['image_crop_coordinates']), $path);

				if ($cropped) {
					$service->pic_1 = 'http://narikala.ge/img/services/image_'.$service->id.'.jpeg';
					$service->pic_2 = 'http://narikala.ge/img/services/thumbnail_'.$service->id.'.jpeg';
					$service->save();
				}

			} elseif ($content['crop-editor-status'] == 0) {


			}


			//if(isset($content['pic_1'])) $service->pic_1 = $content['pic_1'];


			return $service->save();
		}
		return false;
	}

	public function crop_saved($image_src, $dimensions, $path) {
		//dd($dimensions);

		//$imagedata = file_get_contents($image_src);

		list ($this->width, $this->height, $type) = getimagesize($image_src);



        switch ($type) {
            case IMAGETYPE_GIF  :
                $this->image = imagecreatefromgif($image_src);
                break;
            case IMAGETYPE_JPEG :
                $this->image = imagecreatefromjpeg($image_src);
                break;
            case IMAGETYPE_PNG  :
                $this->image = imagecreatefrompng($image_src);
                break;
            default             :
                throw new InvalidArgumentException("Image type $type not supported");
        }

        $thumbnail_dimensions = [1024, 576];

        if ($dimensions['width'] < 1024) {
        	$thumbnail_dimensions = [$dimensions['width'], $dimensions['height']];
        }

        $dest = imagecreatetruecolor($dimensions['width'], $dimensions['height']);
        $thumbnail = imagecreatetruecolor($thumbnail_dimensions[0], $thumbnail_dimensions[1]);

        // Copy
		imagecopy($dest, $this->image, 0, 0, $dimensions['x'], $dimensions['y'], $dimensions['width'], $dimensions['height']);
		imagecopyresampled($thumbnail, $dest, 0, 0, 0, 0, $thumbnail_dimensions[0], $thumbnail_dimensions[1], $dimensions['width'], $dimensions['height']);

        header('Content-Type: image/jpeg');
		imagejpeg($dest, $path['original'], 100);
		imagejpeg($thumbnail, $path['thumbnail'], 100);

		//imagedestroy($dest);
		//imagedestroy($this->image);

		return ['cropped'  => $dest, 'thumbnail' => $thumbnail];

	}

	public function create($content) {
		
		//$service = new Service;

        return $this->new_service($content);
	}

	public function delete($id) {
		$service = $this->getById($id);

		$image = str_replace("http://narikala.ge/","",$service->pic_1);
		$thumbnail = str_replace("http://narikala.ge/","",$service->pic_2);

		if (file_exists($image)) {
			unlink($image);
		}
		if (file_exists($thumbnail)) {
			unlink($thumbnail);
		}

		$service->tags()->delete();
		return $service->delete();

	}

	public function getById($id) {
		$user = $this->model->find($id);
		return $user;
	}

	public function statusChange($status, $id) {
		$user = $this->getById($id);
		$user->activated = $status;
		return $user->save();
	}

}