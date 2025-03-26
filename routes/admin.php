<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\TransferController;
use App\Http\Controllers\Admin\DealerController;
use App\Http\Controllers\Admin\CardPackageController;
use App\Http\Controllers\Admin\NoteVoucherTypeController;
use App\Http\Controllers\Admin\NoteVoucherController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\RequestBalanceController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\CategorySubscriptionController;
use App\Http\Controllers\Admin\PayInvoiceController;
use App\Http\Controllers\Admin\TransferBankController;
use App\Http\Controllers\Reports\InventoryReportController;
use App\Http\Controllers\Reports\OrderReportController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ReceivableController;
use App\Http\Controllers\Admin\SectionUserController;
use App\Http\Controllers\Reports\ProductReportController;
use App\Http\Controllers\Reports\TaxReportController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Permission\Models\Permission;
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

define('PAGINATION_COUNT',11);
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {

    //Search Product in Jquery
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');



 Route::group(['prefix'=>'admin','middleware'=>'auth:admin'],function(){
 Route::get('/',[DashboardController::class,'index'])->name('admin.dashboard');
 Route::get('logout',[LoginController::class,'logout'])->name('admin.logout');

 Route::get('note-vouchers/import', [NoteVoucherController::class, 'importForm'])
 ->name('noteVouchers.importForm');
Route::post('note-vouchers/import', [NoteVoucherController::class, 'importExcel'])
 ->name('noteVouchers.importExcel');
Route::get('note-vouchers/template', [NoteVoucherController::class, 'downloadTemplate'])
 ->name('noteVouchers.downloadTemplate');

/*         start  customer                */
Route::get('/customer/index',[CustomerController::class,'index'])->name('admin.customer.index');
Route::get('/customer/create',[CustomerController::class,'create'])->name('admin.customer.create');
Route::post('/customer/store',[CustomerController::class,'store'])->name('admin.customer.store');
Route::get('/customer/show/{id}',[CustomerController::class,'show'])->name('admin.customer.show');
Route::get('/customer/edit/{id}',[CustomerController::class,'edit'])->name('admin.customer.edit');
Route::post('/customer/update/{id}',[CustomerController::class,'update'])->name('admin.customer.update');
Route::post('/customer/ajax_search',[CustomerController::class,'ajax_search'])->name('admin.customer.ajax_search');
Route::get('/customer/export', [CustomerController::class,'export'])->name('admin.customer.export');
/*         end  customer                */



/*         start  Dealers               */
Route::get('/dealers/index',[DealerController::class,'index'])->name('admin.dealers.index');
Route::get('/dealers/create',[DealerController::class,'create'])->name('admin.dealers.create');
Route::post('/dealers/store',[DealerController::class,'store'])->name('admin.dealers.store');
Route::get('/dealers/show/{id}',[DealerController::class,'show'])->name('admin.dealers.show');
Route::get('/dealers/edit/{id}',[DealerController::class,'edit'])->name('admin.dealers.edit');
Route::post('/dealers/update/{id}',[DealerController::class,'update'])->name('admin.dealers.update');
Route::get('/dealers/delete/{id}',[DealerController::class,'delete'])->name('admin.dealers.delete');
Route::post('/dealers/ajax_search',[DealerController::class,'ajax_search'])->name('admin.dealers.ajax_search');
Route::get('/dealers/export', [DealerController::class,'export'])->name('admin.dealers.export');
/*         end  Dealers               */



/*         start  update login admin                 */
Route::get('/admin/edit/{id}',[LoginController::class,'editlogin'])->name('admin.login.edit');
Route::post('/admin/update/{id}',[LoginController::class,'updatelogin'])->name('admin.login.update');
/*         end  update login admin                */

/// Role and permission
Route::resource('employee', 'App\Http\Controllers\Admin\EmployeeController',[ 'as' => 'admin']);
Route::get('role', 'App\Http\Controllers\Admin\RoleController@index')->name('admin.role.index');
Route::get('role/create', 'App\Http\Controllers\Admin\RoleController@create')->name('admin.role.create');
Route::get('role/{id}/edit', 'App\Http\Controllers\Admin\RoleController@edit')->name('admin.role.edit');
Route::patch('role/{id}', 'App\Http\Controllers\Admin\RoleController@update')->name('admin.role.update');
Route::post('role', 'App\Http\Controllers\Admin\RoleController@store')->name('admin.role.store');
Route::post('admin/role/delete', 'App\Http\Controllers\Admin\RoleController@delete')->name('admin.role.delete');

Route::get('/permissions/{guard_name}', function($guard_name){
    return response()->json(Permission::where('guard_name',$guard_name)->get());
});


/*         start  setting                */
Route::get('/setting/index',[SettingController::class,'index'])->name('admin.setting.index');
Route::get('/setting/create',[SettingController::class,'create'])->name('admin.setting.create');
Route::post('/setting/store',[SettingController::class,'store'])->name('admin.setting.store');
Route::get('/setting/edit/{id}',[SettingController::class,'edit'])->name('admin.setting.edit');
Route::post('/setting/update/{id}',[SettingController::class,'update'])->name('admin.setting.update');

/*         end  setting                */


// Notification
Route::get('/notifications/create',[NotificationController::class,'create'])->name('notifications.create');
Route::post('/notifications/send',[NotificationController::class,'send'])->name('notifications.send');

// Request BALANCE
Route::get('/requestBalances/{id}/approve', [RequestBalanceController::class, 'approve'])->name('requestBalances.approve');
Route::get('/requestBalances/{id}/reject', [RequestBalanceController::class, 'reject'])->name('requestBalances.reject');

// payInvoices
Route::get('/payInvoices/{id}/approve', [PayInvoiceController::class, 'approve'])->name('payInvoices.approve');
Route::get('/payInvoices/{id}/reject', [PayInvoiceController::class, 'reject'])->name('payInvoices.reject');

// TRANSFER BANKS
Route::get('/transferBanks/{id}/approve', [RequestBalanceController::class, 'approve'])->name('transferBanks.approve');
Route::get('/transferBanks/{id}/reject', [RequestBalanceController::class, 'reject'])->name('transferBanks.reject');


Route::prefix('pages')->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('pages.index');
    Route::get('/create', [PageController::class, 'create'])->name('pages.create');
    Route::post('/store', [PageController::class, 'store'])->name('pages.store');
    Route::get('/edit/{id}', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/update/{id}', [PageController::class, 'update'])->name('pages.update');
    Route::delete('/delete/{id}', [PageController::class, 'destroy'])->name('pages.destroy');
});


//Reports
Route::get('/inventory_report', [InventoryReportController::class, 'index'])->name('inventory_report');
Route::get('/order_report', [OrderReportController::class, 'index'])->name('order_report');
Route::get('/product_move', [ProductReportController::class, 'index'])->name('product_move');
Route::get('/tax_report', [TaxReportController::class, 'index'])->name('tax_report');

// Orders of game
Route::get('/orders/game', [OrderController::class, 'indexOfGame'])->name('orders.game');
Route::get('/orders/charge/{id}', [OrderController::class, 'charge'])->name('orders.charge');
Route::post('/orders/notification/send', [OrderController::class, 'sendNotificationToUser'])->name('orders.notification.send');


// Resource Route
Route::resource('noteVoucherTypes', NoteVoucherTypeController::class);
Route::resource('noteVouchers', NoteVoucherController::class);
Route::resource('warehouses', WarehouseController::class);
Route::resource('offers', OfferController::class);
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::resource('units', UnitController::class);
Route::resource('orders', OrderController::class);
Route::resource('wallets', WalletController::class);
Route::resource('transfers', TransferController::class);
Route::resource('cardPackages', CardPackageController::class);
Route::resource('requestBalances', RequestBalanceController::class);
Route::resource('categorySubscriptions', CategorySubscriptionController::class);
Route::resource('payInvoices', PayInvoiceController::class);
Route::resource('transferBanks', TransferBankController::class);
Route::resource('banners', BannerController::class);
Route::resource('sectionUsers', SectionUserController::class);
Route::resource('questions', QuestionController::class);
Route::resource('receivables', ReceivableController::class);


});
});



Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>'guest:admin'],function(){
    Route::get('login',[LoginController::class,'show_login_view'])->name('admin.showlogin');
    Route::post('login',[LoginController::class,'login'])->name('admin.login');

});







