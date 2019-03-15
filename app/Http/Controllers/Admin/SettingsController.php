<?php
namespace App\Http\Controllers;

use App\DataTables\Settings\SettingsDataTable;
use App\Repositories\Eloquent\MediaRepository;
use App\Repositories\Eloquent\LanguageRepository;
use App\Repositories\Eloquent\SettingsRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Region;
use App\Models\Gallery\Gallery;
use App\Models\Gallery\GalleryType;
use App\Models\Settings\SettingsType;
use App\Models\Settings\Settings;
use App\Models\Settings\SettingsLanguage;
use Validator;
use Config;

class SettingsController extends MediaController {

    public $settings;
    public $language;

    public function __construct( MediaRepository $media, SettingsRepository $settings, LanguageRepository $language ) {
        parent::__construct($media);
        $this->item = $settings;
        $this->view_directory = 'settings';
        $this->settings = $settings;
        $this->language = $language;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index( SettingsDataTable $dataTable ) {

        $languages = $this->language->getAll();
        $regions = Region::get();
        $menu_items = [];

        if ($regions->count()) {

            $menu_items = $this->settings->getTypes();

        }
        
        $language_id = $languages->first()->id;

        return $dataTable->render('settings.index', compact('menu_items', 'languages', 'language_id'));
    }

    public function update(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();
            $result = $this->settings->getUpdate($data);
            $current_menu_item_id = $result['settings_type'] != null ? $result['settings_type']->id : null;
            $view = View::make('settings.manage.form', $result)->render();

            return response()->json(['success' => true, 'status' => 1, 'message' => 'new content', 'html' => $view, 'response' => ['current_menu_item_id' => $current_menu_item_id]]);
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong..']);

    }

    public function save(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();
            $result = [];
            $id = (isset($data['id']) && !empty($data['id'])) ? $data['id'] : null;

            $validator = Validator::make($data, Settings::rules($id));
            
            if($validator->fails()){

                return response()->json(['success' => false, 'status' => 0, 'message' => $validator->errors()->all()]);

            } else {

                $result = $this->settings->update($id, $data);
                return response()->json($result);

            }
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'something went wrong']);
    }

    public function delete(Request $request) {

        if ($request->ajax()) {

            $ids = $request->get('ids');
            $message = 'content deleted';

            if (count($ids) > 1) {

                $message = count($ids).' contents deleted';

            }

            $result = $this->settings->delete($ids);

            return response()->json(['success' => true, 'status' => $result['status'], 'messages' => $result['messages'], 'errors' => $result['errors']]);
        }

        return response()->json(['success' => true, 'status' => 1, 'message' => 'could not delete settings..']); 
    }

    public function reorder(Request $request) {

        if ($request->ajax()) {

            $data = $request->all();

            $result = $this->settings->reorder($data);

            if ($result) {
                
                return response()->json(['success' => true, 'status' => 1, 'message' => 'settings reordered']);
            }
            
        }

        return response()->json(['success' => false, 'status' => 0, 'message' => 'could not reorder settings..']);
    }

    public function changeStatus(Request $request) {

        // $json_location_arr = json_decode('{"296":{"lat":40.7127753,"lng":-74.0059728},"297":{"lat":40.7127753,"lng":-74.0059728},"298":{"lat":40.6781784,"lng":-73.9441579},"299":{"lat":40.7127753,"lng":-74.0059728},"300":{"lat":40.7127753,"lng":-74.0059728},"301":{"lat":40.81201710000001,"lng":-74.1243063},"302":{"lat":40.7127753,"lng":-74.0059728},"303":{"lat":40.72815749999999,"lng":-74.07764170000002},"304":{"lat":40.7127753,"lng":-74.0059728},"305":{"lat":40.6781784,"lng":-73.9441579},"306":{"lat":40.7127753,"lng":-74.0059728},"307":{"lat":41.7151377,"lng":44.82709599999998},"308":{"lat":40.7127753,"lng":-74.0059728},"309":{"lat":40.7127753,"lng":-74.0059728},"310":{"lat":40.7127753,"lng":-74.0059728},"311":{"lat":40.7127753,"lng":-74.0059728},"312":{"lat":40.7127753,"lng":-74.0059728},"313":{"lat":40.7127753,"lng":-74.0059728},"314":{"lat":40.7127753,"lng":-74.0059728}}', true);


        // //var_dump($json_location_arr); die;

        // $languages = Language::where('region_id', 2)->get();

        // $settings_t = Settings::where('region_id', 2)->where('settings_type_id', 70)->get();

        // foreach ($settings_t as $sett) {

        //     if (isset($json_location_arr[$sett->id])) {

        //         $translated = $sett->translated()->get();

        //         foreach ($translated as $tr) {
        //             $text = json_decode($tr->text, true);
        //             $text['lat'] = $json_location_arr[$sett->id]['lat'];
        //             $text['lng'] = $json_location_arr[$sett->id]['lng'];
        //             $tr->text = json_encode($text);
        //             $tr->save();
        //         }
        //     }
        // }

        // die;

        // $dealers = Dealer::filterRegion()->get();
        // $languages = Language::filterRegion()->get();

        // $settings_t = Settings::filterRegion(false)->whereNull('ind')->where('settings_type_id', 69)->get();
        // foreach ($settings_t as $sett) {
        //     $sett->deleteHelper();
        // }

        // foreach ($dealers as $dealer) {
        //     $d_user = User::find($dealer->user_id);
        //     if ($d_user && $d_user->city && $d_user->flat) {
        //         $data = [
        //             'city' => $d_user->city,
        //             'flat' => $d_user->flat,
        //             'name' => $d_user->name,
        //             'phone' => $d_user->phone,
        //             'email' => $d_user->email,
        //         ];

        //         $mp = new Settings;
        //         $mp->region_id = $this->region; 
        //         $mp->status = 1; 
        //         $mp->settings_type_id = 69;
        //         $mp->save();

        //         $update_arr['text'] = json_encode($data);
        //         $update_arr['name'] = $d_user->city . ' (' . $d_user->name . ')';
        //         $update_arr['status'] = 1;
        //         $update_arr['settings_id'] = $mp->id;
        //         $update_arr['region_id'] = $this->region;

        //         foreach ($languages as $lang) {
        //             $update_arr['language_id'] = $lang->id;

        //             $translated = new SettingsLanguage;
        //             $translated->saveHelper($update_arr);
        //             $mp->translated()->save($translated);
        //         }
        //     }
        // }


        if ($request->ajax()) {

            $ids = $request->get('ids');
            $message = 'status updated';

            $result = $this->settings->changeStatus($ids);

            if ($result['errors']) {

                $message = $result['errors'];

            }

            return response()->json(['success' => true, 'status' => $result['status'], 'message' => $message]);

        }

        return response()->json(['success' => false, 'error' => true, 'status' => $result['status'], 'message' => 'could not update status..']);

    }

}