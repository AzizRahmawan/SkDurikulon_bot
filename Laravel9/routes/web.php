<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



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

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('/dashboard')->group(function () {
    Route::get('/sktm/create/nik', 'App\Http\Controllers\Backend\SktmController@cariNik');
    Route::get('/skp/create/nik', 'App\Http\Controllers\Backend\SkpController@cariNik');
    Route::get('/skd/create/nik', 'App\Http\Controllers\Backend\SkdController@cariNik');
    Route::get('/sktm/cetak/{id_sktm}/{nik}','App\Http\Controllers\Backend\SktmController@view');
    Route::get('/skp/cetak/{id_skp}/{nik}','App\Http\Controllers\Backend\SkpController@view');
    Route::get('/skd/cetak/{id_skd}/{nik}','App\Http\Controllers\Backend\SkdController@view');
    Route::get('/', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/sktm', App\Http\Controllers\Backend\SktmController::class);
    Route::resource('/skp', App\Http\Controllers\Backend\SkpController::class);
    Route::resource('/skd', App\Http\Controllers\Backend\SkdController::class);
    Route::resource('/penduduk', App\Http\Controllers\Backend\PendudukController::class);
    Route::resource('/req_edit_nama', App\Http\Controllers\Backend\Req_Edit\EditNamaController::class);
    Route::resource('/req_edit_nik', App\Http\Controllers\Backend\Req_Edit\EditNikController::class);
    Route::resource('/req_edit_no_kk', App\Http\Controllers\Backend\Req_Edit\EditNoKkController::class);
    Route::resource('/req_edit_kepala', App\Http\Controllers\Backend\Req_Edit\EditKepalaController::class);
    Route::resource('/req_edit_tgl_lahir', App\Http\Controllers\Backend\Req_Edit\EditTglLahirController::class);
    Route::resource('/req_edit_pekerjaan', App\Http\Controllers\Backend\Req_Edit\EditPekerjaanController::class);
    Route::resource('/req_edit_status', App\Http\Controllers\Backend\Req_Edit\EditStatusController::class);
    Route::resource('/req_edit_agama', App\Http\Controllers\Backend\Req_Edit\EditAgamaController::class);
    Route::resource('/req_edit_pendidikan', App\Http\Controllers\Backend\Req_Edit\EditPendidikanController::class);
    Route::resource('/req_edit_alamat', App\Http\Controllers\Backend\Req_Edit\EditAlamatController::class);
});


//Route::get('/surat',[SuratController::class, 'index']);
Route::get('/sktm/{id_sktm}/{id_user2}/{nik}','App\Http\Controllers\SuratController@view');
Route::get('/skp/{id_skp}/{id_user2}/{nik}','App\Http\Controllers\SkpController@view');
Route::get('/skd/{id_skd}/{id_user}/{nik}','App\Http\Controllers\SkdController@view');
Route::match(['get', 'post'], '/botman', 'App\Http\Controllers\Bot1Controller@handle');
Route::match(['get', 'post'], '/bot1', 'App\Http\Controllers\Bot2Controller@handle');
//Route::match(['get', 'post'], '/botman', 'App\Http\Controllers\BotmanController@handle');
Route::get('dropdown', 'App\Http\Controllers\DropdownController@index');
Route::get('/pegawai','App\Http\Controllers\DropdownController@index');
Route::get('/pegawai/cari','App\Http\Controllers\DropdownController@cari');


Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
?>
