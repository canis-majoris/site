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




	Route::middleware('guest')->group( function() {

		//dd('test');
		Route::any('/', array('as' => 'home-index', 'uses' => 'Admin\AuthController@index'));
		Route::get('/logout', ['as' => 'logout-post', 'uses' => 'Admin\AuthController@postLogout']);
		Route::resource('admins', 'AdminController');
		//Route::post('/admins/assignrole', ['as' => 'admin-assign-role', 'uses' => 'AdminController@assignRole']);
		Route::any('/admins', ['middleware' => ['permission:admins'], 'as' => 'admins.index', 'uses' => 'AdminController@index']);
		Route::post('/admins/edit', ['middleware' => ['permission:admins-edit'], 'as' => 'admins-edit', 'uses' => 'AdminController@edit']);
		Route::post('/admins/update', ['middleware' => ['permission:admins-update'], 'as' => 'admins-save', 'uses' => 'AdminController@update']);
		Route::post('/admins/update_img', ['as' => 'admin-updateimg', 'uses' => 'AdminController@update_img']);
		//Route::post('/admin/all', ['as' => 'admin-all', 'uses' => 'AdminController@index']);
		Route::any('home/get_active_admins_list', ['as' => 'home.get_active_admins_list', 'uses' => 'HomeController@updateActiveAdminsList']);
		Route::any('home/get_transactions_list', ['as' => 'home.get_transactions_list', 'uses' => 'HomeController@updateTransactionsList']);

		//LANGUAGES
		//Route::resource('languages', 'LanguageController');

		Route::any('/languages', ['as' => 'languages.index', 'uses' => 'LanguageController@index']);
		Route::post('/languages/edit', ['middleware' => ['permission:languages-edit'], 'as' => 'languages-edit', 'uses' => 'LanguageController@edit']);
		Route::post('/languages/update', ['middleware' => ['permission:languages-edit'],'as' => 'languages-save', 'uses' => 'LanguageController@update']);
		Route::post('/languages/changestatus', ['as' => 'languages-changestatus', 'uses' => 'LanguageController@postChangeStatus']);
		Route::post('/languages/add', ['middleware' => ['permission:languages-create'], 'as' => 'languages-add', 'uses' => 'LanguageController@update']);
		Route::post('/languages/delete', ['middleware' => ['permission:languages-delete'], 'as' => 'languages-delete', 'uses' => 'LanguageController@delete']);

		//DELIVERY
		Route::resource('delivery', 'DeliveryController');
		Route::post('delivery/edit', ['middleware' => ['permission:delivery-edit'], 'as' => 'delivery-edit', 'uses' => 'DeliveryController@edit']);
		Route::post('delivery/update', ['as' => 'delivery-save', 'uses' => 'DeliveryController@update']);
		Route::post('delivery/changestatus', ['as' => 'delivery-changestatus', 'uses' => 'DeliveryController@postChangeStatus']);
		Route::post('delivery/add', ['middleware' => ['permission:delivery-create'], 'as' => 'delivery-add', 'uses' => 'DeliveryController@update']);
		Route::post('delivery/delete', ['middleware' => ['permission:delivery-delete'], 'as' => 'delivery-delete', 'uses' => 'DeliveryController@delete']);
		Route::post('delivery/update_img', ['as' => 'delivery-updateimg', 'uses' => 'DeliveryController@update_img']);
		Route::any('delivery/load_img_form', ['as' => 'delivery-load_img_form', 'uses' => 'DeliveryController@load_img_form']);
		Route::any('delivery/share_img_status', ['as' => 'delivery-share_img_status', 'uses' => 'DeliveryController@share_img_status']);
		Route::any('delivery/attach_image_form', ['as' => 'delivery-attach_image_form', 'uses' => 'DeliveryController@attach_image_form']);
		Route::any('delivery/attach_images', ['as' => 'delivery-attach_images', 'uses' => 'DeliveryController@attach_images']);
		Route::any('delivery/load_attached_images', ['as' => 'delivery-load_attached_images', 'uses' => 'DeliveryController@load_attached_images']);
		Route::any('delivery/load_gallery_items', ['as' => 'delivery-load_gallery_items', 'uses' => 'DeliveryController@load_gallery_items']);
		Route::any('delivery/remove_img', ['as' => 'delivery-remove_img', 'uses' => 'DeliveryController@remove_img']);

		//USERS
		Route::resource('users', 'TvoyoUsersController');
		Route::any('users/show/{user_id}', ['middleware' => ['permission:users-view'],'as' => 'users-show', 'uses' => 'TvoyoUsersController@show']);
		Route::any('users/show/{user_id}/statistics', ['as' => 'users-show-statistics', 'uses' => 'TvoyoUsersController@showStatistics']);
		Route::any('users/show/{user_id}/balance', ['as' => 'users-show-balance', 'uses' => 'TvoyoUsersController@showBalance']);
		Route::post('users/update', ['middleware' => ['permission:users-edit'],'as' => 'users-update', 'uses' => 'TvoyoUsersController@update']);
		Route::post('users/changestatus', ['as' => 'user-changestatus', 'uses' => 'TvoyoUsersController@postChangeStatus']);
		Route::post('users/delete', ['middleware' => ['permission:users-delete'],'as' => 'users-delete', 'uses' => 'TvoyoUsersController@delete']);
		Route::post('users/load_filter', ['as' => 'users.load_filter', 'uses' => 'TvoyoUsersController@loadFilter']);
		Route::post('users/update', ['middleware' => ['permission:users-create'],'as' => 'users-update', 'uses' => 'TvoyoUsersController@update']);
		Route::post('users/add', ['middleware' => ['permission:users-create'],'as' => 'users-add', 'uses' => 'TvoyoUsersController@save']);
		Route::post('users/checkUsernameUnique', ['as' => 'user-check-unique-username', 'uses' => 'TvoyoUsersController@checkUsername']);
		Route::post('users/checkEmailUnique', ['as' => 'user-check-unique-email', 'uses' => 'TvoyoUsersController@checkEmail']);
		Route::post('users/product/add', ['as' => 'users-add-product', 'uses' => 'TvoyoUsersController@addProduct']);
		Route::post('users/product/add/save', ['as' => 'users-add-product-save', 'uses' => 'TvoyoUsersController@updateProduct']);
		Route::post('users/add/cash_payer', ['as' => 'users.add_cash_payer', 'uses' => 'TvoyoUsersController@addCashPayer']);
		Route::any('users/product/stbs', ['as' => 'users-show-product-stbs', 'uses' => 'TvoyoUsersController@getStbsDataTables']);
		Route::any('users/product/services', ['as' => 'users-show-product-services', 'uses' => 'TvoyoUsersController@getServicesDataTables']);
		Route::any('users/product/mobile_services', ['as' => 'users-show-product-mobile_services', 'uses' => 'TvoyoUsersController@getMobileServicesDataTables']);
		Route::any('users/product/goods', ['as' => 'users-show-product-goods', 'uses' => 'TvoyoUsersController@getGoodsDataTables']);
		Route::any('users/product/orders', ['as' => 'users-show-orders', 'uses' => 'TvoyoUsersController@getOrdersDataTables']);


		/*Route::resource('dealerstats', 'DealerStatsController');*/
		Route::any('dealerstats/load/parameters', ['as' => 'dealerstats.load.parameters', 'uses' => 'DealerStatsController@loadParameters']);
		Route::any('dealerstats', ['as' => 'dealerstats.index', 'uses' => 'DealerStatsController@index']);
		Route::any('dealerstats/balance', ['as' => 'dealerstats.balance', 'uses' => 'DealerStatsController@balance']);

		/*Route::any('dealerstats/table', ['as' => 'statistics-table', 'uses' => 'DealerStatsController@getStatisticsDataTables']);*/

		//PERMISSIONS
		Route::resource('permissions', 'PermissionController');
		Route::any('permissions', ['middleware' => ['permission:permissions'], 'as' => 'permissions.index', 'uses' => 'PermissionController@index']);
		Route::post('permissions/new', ['middleware' => ['permission:permissions'],'as' => 'permissions-new', 'uses' => 'PermissionController@update']);
		Route::post('permissions/edit', ['middleware' => ['permission:permissions-edit'],'as' => 'permissions-edit', 'uses' => 'PermissionController@update']);
		Route::post('permissions/save', ['middleware' => ['permission:permissions-edit'],'as' => 'permissions-save', 'uses' => 'PermissionController@save']);
		Route::post('permissions/delete', ['middleware' => ['permission:permissions-delete'],'as' => 'permissions-delete', 'uses' => 'PermissionController@delete']);
		Route::post('permissions/changestatus', ['as' => 'permissions-changestatus', 'uses' => 'PermissionController@changeStatus']);

		//ROLES
		Route::any('roles/load', ['middleware' => ['permission:roles', 'noregion'],'as' => 'roles-load', 'uses' => 'RoleController@loadTypes']);
		Route::post('roles/edit', ['middleware' => ['permission:roles-edit', 'noregion'],'as' => 'roles-edit', 'uses' => 'RoleController@edit']);
		Route::any('roles/delete', ['middleware' => ['permission:roles-delete', 'noregion'],'as' => 'roles-delete', 'uses' => 'RoleController@deleteType']);
		Route::post('roles/save', ['middleware' => ['permission:roles-update', 'noregion'], 'as' => 'roles-save', 'uses' => 'RoleController@update']);
		Route::post('roles/update', ['middleware' => ['permission:roles-delete', 'noregion'], 'as' => 'roles-update', 'uses' => 'RoleController@update']);
		Route::post('roles/load-settings', ['middleware' => ['noregion'], 'as' => 'roles-load_settings', 'uses' => 'RoleController@loadTypeSettings']);
		Route::post('roles/permission/add', ['middleware' => ['noregion'], 'as' => 'roles-add_permission', 'uses' => 'RoleController@addPermission']);
		Route::post('roles/permission/remove', ['middleware' => ['noregion'], 'as' => 'roles-remove_permission', 'uses' => 'RoleController@removePermission']);

		//CATALOG
		Route::resource('catalog', 'ProductController');
		Route::any('catalog', ['middleware' => ['permission:catalog'], 'as' => 'catalog.index', 'uses' => 'ProductController@index']);
		Route::post('products/new', ['middleware' => ['permission:catalog-create'], 'as' => 'products-new', 'uses' => 'ProductController@update']);
		Route::post('products/edit', ['middleware' => ['permission:catalog-edit'], 'as' => 'products-edit', 'uses' => 'ProductController@update']);
		Route::post('products/save', ['middleware' => ['permission:catalog-update'], 'as' => 'products-save', 'uses' => 'ProductController@save']);
		Route::post('products/save_parameters', ['as' => 'product-save-parameters', 'uses' => 'ProductController@saveParameters']);
		Route::post('products/delete', ['middleware' => ['permission:catalog-delete'], 'as' => 'products-delete', 'uses' => 'ProductController@delete']);
		Route::post('products/reorder', ['as' => 'products-reorder', 'uses' => 'ProductController@reorder']);
		Route::post('products/changestatus', ['middleware' => ['permission:catalog-changestatus'], 'as' => 'products-changestatus', 'uses' => 'ProductController@changeStatus']);
		Route::post('products/update_img', ['as' => 'products-updateimg', 'uses' => 'ProductController@update_img']);
		Route::any('products/load_img_form', ['as' => 'products-load_img_form', 'uses' => 'ProductController@load_img_form']);
		Route::any('products/share_img_status', ['as' => 'products-share_img_status', 'uses' => 'ProductController@share_img_status']);
		Route::any('products/attach_image_form', ['as' => 'products-attach_image_form', 'uses' => 'ProductController@attach_image_form']);
		Route::any('products/attach_images', ['as' => 'products-attach_images', 'uses' => 'ProductController@attach_images']);
		Route::any('products/load_attached_images', ['as' => 'products-load_attached_images', 'uses' => 'ProductController@load_attached_images']);
		Route::any('products/load_gallery_items', ['as' => 'products-load_gallery_items', 'uses' => 'ProductController@load_gallery_items']);
		Route::any('products/remove_img', ['as' => 'products-remove_img', 'uses' => 'ProductController@remove_img']);

		//CATALOG
		Route::any('menuitems/load', ['as' => 'menus_items-load', 'uses' => 'ProductMenuController@loadTypes']);
		Route::post('menuitems/edit', ['middleware' => ['permission:menuitems-edit'], 'as' => 'menus_items-edit', 'uses' => 'ProductMenuController@edit']);
		Route::post('menuitemsdata/load', ['as' => 'menus_items_data-load', 'uses' => 'ProductMenuController@loadTypesData']);
		Route::any('menuitems/delete', ['middleware' => ['permission:menuitems-delete'], 'as' => 'menus_items-delete', 'uses' => 'ProductMenuController@deleteType']);
		Route::post('menuitems/save', ['middleware' => ['permission:menuitems-update'], 'as' => 'menus_items-save', 'uses' => 'ProductMenuController@update']);
		Route::post('menuitems/update', ['middleware' => ['permission:menuitems-update'], 'as' => 'menus_items-update', 'uses' => 'ProductMenuController@update']);
		Route::post('menuitems/load-settings', ['as' => 'menus_items-load_settings', 'uses' => 'ProductMenuController@loadTypeSettings']);
		Route::post('menuitems/add_item', ['as' => 'menus_items-add_item', 'uses' => 'ProductMenuController@addItem']);
		Route::post('menuitems/remove_item', ['as' => 'menus_items-remove_item', 'uses' => 'ProductMenuController@removeItem']);


		//ORDERS PRODUCTS
		Route::resource('orders_products', 'OrderProductController');
		Route::post('comment/save', ['as' => 'comment-save', 'uses' => 'OrderProductController@commentSave']);
		Route::any('orders_products', ['middleware' => ['permission:orders_products'], 'as' => 'ordersproducts-index', 'uses' => 'OrderProductController@index']);
		Route::post('orders_products/status/change', ['middleware' => ['permission:orders_products-changestatus'], 'as' => 'ordersproducts-change-status', 'uses' => 'OrderProductController@statusChange']);
		Route::post('orders_products/delete', ['middleware' => ['permission:orders_products-delete'], 'as' => 'orders_products.delete', 'uses' => 'OrderProductController@delete']);
		Route::post('orders_products/load_filter}', ['as' => 'orders_products.load_filter', 'uses' => 'OrderProductController@loadFilter']);
		Route::post('orders_products/pin/update', ['middleware' => ['permission:orders_products-update_pin'], 'as' => 'orders_products.pin-update', 'uses' => 'OrderProductController@updatePin']);
		Route::post('orders_products/edit/show', ['middleware' => ['permission:orders_products-view'], 'as' => 'orders_products.edit.show', 'uses' => 'OrderProductController@showEdit']);
		Route::post('orders_products/edit/save', ['middleware' => ['permission:orders_products-edit'], 'as' => 'orders_products.edit.save', 'uses' => 'OrderProductController@saveEdit']);
		Route::post('orders_products/get/service/status', ['as' => 'orders_products.get.service.status', 'uses' => 'OrderProductController@getServiceStatus']);
		Route::post('orders_products/service/package/add', ['middleware' => ['permission:orders_products-add_package'], 'as' => 'orders_products.service.packages.add', 'uses' => 'OrderProductController@manageServicePackages']);
		Route::post('orders_products/logs/delete', ['middleware' => ['permission:orders_products-delete_logs'], 'as' => 'orders_products.logs.delete', 'uses' => 'OrderProductController@deleteLogPackages']);
		Route::any('orders_products/show/logs', ['as' => 'product-show-logs', 'uses' => 'OrderProductController@getProductLogsDataTables']);


		//ORDERS
		Route::resource('orders', 'OrderController');
		Route::any('orders', ['middleware' => ['permission:orders'], 'as' => 'orders.index', 'uses' => 'OrderController@index']);
		Route::any('orders/show/{order_id}', ['middleware' => ['permission:orders-view'], 'as' => 'orders-show', 'uses' => 'OrderController@show']);
		Route::post('orders/update', ['middleware' => ['permission:orders-edit'], 'as' => 'orders-update', 'uses' => 'OrderController@update']);
		Route::post('orders/delete', ['middleware' => ['permission:orders-delete'], 'as' => 'orders-delete', 'uses' => 'OrderController@delete']);
		Route::post('orders/all', ['middleware' => ['permission:orders'], 'as' => 'orders-all', 'uses' => 'OrderController@all']);
		Route::post('orders/stbs', ['as' => 'orders-stbs', 'uses' => 'OrderController@stbs']);
		Route::post('orders/services', ['as' => 'orders-services', 'uses' => 'OrderController@services']);
		Route::post('orders/mobile_services', ['as' => 'orders-mobile_services', 'uses' => 'OrderController@mobile_services']);
		Route::post('orders/goods', ['as' => 'orders-goods', 'uses' => 'OrderController@goods']);
		Route::any('orders/get/statistics/month_chart', ['as' => 'orders.get.statistics.month_chart', 'uses' => 'OrderController@getMonthChart']);
		Route::any('orders/get/statistics/chart', ['as' => 'orders.get.statistics.chart', 'uses' => 'OrderController@getChart']);
		Route::any('orders/get/statistics/sales', ['as' => 'orders.get.statistics.sales', 'uses' => 'OrderController@getSales']);
		Route::any('orders/get/products', ['as' => 'orders-show-products', 'uses' => 'OrderController@getProductsDataTables']);
		Route::any('orders/changetransaction', ['middleware' => ['permission:orders-changetransaction'], 'as' => 'orders.changetransaction', 'uses' => 'OrderController@changeTransaction']);
		Route::any('orders/sticker/query', ['as' => 'order.sticker.query', 'uses' => 'OrderController@stickerQuery']);
		Route::any('orders/get/usticker', ['as' => 'order.usticker', 'uses' => 'OrderController@usticker']);



		//DISCOUNTS
		Route::resource('discounts', 'DiscountController');
		Route::any('discounts', ['middleware' => ['permission:discounts'], 'as' => 'discounts.index', 'uses' => 'DiscountController@index']);
		Route::post('discounts/update', ['middleware' => ['permission:discounts-edit'], 'as' => 'discounts-update', 'uses' => 'DiscountController@update']);
		Route::post('discounts/delete', ['middleware' => ['permission:discounts-delete'], 'as' => 'discounts-delete', 'uses' => 'DiscountController@delete']);
		Route::post('discounts/all', ['middleware' => ['permission:discounts'], 'as' => 'discounts-all', 'uses' => 'DiscountController@all']);
		Route::any('discounts/edit/show', ['middleware' => ['permission:discounts-edit'], 'as' => 'discounts.edit.show', 'uses' => 'DiscountController@edit']);
		Route::post('discounts/update', ['middleware' => ['permission:discounts-update'], 'as' => 'discounts-save', 'uses' => 'DiscountController@update']);
		Route::any('discounts/show/products', ['as' => 'discounts-show-products', 'uses' => 'DiscountController@showProducts']);
		Route::post('discounts/status/change', ['middleware' => ['permission:discounts-changestatus'], 'as' => 'discounts-changestatus', 'uses' => 'DiscountController@statusChange']);
		Route::post('discounts/users/edit', ['middleware' => ['permission:discounts-users_edit'], 'as' => 'discounts.users.edit', 'uses' => 'DiscountController@usersEdit']);
		Route::post('discounts/users/save', ['middleware' => ['permission:discounts-users_update'], 'as' => 'discounts.users.save', 'uses' => 'DiscountController@usersSave']);
		Route::any('discounts/track/{id}', ['middleware' => ['permission:discounts-view'], 'as' => 'discounts.track', 'uses' => 'DiscountController@track']);
		Route::any('discounts/users/load/free', ['as' => 'discounts.users.load.free', 'uses' => 'DiscountController@loadFreeUsers']);
		Route::any('discounts/users/load/all_available', ['as' => 'discounts.users.load.all_available', 'uses' => 'DiscountController@loadAvailableUsers']);
		Route::any('discounts/dealers/load/free', ['as' => 'discounts.dealers.load.free', 'uses' => 'DiscountController@loadFreeDealers']);
		Route::any('discounts/dealers/load/all_available', ['as' => 'discounts.dealers.load.all_available', 'uses' => 'DiscountController@loadAvailableDealers']);
		Route::any('discounts/show/log', ['as' => 'discounts.show.log', 'uses' => 'DiscountController@getDiscountLog']);



		//PROMOS
		Route::resource('promos', 'PromoController');
		Route::post('promos/update', ['as' => 'promos-update', 'uses' => 'PromoController@update']);
		Route::post('promos/delete', ['as' => 'promos-delete', 'uses' => 'PromoController@delete']);
		Route::post('promos/all', ['as' => 'promos-all', 'uses' => 'PromoController@all']);
		Route::any('promos/edit/show', ['as' => 'promos.edit.show', 'uses' => 'PromoController@edit']);
		Route::any('promos/track/{id}', ['as' => 'promos.track', 'uses' => 'PromoController@track']);
		Route::post('promos/update', ['as' => 'promos-save', 'uses' => 'PromoController@update']);
		Route::any('promos/show/products', ['as' => 'promos-show-products', 'uses' => 'PromoController@showProducts']);
		Route::post('promos/status/change', ['as' => 'promos-changestatus', 'uses' => 'PromoController@statusChange']);
		Route::any('promos/show/log', ['as' => 'promos.show.log', 'uses' => 'PromoController@getPromoLog']);

		//TRANSACTIONS
		/*		Route::resource('promos', 'PromoController');*/
		Route::any('transactions', ['as' => 'transactions.index', 'uses' => 'TransactionController@index']);
		Route::any('transactions/provider/cartu', ['as' => 'transactions.provider.cartu', 'uses' => 'TransactionController@getCartuTransactionsDataTable']);
		Route::any('transactions/provider/paypal', ['as' => 'transactions.provider.paypal', 'uses' => 'TransactionController@getPaypalTransactionsDataTable']);
		Route::post('transactions/load/filter', ['as' => 'transactions.load_filter', 'uses' => 'TransactionController@loadFilter']);
		Route::post('transactions/load/table', ['as' => 'transactions.load_table', 'uses' => 'TransactionController@loadTable']);
		// Route::any('transactions/edit/show', ['as' => 'transactions.edit.show', 'uses' => 'TransactionController@edit']);
		// Route::any('transactions/track/{id}', ['as' => 'transactions.track', 'uses' => 'TransactionController@track']);


		//REGIONS
		Route::post('region/change', ['as' => 'change-region', 'uses' => 'HomeController@changeRegion']);
		Route::post('users/get/statistics/chart', ['as' => 'users.get.statistics.chart', 'uses' => 'HomeController@usersChart']);


		Route::post('file/upload_new', ['as' => 'file-upload-new', 'uses' => 'FileController@upload']);
		Route::post('file/delete', ['as' => 'file-delete', 'uses' => 'FileController@delete']);


		//////////////////////////////////////////////////////////


		/*Route::get('/register', array('as' => 'register', 'uses' => 'Admin\AuthController@getRegister'));
		Route::any('/register-post', array('as' => 'register-post', 'uses' => 'Admin\AuthController@postRegister'));*/
		Route::any('/auth-confirm/{code}', array('as' => 'auth-confirm', 'uses' => 'Admin\AuthController@getConfirm'));

		// Resend routes...
		Route::get('resendconfirmmail', ['as' => 'resend-confirm-mail', 'uses' => 'Admin\AuthController@getResend']);


		//Users
		Route::get('users/all', ['as' => 'users-all', 'uses' => 'Admin\UserController@allUsers']);
		Route::get('users/edit/{id}', ['as' => 'user-edit', 'uses' => 'Admin\UserController@getEditUser']);
		Route::post('users/edit', ['as' => 'user-edit-post', 'uses' => 'Admin\UserController@postEditUser']);
		Route::get('users/delete/{id}', ['as' => 'user-delete', 'uses' => 'Admin\UserController@getDeleteUser']);

		//Services
		// Route::get('services/all', ['as' => 'services-all', 'uses' => 'Admin\ServiceController@allServices']);
		// Route::get('services/edit/{id}', ['as' => 'services-edit', 'uses' => 'Admin\ServiceController@getEditService']);
		// Route::post('services/edit', ['as' => 'services-edit-post', 'uses' => 'Admin\ServiceController@postEditService']);
		// Route::get('services/add', ['as' => 'services-add', 'uses' => 'Admin\ServiceController@getAddService']);
		// Route::post('services/add', ['as' => 'services-add-post', 'uses' => 'Admin\ServiceController@postAddService']);
		// Route::get('services/delete/{id}', ['as' => 'services-delete', 'uses' => 'Admin\ServiceController@getDeleteService']);

		//resource('service', 'Admin\ServiceController');

		//Slider
		//resource('slider', 'Admin\SliderController');
		Route::get('soc/edit', ['as' => 'admin.soc.index', 'uses' => 'Admin\HomeController@getEditSocLinks']);
		Route::post('soc/edit', ['as' => 'admin.soc.edit.post', 'uses' => 'Admin\HomeController@updateSocialLinks']);
		Route::post('soc/changestatus', ['as' => 'soc-changestatus', 'uses' => 'Admin\HomeController@postChangeStatus']);

		//User

		Route::get('user/edit/{id}', ['as' => 'user-edit', 'uses' => 'Admin\UserController@getEditUser']);
		Route::post('user/edit', ['as' => 'user-edit-post', 'uses' => 'Admin\UserController@postEditUser']);

		//Pages
		Route::get('page_container', array('as' => 'admin.page_container.index', 'uses' => 'Admin\PageContainerController@index'));
		Route::get('page_container/create_new', ['as' => 'admin.page_container.create_new_page', 'uses' => 'Admin\PageContainerController@create_new']);
		Route::post('page_container/delete', ['as' => 'admin.page_container.destroy', 'uses' => 'Admin\PageContainerController@destroy']);
		//resource('page_container', 'Admin\PageContainerController');
		Route::post('page_container/edit', ['as' => 'admin.page_container.edit', 'uses' => 'Admin\PageContainerController@update']);
		/*Route::get('page_container/create/{language_id}/{code}', ['as' => 'admin.page_container.create', 'uses' => 'Admin\PageContainerController@create']);*/

		// Route::get('page_container/edit/changelang/{code}/{language_id}', ['as' => 'admin.page_container.edit.changelang', 'uses' => 'Admin\PageContainerController@getEditChangeLang']);


		Route::get('page_container/edit/{code}/{language_id}', ['as' => 'admin.page_container.edit_with_lang', 'uses' => 'Admin\PageContainerController@editWithLang']);

		Route::group(array('prefix' => 'messaging'), function() {
		    Route::get('/', array('as' => 'messaging.index', 'uses' => 'Messaging\HomeController@showIndex'));
		    Route::get('logout', 'Messaging\AuthController@postLogout');
		    Route::post('check-mac', array('as' => 'check-mac-address', 'uses' => 'Messaging\HomeController@checkMac'));
		    Route::post('send', array('as' => 'send-message', 'uses' => 'Messaging\HomeController@sendMessage'));
		});

		Route::any('order_status/create', array('as' => 'order_status.create', 'uses' => 'OrderStatusController@create'));
		Route::any('order_status/destroy', array('as' => 'order_status.destroy', 'uses' => 'OrderStatusController@destroy'));
		Route::any('order_status/edit', array('as' => 'order_status.edit', 'uses' => 'OrderStatusController@edit'));
		Route::post('order_status/update', array('as' => 'order_status.update', 'uses' => 'OrderStatusController@update'));
		Route::post('order_status/changestatus', array('as' => 'order_status.changestatus', 'uses' => 'OrderStatusController@postChangeStatus'));

		Route::resource('order_settings', 'Settings\OrderSettingsController');


		//CHARTS
		Route::any('charts', array('as' => 'charts.index', 'uses' => 'ChartsPageController@index'));
		Route::any('charts/create', array('as' => 'charts.create', 'uses' => 'ChartsPageController@create'));
		Route::any('charts/destroy', array('as' => 'charts.destroy', 'uses' => 'ChartsPageController@destroy'));
		Route::any('charts/edit', array('as' => 'charts.edit', 'uses' => 'ChartsPageController@edit'));
		Route::post('charts/update', array('as' => 'charts.update', 'uses' => 'ChartsPageController@update'));
		Route::post('charts/changestatus', array('as' => 'charts.changestatus', 'uses' => 'ChartsPageController@postChangeStatus'));
		Route::post('charts/update_img', ['as' => 'charts.updateimg', 'uses' => 'ChartsPageController@update_img']);
		Route::any('charts/show', array('as' => 'charts.show', 'uses' => 'ChartsPageController@show'));


		//ACTIVITY LOG
		Route::resource('activitylog', 'ActivityLogController');
		Route::any('activitylog', ['middleware' => ['permission:activitylog'], 'as' => 'activitylog.index', 'uses' => 'ActivityLogController@index']);
		Route::any('activitylog/delete', ['middleware' => ['permission:activitylog-delete'], 'as' => 'activitylog.delete', 'uses' => 'ActivityLogController@delete']);

		//BILLING LOG
		Route::resource('billinglog', 'BillingLogController');
		Route::any('billinglog', ['middleware' => ['permission:billinglog'], 'as' => 'billinglog.index', 'uses' => 'BillingLogController@index']);
		Route::any('billinglog/delete', ['middleware' => ['permission:billinglog-delete'], 'as' => 'billinglog.delete', 'uses' => 'BillingLogController@delete']);

		//CONTENT
		//
		////TEXTS
		Route::any('textstypes/load', ['as' => 'texts_types-load', 'uses' => 'TextTypeController@loadTypes']);
		Route::post('textstypes/edit', ['middleware' => ['permission:textstypes-edit'], 'as' => 'texts_types-edit', 'uses' => 'TextTypeController@edit']);
		/*Route::post('textstypes/load', ['as' => 'texts_types-load', 'uses' => 'TextTypeController@loadTextTypesData']);*/
		Route::any('textstypes/delete', ['middleware' => ['permission:textstypes-delete'], 'as' => 'texts_types-delete', 'uses' => 'TextTypeController@deleteType']);
		Route::post('textstypes/save', ['middleware' => ['permission:textstypes-update'], 'as' => 'texts_types-save', 'uses' => 'TextTypeController@update']);
		Route::post('textstypes/update', ['middleware' => ['permission:textstypes-update'], 'as' => 'texts_types-update', 'uses' => 'TextTypeController@update']);
		Route::post('textstypes/load-settings', ['as' => 'texts_types-load_settings', 'uses' => 'TextTypeController@loadTypeSettings']);

		//Route::resource('pages', 'PagesController');
		Route::any('texts', ['middleware' => ['permission:pages'], 'as' => 'texts.index', 'uses' => 'TextController@index']);
		Route::any('texts/new', ['middleware' => ['permission:pages-create'], 'as' => 'texts-new', 'uses' => 'TextController@update']);
		Route::any('texts/edit', ['middleware' => ['permission:pages-edit'], 'as' => 'texts-edit', 'uses' => 'TextController@update']);
		Route::post('texts/save', ['middleware' => ['permission:pages-update'], 'as' => 'texts-save', 'uses' => 'TextController@save']);
		Route::post('texts/save_parameters', ['as' => 'texts-save-parameters', 'uses' => 'TextController@saveParameters']);
		Route::post('texts/delete', ['middleware' => ['permission:pages-delete'], 'as' => 'texts-delete', 'uses' => 'TextController@delete']);
		Route::post('texts/reorder', ['middleware' => ['permission:pages-reorder'], 'as' => 'texts-reorder', 'uses' => 'TextController@reorder']);
		Route::post('texts/changestatus', ['middleware' => ['permission:pages-changestatus'], 'as' => 'texts-changestatus', 'uses' => 'TextController@changeStatus']);
		Route::post('texts/update_img', ['as' => 'texts-updateimg', 'uses' => 'TextController@update_img']);
		Route::any('texts/load_img_form', ['as' => 'texts-load_img_form', 'uses' => 'TextController@load_img_form']);
		Route::any('texts/share_img_status', ['as' => 'texts-share_img_status', 'uses' => 'TextController@share_img_status']);
		Route::any('texts/attach_image_form', ['as' => 'texts-attach_image_form', 'uses' => 'TextController@attach_image_form']);
		Route::any('texts/attach_images', ['as' => 'texts-attach_images', 'uses' => 'TextController@attach_images']);
		Route::any('texts/load_attached_images', ['as' => 'texts-load_attached_images', 'uses' => 'TextController@load_attached_images']);
		Route::any('texts/load_gallery_items', ['as' => 'texts-load_gallery_items', 'uses' => 'TextController@load_gallery_items']);
		Route::any('texts/remove_img', ['as' => 'texts-remove_img', 'uses' => 'TextController@remove_img']);


		//PAGES

		Route::any('pagestypes/load', ['as' => 'pages_types-load', 'uses' => 'PageTypeController@loadPageTypes']);
		Route::post('pagestypes/edit', ['middleware' => ['permission:textstypes-edit'], 'as' => 'pages_types-edit', 'uses' => 'PageTypeController@edit']);
		Route::any('pagestypes/delete', ['middleware' => ['permission:textstypes-delete'], 'as' => 'pages_types-delete', 'uses' => 'PageTypeController@deletePageType']);
		Route::post('pagestypes/save', ['middleware' => ['permission:textstypes-update'], 'as' => 'pages_types-save', 'uses' => 'PageTypeController@update']);
		Route::post('pagestypes/update', ['middleware' => ['permission:textstypes-update'], 'as' => 'pages_types-update', 'uses' => 'PageTypeController@update']);
		Route::post('pagestypes/load-settings', ['as' => 'pages_types-load_settings', 'uses' => 'PageTypeController@loadPageTypeSettings']);

		Route::any('pages', ['middleware' => ['permission:pages'], 'as' => 'pages.index', 'uses' => 'Admin\PageController@index']);
		Route::any('pages/new', ['middleware' => ['permission:pages-create'], 'as' => 'pages-new', 'uses' => 'Admin\PageController@update']);
		Route::any('pages/edit', ['middleware' => ['permission:pages-edit'], 'as' => 'pages-edit', 'uses' => 'Admin\PageController@update']);
		Route::post('pages/save', ['middleware' => ['permission:pages-update'], 'as' => 'pages-save', 'uses' => 'Admin\PageController@save']);
		Route::post('pages/save_parameters', ['as' => 'pages-save-parameters', 'uses' => 'Admin\PageController@saveParameters']);
		Route::post('pages/delete', ['middleware' => ['permission:pages-delete'], 'as' => 'pages-delete', 'uses' => 'Admin\PageController@delete']);
		Route::post('pages/reorder', ['middleware' => ['permission:pages-reorder'], 'as' => 'pages-reorder', 'uses' => 'Admin\PageController@reorder']);
		Route::post('pages/changestatus', ['middleware' => ['permission:pages-changestatus'], 'as' => 'pages-changestatus', 'uses' => 'Admin\PageController@changeStatus']);
		Route::post('pages/update_nestable_list', ['middleware' => ['permission:pages-changestatus'], 'as' => 'pages-update_nestable_list', 'uses' => 'Admin\PageController@updateNestableList']);
		Route::post('pages/load_nestable_list', ['middleware' => ['permission:pages-changestatus'], 'as' => 'pages-load_nestable_list', 'uses' => 'Admin\PageController@loadNestableList']);


		Route::post('pages/update_img', ['as' => 'pages-updateimg', 'uses' => 'Admin\PageController@update_img']);
		Route::any('pages/load_img_form', ['as' => 'pages-load_img_form', 'uses' => 'Admin\PageController@load_img_form']);
		Route::any('pages/share_img_status', ['as' => 'pages-share_img_status', 'uses' => 'Admin\PageController@share_img_status']);
		Route::any('pages/attach_image_form', ['as' => 'pages-attach_image_form', 'uses' => 'Admin\PageController@attach_image_form']);
		Route::any('pages/attach_images', ['as' => 'pages-attach_images', 'uses' => 'Admin\PageController@attach_images']);
		Route::any('pages/load_attached_images', ['as' => 'pages-load_attached_images', 'uses' => 'Admin\PageController@load_attached_images']);
		Route::any('pages/load_gallery_items', ['as' => 'pages-load_gallery_items', 'uses' => 'Admin\PageController@load_gallery_items']);
		Route::any('pages/remove_img', ['as' => 'pages-remove_img', 'uses' => 'Admin\PageController@remove_img']);


		//Settings Types
		Route::any('settingstypes/load', ['as' => 'settings_types-load', 'uses' => 'SettingsTypeController@loadTypes']);
		Route::post('settingstypes/edit', ['middleware' => ['permission:settingstypes-edit'], 'as' => 'settings_types-edit', 'uses' => 'SettingsTypeController@edit']);
		Route::any('settingstypes/delete', ['middleware' => ['permission:settingstypes-delete'], 'as' => 'settings_types-delete', 'uses' => 'SettingsTypeController@deleteType']);
		Route::post('settingstypes/save', ['middleware' => ['permission:settingstypes-update'], 'as' => 'settings_types-save', 'uses' => 'SettingsTypeController@update']);
		Route::post('settingstypes/update', ['middleware' => ['permission:settingstypes-update'], 'as' => 'settings_types-update', 'uses' => 'SettingsTypeController@update']);
		Route::post('settingstypes/load-settings', ['as' => 'settings_types-load_settings', 'uses' => 'SettingsTypeController@loadTypeSettings']);

		//Settings
		Route::any('settings', ['middleware' => ['permission:settings'], 'as' => 'settings.index', 'uses' => 'SettingsController@index']);
		Route::any('settings/new', ['middleware' => ['permission:settings-create'], 'as' => 'settings-new', 'uses' => 'SettingsController@update']);
		Route::any('settings/edit', ['middleware' => ['permission:settings-edit'], 'as' => 'settings-edit', 'uses' => 'SettingsController@update']);
		Route::post('settings/save', ['middleware' => ['permission:settings-update'], 'as' => 'settings-save', 'uses' => 'SettingsController@save']);
		Route::post('settings/save_parameters', ['as' => 'settings-save-parameters', 'uses' => 'SettingsController@saveParameters']);
		Route::post('settings/delete', ['middleware' => ['permission:settings-delete'], 'as' => 'settings-delete', 'uses' => 'SettingsController@delete']);
		Route::post('settings/reorder', ['middleware' => ['permission:settings-reorder'], 'as' => 'settings-reorder', 'uses' => 'SettingsController@reorder']);
		Route::post('settings/changestatus', ['middleware' => ['permission:settings-changestatus'], 'as' => 'settings-changestatus', 'uses' => 'SettingsController@changeStatus']);
		Route::post('settings/update_img', ['as' => 'settings-updateimg', 'uses' => 'SettingsController@update_img']);
		Route::any('settings/load_img_form', ['as' => 'settings-load_img_form', 'uses' => 'SettingsController@load_img_form']);
		Route::any('settings/share_img_status', ['as' => 'settings-share_img_status', 'uses' => 'SettingsController@share_img_status']);
		Route::any('settings/attach_image_form', ['as' => 'settings-attach_image_form', 'uses' => 'SettingsController@attach_image_form']);
		Route::any('settings/attach_images', ['as' => 'settings-attach_images', 'uses' => 'SettingsController@attach_images']);
		Route::any('settings/load_attached_images', ['as' => 'settings-load_attached_images', 'uses' => 'SettingsController@load_attached_images']);
		Route::any('settings/load_gallery_items', ['as' => 'settings-load_gallery_items', 'uses' => 'SettingsController@load_gallery_items']);
		Route::any('settings/remove_img', ['as' => 'settings-remove_img', 'uses' => 'SettingsController@remove_img']);






		//NOTIFICATIONS	

		Route::any('notifications', ['as' => 'users.notifications', 'uses' => 'NotificationsController@index']);
		Route::post('notifications/{user}/follow', ['as' => 'users.notifications.follow', 'uses' => 'NotificationsController@follow']);
		Route::post('notifications/{user}/unfollow', ['as' => 'users.notifications.unfollow', 'uses' => 'NotificationsController@unfollow']);

		Route::any('/notifications/list', ['as' => 'users.notifications.list', 'uses' => 'NotificationsController@notifications']);

		//\NOTIFICATIONS	
		//

		//GALLERY
		Route::get('gallery', ['as' => 'gallery.index', 'uses' => 'GalleryController@index']);
		Route::post('gallery/new', ['as' => 'gallery.new', 'uses' => 'GalleryController@update']);
		Route::post('gallery/edit', ['as' => 'gallery.edit', 'uses' => 'GalleryController@update']);
		Route::any('gallery/save', ['as' => 'gallery.save', 'uses' => 'GalleryController@save']);
		Route::any('gallery/update_img', ['as' => 'gallery.update_img', 'uses' => 'GalleryController@update_img']);
		Route::post('gallery/changestatus', ['as' => 'gallery.changestatus', 'uses' => 'GalleryController@changeStatus']);
		Route::any('gallery/delete', ['as' => 'gallery.delete', 'uses' => 'GalleryController@delete']);

		Route::post('gallery/update_img', ['as' => 'gallery-updateimg', 'uses' => 'GalleryController@update_img']);
		Route::any('gallery/load_img_form', ['as' => 'gallery-load_img_form', 'uses' => 'GalleryController@load_img_form']);
		Route::any('gallery/attach_image_form', ['as' => 'gallery-attach_image_form', 'uses' => 'GalleryController@attach_image_form']);
		Route::any('gallery/attach_images', ['as' => 'gallery-attach_images', 'uses' => 'GalleryController@attach_images']);
		Route::any('gallery/load_attached_images', ['as' => 'gallery-load_attached_images', 'uses' => 'GalleryController@load_attached_images']);
		Route::any('gallery/load_gallery_items', ['as' => 'gallery-load_gallery_items', 'uses' => 'GalleryController@load_gallery_items']);
		Route::any('gallery/remove_img', ['as' => 'gallery-remove_img', 'uses' => 'GalleryController@remove_img']);



		//GALLERY TYPES
		Route::any('gallery_types/load', ['as' => 'gallery_types-load', 'uses' => 'GalleryTypeController@loadGalleryTypes']);
		Route::post('gallery_types/edit', ['middleware' => ['permission:settingstypes-edit'], 'as' => 'gallery_types-edit', 'uses' => 'GalleryTypeController@edit']);
		Route::any('gallery_types/delete', ['middleware' => ['permission:settingstypes-delete'], 'as' => 'gallery_types-delete', 'uses' => 'GalleryTypeController@deleteGalleryType']);
		Route::post('gallery_types/save', ['middleware' => ['permission:settingstypes-update'], 'as' => 'gallery_types-save', 'uses' => 'GalleryTypeController@update']);
		Route::post('gallery_types/update', ['middleware' => ['permission:settingstypes-update'], 'as' => 'gallery_types-update', 'uses' => 'GalleryTypeController@update']);
		Route::post('gallery_types/load-settings', ['as' => 'gallery_types-load_settings', 'uses' => 'GalleryTypeController@loadGalleryTypeSettings']);
		Route::any('gallery_types/load-data', ['as' => 'gallery_types-load_data', 'uses' => 'GalleryTypeController@loadGalleryTypesData']);

		Route::post('gallery_types/update_img', ['as' => 'gallery_types-updateimg', 'uses' => 'GalleryTypeController@update_img']);
		Route::any('gallery_types/load_img_form', ['as' => 'gallery_types-load_img_form', 'uses' => 'GalleryTypeController@load_img_form']);
		Route::any('gallery_types/attach_image_form', ['as' => 'gallery_types-attach_image_form', 'uses' => 'GalleryTypeController@attach_image_form']);
		Route::any('gallery_types/attach_images', ['as' => 'gallery_types-attach_images', 'uses' => 'GalleryTypeController@attach_images']);
		Route::any('gallery_types/load_attached_images', ['as' => 'gallery_types-load_attached_images', 'uses' => 'GalleryTypeController@load_attached_images']);
		Route::any('gallery_types/load_gallery_items', ['as' => 'gallery_types-load_gallery_items', 'uses' => 'GalleryTypeController@load_gallery_items']);
		Route::any('gallery_types/remove_img', ['as' => 'gallery_types-remove_img', 'uses' => 'GalleryTypeController@remove_img']);

		Route::get('test/password', function () {

			$salt = '0'; //User::find_salt(User::find(3164)->password);

			$password = User::hash_password('canismajoris', $salt);

		    dd([$password, $salt]);
		});


		/*Route::controller('menustable', 'Admin\MenuController', [
		    'anyData'  => 'menustable.data',
		    //'getIndex' => 'menustable',
		]);
		Route::controller('languagestable', 'Admin\LanguageController', [
		    'anyData'  => 'languagestable.data',
		    //'getIndex' => 'menustable',
		]);*/

		Route::post('service/uploadfiles', [
		'as'=> 'files-upload',
		'uses' => 'ServiceController@postFileUpload'
		]);
		Route::post('service/deletefiles', [
		'as'=> 'files-delete',
		'uses' => 'ServiceController@postFileDelete'
		]);
		});
});
