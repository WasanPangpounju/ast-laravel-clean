<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\materialstockController;
use App\Http\Controllers\materialstoreController;
use App\Http\Controllers\StuffController;
use App\Http\Controllers\MaterialOutsideController;
use App\Http\Controllers\ManufactureController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\FabricimportController;
use App\Http\Controllers\OrdershippedController;
use App\Http\Controllers\StockfabricController;
use App\Http\Controllers\FabricoutController;
use App\Http\Controllers\FabricdepositController;
use App\Http\Controllers\FabriccheckController;
use App\Http\Controllers\FabricoutDepositController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('employee', EmployeeController::class );

Route::resource('customer', CustomerController::class );

Route::resource('order', OrderController::class );

Route::resource('supplier', SupplierController::class );

Route::resource('package', PackageController::class );
Route::resource('material', MaterialController::class );
Route::resource('materialstock', materialstockController::class );
Route::resource('materialstore', materialstoreController::class );
Route::resource('stuff', StuffController::class );
Route::resource('materialoutside', MaterialOutsideController::class );
Route::resource('manufacture', ManufactureController::class );
Route::resource('inventory', InventoryController::class );
Route::resource('fabricimport', FabricimportController::class );

// ตรวจสอบคีย์ผ้าซื้อเข้าสต็อก (สรุปเป็นกลุ่ม refId)
Route::get('/fabricimport-check', [FabricimportController::class, 'checkIndex'])
    ->name('fabricimport.check.index');

// หน้ารายละเอียดชุดตาม refId
Route::get('/fabricimport-check/{refId}', [FabricimportController::class, 'checkShow'])
    ->name('fabricimport.check.show');

// ลบ “แถวเดียว” ในชุดซื้อเข้า
Route::delete('/fabricimport-check/{refId}/items/{id}', [FabricimportController::class, 'checkDestroyItem'])
    ->name('fabricimport.check.destroyItem');

// ลบ “ทั้งชุด” ตาม refId
Route::delete('/fabricimport-check/{refId}', [FabricimportController::class, 'checkDestroy'])
    ->name('fabricimport.check.destroy');

Route::resource('ordershipped', OrdershippedController::class );

// ✅ เพิ่ม GET สำหรับ reload หน้า inspect หลังลบ
Route::get('/stockfabric-check', [StockfabricController::class, 'inspectGet'])
    ->name('stockfabric.inspect.get');

// ✅ POST สำหรับตอนกดปุ่ม "ตรวจสอบ"
Route::post('/stockfabric-check', [StockfabricController::class, 'inspect'])
    ->name('stockfabric.inspect');

// ✅ เปลี่ยน path จาก /stockfabric/inspect → /stockfabric-check
Route::post('/stockfabric-check', [StockfabricController::class, 'inspect'])->name('stockfabric.inspect');

Route::delete('/stockfabric/in/{id}', [StockfabricController::class, 'destroyIn'])->name('stockfabric.destroyIn');
Route::delete('/stockfabric/in/bulk', [StockfabricController::class, 'destroyInBulk'])->name('stockfabric.destroyInBulk');

// resource ไว้ล่างสุด
Route::resource('stockfabric', StockfabricController::class );

// Route::post('/stockfabric/inspect', [StockfabricController::class, 'inspect'])->name('stockfabric.inspect');

// // ลบรายการสต็อกเข้าแบบรายแถว
// Route::delete('/stockfabric/in/{id}', [StockfabricController::class, 'destroyIn'])->name('stockfabric.destroyIn');

// // ลบรายการสต็อกเข้าแบบทั้งชุด (ตามคีย์ที่เลือก)
// Route::delete('/stockfabric/in/bulk', [StockfabricController::class, 'destroyInBulk'])->name('stockfabric.destroyInBulk');

// Route::resource('stockfabric', StockfabricController::class );

Route::resource('fabricout', FabricoutController::class );
Route::resource('fabricdeposit', FabricdepositController::class );

Route::delete('/fabriccheck/{refId}/items/{id}', [FabriccheckController::class, 'destroyItem'])
    ->name('fabriccheck.item.destroy');
Route::resource('fabriccheck', FabriccheckController::class );


Route::resource('fabricoutdeposit', FabricoutDepositController::class );

Route::post('generate-pdf', 'PDFController@generatePDF')->name('generate-pdf');

