<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RajaOngkirController;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    // return view('backend.v_login.login');
    return redirect()->route('beranda');
});

//frontend
Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])->name('backend.beranda')->middleware('auth');

Route::get('backend/login', [LoginController::class, 'loginBackend'])->name('backend.login');
Route::post('backend/login', [LoginController::class, 'authenticateBackend'])->name('backend.login');
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout');

// Route User
Route::resource('backend/user', UserController::class, ['as' => 'backend'])->middleware('auth');
Route::get('backend/laporan/formuser', [UserController::class, 'formUser'])->name('backend.laporan.formuser')->middleware('auth');
Route::post('backend/laporan/cetakuser', [UserController::class, 'cetakUser'])->name('backend.laporan.cetakuser')->middleware('auth');

// Route Kategori
Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend'])->middleware('auth');

// Route Produk
Route::resource('backend/produk', ProdukController::class, ['as' => 'backend'])->middleware('auth');
Route::post('foto-produk/store', [ProdukController::class, 'storeFoto'])->name('backend.foto_produk.store')->middleware('auth');
Route::delete('foto-produk/{id}', [ProdukController::class, 'destroyFoto'])->name('backend.foto_produk.destroy')->middleware('auth');
Route::get('backend/laporan/formproduk', [ProdukController::class, 'formProduk'])->name('backend.laporan.formproduk')->middleware('auth');
Route::post('backend/laporan/cetakproduk', [ProdukController::class, 'cetakProduk'])->name('backend.laporan.cetakproduk')->middleware('auth');
Route::get('/produk/detail/{id}', [ProdukController::class, 'detail'])->name('produk.detail');
Route::get('/produk/kategori/{id}', [ProdukController::class, 'produkKategori'])->name('produk.kategori');
Route::get('/produk/all', [ProdukController::class, 'produkAll'])->name('produk.all');

// Route Pesanan
Route::get('backend/pesanan-proses', [OrderController::class, 'statusProses'])->name('backend.pesanan.proses')->middleware('auth');
Route::get('backend/pesanan-selesai', [OrderController::class, 'statusSelesai'])->name('backend.pesanan.selesai')->middleware('auth');
Route::get('pesanan/detail/{id}', [OrderController::class, 'statusDetail'])->name('backend.pesanan.detail')->middleware('auth');
Route::get('pesanan/invoice/{id}', [OrderController::class, 'invoiceBackend'])->name('backend.pesanan.invoice')->middleware('auth');
Route::match(['get', 'post'], 'pesanan/update/{id}', [OrderController::class, 'statusUpdate'])->name('backend.pesanan.update')->middleware('auth');
Route::get('backend/laporan/form-pesanan-selesai', [OrderController::class, 'formOrderSelesai'])->name('backend.laporan.formpesananselesai')->middleware('auth');
Route::get('backend/laporan/form-pesanan-proses', [OrderController::class, 'formOrderProses'])->name('backend.laporan.formpesananproses')->middleware('auth');
Route::match(['get','post'],'backend/laporan/cetak-pesanan-proses', [OrderController::class, 'cetakOrderProses'])->name('backend.laporan.cetakpesananproses')->middleware('auth');
Route::match(['get','post'],'backend/laporan/cetak-pesanan-selesai', [OrderController::class, 'cetakOrderSelesai'])->name('backend.laporan.cetakpesananselesai')->middleware('auth');
// Route untuk Customer
Route::resource('backend/customer', CustomerController::class, ['as' => 'backend'])->middleware('auth');



// Route untuk menampilkan halaman akun customer

Route::get('/customer/akun/{id}', [CustomerController::class, 'akun'])->name('customer.akun')->middleware('is.customer');
Route::put('/customer/akun/{id}/update', [CustomerController::class, 'updateAkun'])->name('customer.akun.update')->middleware('is.customer');

// Group route untuk customer
Route::middleware('is.customer')->group(function () {
    // Route untuk menampilkan halaman akun customer
    Route::get('/customer/akun/{id}', [CustomerController::class, 'akun'])->name('customer.akun');
    // Route untuk mengupdate data akun customer
    Route::put('/customer/updateakun/{id}', [CustomerController::class, 'updateAkun'])->name('customer.updateakun');
    // Route untuk menambahkan produk ke keranjang 
    Route::post('add-to-cart/{id}', [OrderController::class, 'addToCart'])->name('order.addToCart');
    Route::get('cart', [OrderController::class, 'viewCart'])->name('order.cart');
    Route::post('cart/update/{id}', [OrderController::class, 'updateCart'])->name('order.updateCart');
    Route::post('remove/{id}', [OrderController::class, 'removeFromCart'])->name('order.remove');
    Route::post('update-ongkir', [OrderController::class, 'updateOngkir'])->name('order.update-ongkir');
    Route::match(['get', 'post'], 'select-payment', [OrderController::class, 'selectPayment'])->name('order.selectpayment');
    //midtrans
    Route::get('select-payment', [OrderController::class, 'selectPayment'])->name('order.selectpayment');
    Route::post('/midtrans-callback', [OrderController::class, 'callback']);
    Route::get('/order/complete', [OrderController::class, 'complete'])->name('order.complete');
    // Route history
    Route::get('history', [OrderController::class, 'orderHistory'])->name('order.history');
    Route::get('order/invoice/{id}', [OrderController::class, 'invoiceFrontend'])->name('order.invoice');




    Route::get('select-shipping', [OrderController::class, 'selectShipping'])->name('order.selectShipping');
    Route::post('/select-shipping/hitung', [RajaOngkirController::class, 'getCost'])->name('cek-ongkir.hitung');
});

//API Google
Route::get('/auth/redirect', [CustomerController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/google/callback', [CustomerController::class, 'callback'])->name('auth.callback');
// Logout
Route::post('/logout', [CustomerController::class, 'logout'])->name('customer.logout');

// Route::get('/cek-ongkir', function () {
//     return view('ongkir');
// });

// Route::get('/domestic', [RajaOngkirController::class, 'getDomestic']);
// Route::post('/cost', [RajaOngkirController::class, 'getCost']);

Route::get('/domestic-destination', [RajaOngkirController::class, 'getDomestic']);
Route::view('/domestic-view', 'domestik');

Route::view('/cek-ongkir', 'cost');
Route::post('/cek-ongkir/hitung', [RajaOngkirController::class, 'getCost'])->name('cek-ongkir.hitung');
