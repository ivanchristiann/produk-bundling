<?php

use App\Http\Controllers\AprioriController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\kategoriesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SubkategoriesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TopsisController;
use App\Http\Controllers\TransaksiPembelianController;
use App\Http\Controllers\TransaksiPenjualanController;
use App\Models\Product;
use App\Models\TransaksiPembelian;
use App\Models\TransaksiPenjualan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect('/login');
    } else {
        return redirect('/dashboard');
    }
});

Auth::routes();

Route::resource('dashboard', DashboardController::class);
Route::resource('product', ProductController::class);
Route::resource('employee', EmployeeController::class);
Route::resource('supplier', SupplierController::class);
Route::resource('customer', CustomerController::class);
Route::resource('kategories', KategoriesController::class);
Route::resource('satuan', SatuanController::class);
Route::resource('subkategories', SubkategoriesController::class);
Route::resource('transaksiPembelian', TransaksiPembelianController::class);
Route::resource('transaksiPenjualan', TransaksiPenjualanController::class);
Route::resource('stok', StokController::class);
Route::resource('apriori', AprioriController::class);
Route::resource('topsis', TopsisController::class);
 
Route::post('product/aktifkan', [ProductController::class, 'aktifkan'])->name('product.aktifkan');
Route::post('product/nonaktifkan', [ProductController::class, 'nonaktifkan'])->name('product.nonaktifkan');
Route::get('productbundling', [ProductController::class, 'produkBundling'])->name('productbundling.produkBundling');
Route::get('detailproductbundling/{id}', [ProductController::class, 'detailProdukBundling'])->name('productbundling.detailProdukBundling');
Route::get('detailproduct/{id}', [ProductController::class, 'detailProduk'])->name('product.detailProduk');
Route::get('productAktif/index/{categoryId?}/{search?}', [ProductController::class, 'indexProdukAktif'])->name('productAktif.index');
Route::get('productNonAktif/index/{categoryId?}/{search?}', [ProductController::class, 'indexProdukNonAktif'])->name('productNonAktif.index');

Route::get('createProdukBundling', [ProductController::class, 'createProdukBundling'])->name('productbundling.createProdukBundling');
Route::get('createProdukBundling/apriori/{idProduk1}/{idProduk2}', [ProductController::class, 'createProdukBundlingApriori'])->name('productbundling.produkBundlingApriori');
Route::get('createProdukBundling/substitusi/{idProduk1}/{idProduk2}/{startDate?}/{endDate?}/{metode?}', [ProductController::class, 'createProdukBundlingSubstitusi'])->name('productbundling.produkBundlingSubstitusi');

Route::get('getExpDate', [ProductController::class, 'getExpDate'])->name('productbundling.getExpDate');
Route::post('productbundling/store', [ProductController::class, 'storeProdukBundling'])->name('productbundling.store');

Route::post('supplier/aktifkan', [SupplierController::class, 'aktifkan'])->name('supplier.aktifkan');
Route::post('supplier/nonaktifkan', [SupplierController::class, 'nonaktifkan'])->name('supplier.nonaktifkan');

Route::post('employee/aktifkan', [EmployeeController::class, 'aktifkan'])->name('employee.aktifkan');
Route::post('employee/nonaktifkan', [EmployeeController::class, 'nonaktifkan'])->name('employee.nonaktifkan');

Route::get('transaksiPembelian/detail/{id}', [TransaksiPembelianController::class, 'detail'])->name('transaksiPembelian.detail');
Route::get('transaksiPembelian/pembayaran/{id}', [TransaksiPembelianController::class, 'pembayaran'])->name('transaksiPembelian.pembayaran');
Route::get('transaksiPembelian/{startDate?}/{endDate?}/{status?}', [TransaksiPembelianController::class, 'index'])->name('transaksiPembelian.index');

Route::get('transaksiPenjualan/detail/{id}', [TransaksiPenjualanController::class, 'detail'])->name('transaksiPenjualan.detail');
Route::get('transaksiPenjualan/{startDate?}/{endDate?}', [TransaksiPenjualanController::class, 'index'])->name('transaksiPenjualan.index');

Route::get('apriori/proses', [AprioriController::class, 'proses'])->name('apriori.proses');
Route::get('proses/topsis/{idProduk?}/{idSubKategori?}/{startDate?}/{endDate?}', [TopsisController::class, 'proses'])->name('topsis.proses');

Route::get('/ubahpassword', [EmployeeController::class, 'ubahPassword'])->name('ubahPassword');
Route::post('/newPassword', [EmployeeController::class, 'newPassword'])->name('newPassword');


Auth::routes();

Route::get('/home', [DashboardController::class, 'index'])->name('home');

