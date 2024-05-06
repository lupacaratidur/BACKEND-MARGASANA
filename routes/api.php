<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Dashboard\AuthController;
use App\Http\Controllers\API\Dashboard\BeritaController;
use Tymon\JWTAuth\Http\Middleware\Authenticate;
use App\Http\Controllers\API\Dashboard\BPDController;
use App\Http\Controllers\API\Dashboard\GaleriController;
use App\Http\Controllers\API\Dashboard\PemerintahDesaController;
use App\Http\Controllers\API\Dashboard\PengaduanController;
use App\Http\Controllers\API\Dashboard\PengajuanSuratController;
use App\Http\Controllers\API\Dashboard\TanggapanController;
use App\Models\PemerintahDesa;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// API untuk frontend landing page


//API untuk dashboard
//Authentikasi
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

//Berita
Route::group(['middleware' => ['jwt.auth', 'PetugasAdmin']], function () {
    Route::get('berita', [BeritaController::class, 'index']);
    Route::get('berita/{id}', [BeritaController::class, 'show']);
    Route::post('berita', [BeritaController::class, 'store']);
    Route::post('berita/{berita}', [BeritaController::class, 'update']);
    Route::delete('berita/{berita}', [BeritaController::class, 'destroy']);
});

//BPD
Route::group(['middleware' => ['jwt.auth', 'PetugasAdmin']], function () {
    Route::get('/bpd', [BPDController::class, 'index']);
    Route::get('/bpd/{id}', [BPDController::class, 'show']);
    Route::post('/bpd', [BPDController::class, 'store']);
    Route::put('/bpd/{id}', [BPDController::class, 'update']);
    Route::delete('/bpd/{id}', [BPDController::class, 'destroy']);
});

//Galeri
Route::group(['middleware' => ['jwt.auth', 'PetugasAdmin']], function () {
    Route::get('galeri', [GaleriController::class, 'index']);
    Route::get('galeri/{id}', [GaleriController::class, 'show']);
    Route::post('galeri', [GaleriController::class, 'store']);
    Route::post('galeri/{id}', [GaleriController::class, 'update']); //masih error
    Route::delete('galeri/{galeri}', [GaleriController::class, 'destroy']);
});

//Pemerintah Desa
Route::group(['middleware' => ['jwt.auth', 'PetugasAdmin']], function () {
    Route::get('pemerintah_desa', [PemerintahDesaController::class, 'index']);
    Route::get('pemerintah_desa/{id}', [PemerintahDesaController::class, 'show']);
    Route::post('pemerintah_desa', [PemerintahDesaController::class, 'store']);
    Route::post('pemerintah_desa/{pemerintah_desa}', [PemerintahDesaController::class, 'update']); //masih error
    Route::delete('pemerintah_desa/{pemerintah_desa}', [PemerintahDesaController::class, 'destroy']);
});

//Pengaduan masyarakat
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/pengaduan/semua', [PengaduanController::class, 'index']);
    Route::get('/pengaduan/belum', [PengaduanController::class, 'belum']);
    Route::get('/pengaduan/proses', [PengaduanController::class, 'proses']);
    Route::get('/pengaduan/selesai', [PengaduanController::class, 'selesai']);
    Route::post('/pengaduan', [PengaduanController::class, 'store']);
    Route::put('/pengaduan/{pengaduan}', [PengaduanController::class, 'update']);
    Route::delete('/pengaduan/{pengaduan}', [PengaduanController::class, 'destroy']);
    Route::post('/pengaduan/response/{pengaduan}', [PengaduanController::class, 'response']);
});

//Tanggapan Petugas/Admin
Route::group(['middleware' => ['jwt.auth', 'PetugasAdmin']], function () {
    Route::delete('/tanggapan/{tanggapan}', [TanggapanController::class, 'destroy']);
});

//Pengajuan surat
Route::group(['middleware' => 'jwt.auth'], function () {
    Route::resource('/pengajuan-surat', PengajuanSuratController::class);
    Route::post('/pengajuan-surat/{}', [PengajuanSuratController::class, 'update']);
    Route::get('/pengajuan-surat/{pengajuan_surat}/download', [PengajuanSuratController::class, 'download']);
    Route::put('/pengajuan-surat/{id}/approve', [PengajuanSuratController::class, 'approve']);
    Route::put('/pengajuan-surat/{id}/reject', [PengajuanSuratController::class, 'reject']);
    Route::get('/pengajuan-surat/{pengajuan_surat}/preview', [PengajuanSuratController::class, 'preview']);
});
