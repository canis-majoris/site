<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zizaco\Entrust\Traits\EntrustUserTrait;
// use App\Models\ImageHelper;


class Admin extends Authenticatable
{
    //use SoftDeletes;
    use EntrustUserTrait;

    //protected $connection = 'mysql_admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'activated', 'email', 'password', 'phone', 'city', 'country', 'region', 'salt', 'status'
    ];

    protected $dates = ['deleted_at'];
    

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function region() 
    {
      return $this->belongsTo('App\Models\Region');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_user');
    }

    public static function upload_img($data, $id = null) {
        $data['directory'] = 'admins/avatars';
        return ImageHelper::upload($data, $id, $data['size']);
    }

    public function followers() {
        return $this->belongsToMany(self::class, 'followers', 'follows_id', 'user_id')->withTimestamps();
    }

    public function follows() {
        return $this->belongsToMany(self::class, 'followers', 'user_id', 'follows_id')->withTimestamps();
    }

    public function follow($userId) 
    {
        $this->follows()->attach($userId);
        return $this;
    }

    public function unfollow($userId)
    {
        $this->follows()->detach($userId);
        return $this;
    }

    public function isFollowing($userId) 
    {
        return (boolean) $this->follows()->where('follows_id', $userId)->first(['users.id']);
    }

    public function delete_img($data) {
        $this->avatar = null;
        $this->save();
        $data['direcotry'] = 'admins/avatars';
        return ImageHelper::delete($data, $this->id);
    }

    public function deleteHelper() {

        $users = [];
        $errors = [];
        $messages = [];
        $users ['local']= $this;
        
        foreach ($users as $k => $user) {
            if ($user) {
                $user->roles()->detach();
                if (!$user->delete()) {
                    $errors [$user->username]= $res['message'];
                } else $messages [$user->username]= 'User <b>'.$user->username.'</b> deleted';
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
        $users = [];
        $errors = [];
        $status = true;
        $users ['local']= $this;
        if (count($data)) {
            $id = ($this->id ? $this->id : null);
            foreach ($users as $connection => $user) {
                if ($user) {
                    foreach ($user->getFillable() as $attr) {
                        if (isset($data[$attr])) {
                            $user->$attr = $data[$attr];
                        }
                    }

                    if ($id) {
                        $user->id = $id;
                    }

                    if(!$user->save()) { 
                        $errors [$connection]= 'something went wrong (could not save item  on '.$connection.')'; $status = false; 
                    } else {
                        $id = $user->id;
                        if (isset($data['role'])) {
                            $user->roles()->sync($data['role']);
                        } else {
                            $roles = Role::all()->pluck('id')->toArray();
                            $user->roles()->sync($roles);
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
        $connection = 'mysql_admin';
        return array_merge([
            'email' => 'required|email|max:255' . ($id ? '|unique:' . $connection . '.users,email,' . $id : ''),
        ], $merge);
    }
}
