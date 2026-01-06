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
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ReceivableController;
use App\Http\Controllers\Admin\SectionUserController;
use App\Http\Controllers\Admin\UserController;
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





 Route::group(['prefix'=>'admin','middleware'=>'auth:admin'],function(){
 Route::get('/',[DashboardController::class,'index'])->name('admin.dashboard');
 Route::get('logout',[LoginController::class,'logout'])->name('admin.logout');






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



// Notification
Route::get('/notifications/create',[NotificationController::class,'create'])->name('notifications.create');
Route::post('/notifications/send',[NotificationController::class,'send'])->name('notifications.send');




Route::prefix('pages')->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('pages.index');
    Route::get('/create', [PageController::class, 'create'])->name('pages.create');
    Route::post('/store', [PageController::class, 'store'])->name('pages.store');
    Route::get('/edit/{id}', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/update/{id}', [PageController::class, 'update'])->name('pages.update');
    Route::delete('/delete/{id}', [PageController::class, 'destroy'])->name('pages.destroy');
});


//Reports

// other route
Route::patch('users/{user}/toggle-activation', [UserController::class, 'toggleActivation'])
    ->name('users.toggle-activation');

Route::patch('drivers/{driver}/toggle-activation', [DriverController::class, 'toggleActivation'])
    ->name('drivers.toggle-activation');
    
Route::get('wallets/statistics/overview', [WalletController::class, 'statistics'])
    ->name('wallets.statistics');


Route::get('transactions/{transaction}/edit', [WalletController::class, 'edit'])->name('transactions.edit');
Route::put('transactions/{transaction}', [WalletController::class, 'update'])->name('transactions.update');
Route::delete('transactions/{transaction}', [WalletController::class, 'destroy'])->name('transactions.destroy');
    
// Additional order routes
Route::get('orders/statistics/overview', [OrderController::class, 'statistics'])
    ->name('orders.statistics');

Route::get('orders/status/{status}', [OrderController::class, 'byStatus'])
    ->name('orders.by-status');

Route::get('orders/today', [OrderController::class, 'ordersToday'])
    ->name('orders.today');

Route::patch('orders/{order}/update-status', [OrderController::class, 'updateStatus'])
    ->name('orders.update-status');

Route::patch('orders/{order}/assign-driver', [OrderController::class, 'assignDriver'])
    ->name('orders.assign-driver');
    
    Route::patch('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
// end other route


// Resource Route
Route::resource('orders', OrderController::class);
Route::resource('wallets', WalletController::class);
Route::resource('users', UserController::class);
Route::resource('drivers', DriverController::class);
Route::resource('settings', SettingController::class);
Route::resource('cities', CityController::class);


});
});



Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware'=>'guest:admin'],function(){
    Route::get('login',[LoginController::class,'show_login_view'])->name('admin.showlogin');
    Route::post('login',[LoginController::class,'login'])->name('admin.login');

});







