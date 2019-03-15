<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityLog extends Model  {

    //use SoftDeletes;
    protected $default_region_id = 1;
    protected $region = null;
	protected $fillable = [
        'id', 'region_id', 'log_time', 'ip', 'user_id', 'module_id', 'permission_id', 'type', 'message', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $table = 'admin_activity_log';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->region = session('region_id') ? session('region_id') : $this->default_region_id;
        $this->connection = 'mysql_admin';
    }

    public function scopeFilterRegion($query, $flag = true) {
        $region = $this->region;
        if ($flag) {
            return $query->where('region_id', $region);
        }
        return $query;
    }

	public function user()
    {
        return $this->belongsTo('App\Models\Tvoyo\User', 'user_id');
    }

    public function permission()
    {
        return $this->belongsTo('App\Models\Permission', 'permission_id');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'region_id');
    }
    public function deleteHelper() {
        $activitylog = [];
        $errors = [];
        $messages = [];
        $activitylog ['local']= $this;
        
        foreach ($activitylog as $k => $log) {
            if ($log) {
                $name = $log->id;
                if (!$log->delete()) {
                    $errors [$name]= $res['message'];
                } else $messages [$name]= 'Activity log deleted';
            }
        }

        $status = (count($errors) > 0 ? false : true);

        $error_str = '';
        foreach ($errors as $reg => $error) {
            $error_str .= '<div class="error_holder-dialog">'.$error.'</div>';
        }

        $messsage_str = '';
        foreach ($messages as $reg => $message) {
            $messsage_str .= '<div class="success_holder-dialog">'.$message.'</div>';
        }

        return ['status' => $status, 'errors' => $error_str, 'messages' => $messsage_str];
    }

    public function saveHelper($data) {
        $activityLogs = [];
        $errors = [];
        $status = true;
        $activityLogs ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);
            foreach ($activityLogs as $connection => $log) {
                if ($log) {
                    foreach ($log->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $log->$attr = $data[$attr];
                        }
                    }

                    if ($id) {
                        $log->id = $id;
                    }

                    if(!$log->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $log->id;
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

    public static function rules ($id=0, $merge=[]) {
        return array_merge(
            [
                'name' => 'required|max:10',
                'display_name' => 'max:512',
            ], 
            $merge);
    }
}