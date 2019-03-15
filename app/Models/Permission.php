<?php 
namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission {
	protected $default_region_id = 1;

	protected $connection = 'mysql_admin';

	protected $fillable = [
        'id', 'name', 'display_name', 'description'
    ];

    public function scopeFilterRegion($query, $flag = true) {
        return $query;
    }

	public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'permission_role');
    }

    public function deleteHelper() {
        $permissions = [];
        $errors = [];
        $messages = [];
        $permissions ['local']= $this;
        
        foreach ($permissions as $k => $permission) {
            if ($permission) {
                $permission->roles()->detach();
                if (!$permission->delete()) {
                    $errors [$permission->display_name]= $res['message'];
                } else $messages [$permission->display_name]= 'Permission <b>'.$permission->display_name.'</b> deleted';
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

    public function saveHelper($data, $role) {
        $permissions = [];
        $errors = [];
        $status = true;
        $permissions ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);
            foreach ($permissions as $connection => $permission) {
                if ($permission) {
                    foreach ($permission->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $permission->$attr = $data[$attr];
                        }
                    }

                    if ($id) {
                        $permission->id = $id;
                    }

                    if(!$permission->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $permission->id;
                        if ($role) {

                        	$role->permissions()->sync([$permission->id], false);

                        }
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
                'name' => 'required|max:30',
                'display_name' => 'max:512',
            ], 
            $merge);
    }
}