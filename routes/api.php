<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// ADMIN //

Route::prefix('admin')->group(function() {
	Route::get('/login', array('as' => 'admin-login', 'uses' => 'Admin\AuthController@getLogin'));
	Route::any('/post-login', array('as' => 'admin-login-post', 'uses' => 'Admin\AuthController@postLogin'));
	Route::get('/register', array('as' => 'register', 'uses' => 'Admin\AuthController@getRegister'));
	Route::any('/register-post', array('as' => 'register-post', 'uses' => 'Admin\AuthController@postRegister'));
	Route::post('/checkEmailUnique', ['as' => 'check-unique-email', 'uses' => 'Admin\AuthController@checkEmail']);
	Route::get('/confirm_registration/{code}', ['as' => 'confirm-registration', 'uses' => 'Admin\AuthController@confirm_registration']);

	Route::group(['middleware' => ['role:superadmin|admin']], function() {
		Route::post('/admins/changestatus', ['as' => 'admins-changestatus', 'uses' => 'AdminController@postChangeStatus']);
		Route::post('/admins/add', ['middleware' => ['permission:admins-create'],'as' => 'admins-add', 'uses' => 'AdminController@update']);
		Route::post('/admins/delete', ['middleware' => ['permission:admins-delete'],'as' => 'admins-delete', 'uses' => 'AdminController@delete']);
	});
});
