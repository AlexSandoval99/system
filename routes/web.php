<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'App\Http\Controllers\HomeController@index');
Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');
Route::get('/config', 'App\Http\Controllers\ConfigController@index')->name('config');
Route::put('/config/update/{id}', 'App\Http\Controllers\ConfigController@update')->name('config.update');

Route::group(['namespace' => 'App\Http\Controllers\Profile'], function (){
	Route::get('/profile', 'ProfileController@index')->name('profile');
	Route::put('/profile/update/profile/{id}', 'ProfileController@updateProfile')->name('profile.update.profile');
	Route::put('/profile/update/password/{id}', 'ProfileController@updatePassword')->name('profile.update.password');
	Route::put('/profile/update/avatar/{id}', 'ProfileController@updateAvatar')->name('profile.update.avatar');
});

Route::group(['namespace' => 'App\Http\Controllers\Error'], function (){
	Route::get('/unauthorized', 'ErrorController@unauthorized')->name('unauthorized');
});

Route::group(['namespace' => 'App\Http\Controllers'], function (){
	
	Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
	//Users
	Route::get('user', 'UserController@index')->name('user');
	Route::get('user/create', 'UserController@create')->name('user.create');
	Route::post('user/store', 'UserController@store')->name('user.store');
	Route::get('user/edit/{id}', 'UserController@edit')->name('user.edit');
	Route::put('user/update/{id}', 'UserController@update')->name('user.update');
	Route::get('user/edit/password/{id}', 'UserController@editPassword')->name('user.edit.password');
	Route::put('user/update/password/{id}', 'UserController@updatePassword')->name('user.update.password');
	Route::get('user/show/{id}', 'UserController@show')->name('user.show');
	Route::get('user/destroy/{id}', 'UserController@destroy')->name('user.destroy');
	// Roles
	Route::get('role', 'RoleController@index')->name('role');
	Route::get('role/create', 'RoleController@create')->name('role.create');
	Route::post('role/store', 'RoleController@store')->name('role.store');
	Route::get('role/edit/{id}', 'RoleController@edit')->name('role.edit');
	Route::put('role/update/{id}', 'RoleController@update')->name('role.update');
	Route::get('role/show/{id}', 'RoleController@show')->name('role.show');
	Route::get('role/destroy/{id}', 'RoleController@destroy')->name('role.destroy');


	Route::get('articulo', 'ArticuloController@index')->name('articulo');
	Route::get('articulo/create', 'ArticuloController@create')->name('articulo-create');
	Route::post('articulo', 'ArticuloController@store')->name('articulo.store');
	Route::get('articulo/{articulo}/pdf', 'ArticuloController@pdf')->name('articulo.pdf');


	Route::get('cliente', 'ClienteController@index')->name('cliente');
	Route::get('cliente/create', 'ClienteController@create')->name('cliente-create');
	Route::post('cliente', 'ClienteController@store')->name('cliente.store');

    Route::get('ajax/purchases_providers', 'ProveedorController@ajax_providers')->name('ajax.providers');
    Route::get('ajax/purchases_providers_details', 'ProveedorController@ajax_providers_purchases')->name('ajax.providers-purchases');
	Route::get('provider', 'ProveedorController@index')->name('provider');
	Route::get('provider/create', 'ProveedorController@create')->name('provider-create');
	Route::get('provider/{provider_id}/edit', 'ProveedorController@edit')->name('provider-create');
	Route::post('provider', 'ProveedorController@store')->name('provider.store');
	Route::put('provider/{provider_id}', 'ProveedorController@update')->name('provider.update');
	Route::get('provider-xls', 'ProveedorController@export_xls')->name('provider.export-xls');

	Route::get('raw-materials', 'RawMaterialsController@index')->name('raw-materials');
	Route::get('raw-materials/create', 'RawMaterialsController@create')->name('raw-materials-create');
	Route::post('raw-materials', 'RawMaterialsController@store')->name('raw-materials.store');

    Route::get('brand', 'BrandController@index')->name('brand');
	Route::get('brand/create', 'BrandController@create')->name('brand-create');
	Route::post('brand', 'BrandController@store')->name('brand.store');

	Route::get('nationalities', 'NationalitiesController@index')->name('nationalities');
	Route::get('nationalities/create', 'NationalitiesController@create')->name('nationalities-create');
	Route::post('nationalities', 'NationalitiesController@store')->name('nationalities.store');


    Route::get('wish-purchase', 'WishPurchaseController@index')->name('wish-purchase');
	Route::get('wish-purchase/create', 'WishPurchaseController@create')->name('wish-purchase-create');
	Route::get('wish-purchase/{wish_purchase}', 'WishPurchaseController@show')->name('wish-purchase-create');
	Route::get('wish-purchase/{wish_purchase}/charge-purchase-budgets', 'WishPurchaseController@charge_purchase_budgets')->name('wish-purchases.charge-budgets');
    Route::post('wish-purchase-budgets/{wish_purchase}/charge-purchase-budgets', 'WishPurchaseController@charge_purchase_budgets_store')->name('wish-purchases.charge_purchase_budgets_store');
	Route::get('wish-purchase/{wish_purchase}/confirm-purchase-budgets', 'WishPurchaseController@confirm_purchase_budgets')->name('wish-purchases.charge-budgets');
    Route::get('wish-purchase-budgets/{purchase_budget}/confirm-purchase-budgets', 'WishPurchaseController@confirm_purchase_budgets_store')->name('wish-purchases.confirm_purchase_budgets_store');
    Route::get('wish-purchase-budgets/{wish_purchase}/wish-purchase-budgets-approved', 'WishPurchaseController@wish_purchase_budgets_approved')->name('wish-purchases.budgets_approved');
    // Route::get('wish-purchase/{wish_purchase}/pdf', 'WishPurchaseController@pdf')->name('wish-purchases.pdf');
	Route::post('wish-purchase', 'WishPurchaseController@store')->name('wish-purchase.store');

	Route::get('wish-purchase/{restocking}/pdf', 'WishPurchaseController@pdf')->name('wish-purchases.pdf');


	Route::get('purchase-order', 'PurchaseOrderController@index')->name('purchase-order');
	Route::get('purchase-order/create', 'PurchaseOrderController@create')->name('purchase-order-create');
	Route::get('purchase-order/{wish_purchase}', 'PurchaseOrderController@show')->name('purchase-order-create');
    Route::post('purchase-orders', 'PurchaseOrderController@store')->name('purchase-order.store');
    // Route::get('ajax/purchases_providers', 'ProveedorController@ajax_providers')->name('ajax.providers');

	Route::get('purchase-movement', 'PurchaseMovementsController@index')->name('purchase-movement');
	Route::get('purchase-movement/create', 'PurchaseMovementsController@create')->name('purchase-movement-create');
	Route::get('purchase-movement/{purchase_movement}', 'PurchaseMovementsController@show')->name('purchase-movement-show');
	Route::post('purchase-movement', 'PurchaseMovementsController@store')->name('purchase-movement-store');
	Route::get('ajax/purchases-movements', 'PurchaseMovementsController@ajax_purchases_movements')->name('ajax.purchases-movements');
	Route::get('ajax/purchases-products-movements', 'PurchaseMovementsController@ajax_purchases_products_movements')->name('ajax.purchases-products-movements');
	Route::get('search/provider-stamped', 'WishPurchaseController@searchProviderStamped')->name('provider-stamped.search');
    Route::get('ajax/purchases_products_last', 'ArticuloController@ajax_purchases_last')->name('ajax.products-purchases-last');

	Route::get('purchase', 'PurchaseController@index')->name('purchase');
	Route::get('purchase/create', 'PurchaseController@create')->name('purchase-create');
	Route::get('purchase/{purchase}', 'PurchaseController@show')->name('purchase-show');
	Route::post('purchase', 'PurchaseController@store')->name('purchase.store');
	Route::get('purchase/{purchase}/pdf', 'PurchaseController@pdf')->name('purchases.pdf');

    Route::get('ajax/purchases_products_orders', 'RawMaterialsController@ajax_purchases_orders')->name('ajax.products-purchases-orders');
	Route::get('ajax/raw-material', 'RawMaterialsController@ajax_purchases_products')->name('ajax.purchases-products');
	Route::get('ajax/purchases/note-credits', 'PurchaseController@ajax_purchases_note_credits')->name('ajax.invoices-purchases');

	Route::get('inventories', 'PurchasesProductInventoriesController@index')->name('inventories');
    Route::get('inventories/create', 'PurchasesProductInventoriesController@create')->name('inventories.create');
    Route::post('inventories', 'PurchasesProductInventoriesController@store')->name('inventories.store');
    Route::get('inventories/{purchases_product_inventory}', 'PurchasesProductInventoriesController@show')->name('inventories.show');
    Route::get('inventories/{purchases_product_inventory}/pdf', 'PurchasesProductInventoriesController@pdf')->name('inventories.pdf');
	Route::get('inventories/{purchases_product_inventory}/confirm-inventory', 'PurchasesProductInventoriesController@confirm_inventory')->name('inventories.confirm-inventory');
	Route::get('reports/stock-product-purchases', 'ReportsController@stock_product_purchases_report')->name('reports.stock-product-purchases');
	Route::get('reports/stock-product-purchases-xls', 'ReportsController@stock_product_purchases_report_excel')->name('reports.stock-product-purchases-xls');
	Route::get('reports/purchases_report', 'ReportsController@purchases_report')->name('reports.purchases_report');
	Route::get('reports/purchases_report/pdf', 'ReportsController@purchases_report_pdf')->name('reports.purchases_report.pdf');






});
