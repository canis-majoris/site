<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::any('post/signIn', ['as' => 'post.sign-in', 'uses' => 'AuthController@signIn']);
// Route::any('post/signUp', ['as' => 'post.sign-up', 'uses' => 'AuthController@signUp']);
// Route::get('post/signOut', ['as' => 'post.sign-out', 'uses' => 'AuthController@signOut']);

Route::get('/uploadtest/', 'UploadImagesController@create');
Route::post('/images-save', 'UploadImagesController@store');
Route::post('/images-delete', 'UploadImagesController@destroy');
Route::get('/images-show', 'UploadImagesController@index');

Route::prefix('{lang?}')->middleware('locale')->group(function() {
	// Route::middleware('auth_custom')->group(function() {
	// 	Route::get('/charts-page', ['as' => 'page', 'uses' => 'PagesController@profile']);
	// });

	// Route::middleware('guest_custom')->group(function() {
	// 	Route::get('/sign-up', ['as' => 'sign-up', 'uses' => 'AuthController@getSignUp']);
	// 	Route::get('/sign-in', ['as' => 'sign-in', 'uses' => 'AuthController@getSignIn']);
	// });

    Route::get('/{page?}', ['as' => 'page', 'uses' => 'PagesController@index']);
   // Route::get('/page', 'PagesController@index');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
