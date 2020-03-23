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

Route::post('register', 'PetugasController@register');
Route::post('login', 'PetugasController@login');

Route::post('tambah_pelanggan', 'pelangganController@store')->Middleware('jwt.verify');
Route::post('tambah_jenis', 'jenisController@store')->Middleware('jwt.verify');
Route::post('tambah_transaksi', 'transaksiController@store')->Middleware('jwt.verify');
Route::post('tambah_detail', 'detailController@store')->Middleware('jwt.verify');

Route::put('ubah_pelanggan/{id}', 'pelangganController@update')->Middleware('jwt.verify');
Route::put('ubah_jenis/{id}', 'jenisController@update')->Middleware('jwt.verify');
Route::put('ubah_transaksi/{id}', 'transaksiController@update')->Middleware('jwt.verify');
Route::put('ubah_detail/{id}', 'detailController@update')->Middleware('jwt.verify');

Route::delete('hapus_pelanggan/{id}', 'pelangganController@destroy')->Middleware('jwt.verify');
Route::delete('hapus_jenis/{id}', 'jenisController@destroy')->Middleware('jwt.verify');
Route::delete('hapus_transaksi/{id}', 'transaksiController@destroy')->Middleware('jwt.verify');
Route::delete('hapus_detail/{id}', 'detailController@destroy')->Middleware('jwt.verify');

Route::post('transaksi', 'transaksiController@get_transaksi')->Middleware('jwt.verify');
