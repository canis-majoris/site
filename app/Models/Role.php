<?php 
namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {
	protected $connection = 'mysql_admin';

	protected $fillable = [
        'id', 'name', 'display_name', 'description'
    ];

    public function scopeFilterRegion($query, $flag = true) {
        return $query;
    }

    //protected $dates = ['deleted_at'];

	public function users()
    {
        return $this->belongsToMany('App\Models\User', 'role_user');
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Models\Permission', 'permission_role');
    }

    public function deleteHelper() {
        $roles = [];
        $errors = [];
        $roles ['local']= $this;
        
        foreach ($roles as $connection => $role) {
            if ($role) {
                $role->permissions()->detach();
                $role->users()->detach();
                if (!$role->delete()) $errors [$op->code]= $res['message'];
            }
        }

        $status = (count($errors) > 0 ? false : true);

        $error_str = '';
        foreach ($errors as $reg => $error) {
            $error_str .= '<div class="error_holder-dialog">('.$reg.') '.$error.'</div>';
        }

        return ['status' => $status, 'errors' => $error_str];
    }

    public function saveHelper($data) {
        $roles = [];
        $errors = [];
        $status = true;
        $roles ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);
            foreach ($roles as $connection => $role) {
                if ($role) {
                    foreach ($role->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $role->$attr = $data[$attr];
                        }
                    }

                    if ($id) {
                        $role->id = $id;
                    }

                    if(!$role->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $role->id;
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
                'name' => 'required|max:15',
                'display_name' => 'max:512',
            ], 
            $merge);
    }
}