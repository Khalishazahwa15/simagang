<?php
use App\Core\Router;

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/alur', 'HomeController@alur');
$router->get('/persyaratan', 'HomeController@persyaratan');
$router->get('/faq', 'HomeController@faq');
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');
$router->get('/forgot-password', 'AuthController@forgotPassword');
$router->post('/forgot-password', 'AuthController@forgotPassword');
$router->get('/reset-password', 'AuthController@resetPassword');
$router->post('/reset-password', 'AuthController@resetPassword');

// Notifications
$router->post('/notifikasi/tandai-terbaca', 'NotificationController@markAsRead');

// Mahasiswa routes
$router->get('/mahasiswa/dashboard', 'MahasiswaController@dashboard');
$router->get('/mahasiswa/pengajuan', 'MahasiswaController@pengajuan');
$router->post('/mahasiswa/pengajuan/submit', 'MahasiswaController@submitPengajuan');
$router->get('/mahasiswa/status', 'MahasiswaController@status');
$router->get('/mahasiswa/dokumen', 'MahasiswaController@dokumen');
$router->get('/mahasiswa/dokumen/download/:id', 'MahasiswaController@downloadDokumen');
$router->post('/mahasiswa/pengajuan/revisi', 'MahasiswaController@revisi');
$router->post('/mahasiswa/pengajuan/respon-tawaran', 'MahasiswaController@responTawaran');
$router->get('/mahasiswa/pengunduran-diri', 'MahasiswaController@pengunduranDiri');
$router->post('/mahasiswa/pengunduran-diri', 'MahasiswaController@submitPengunduranDiri');
$router->get('/mahasiswa/profil', 'MahasiswaController@profil');
$router->post('/mahasiswa/profil/update', 'MahasiswaController@updateProfil');
$router->post('/mahasiswa/profil/password', 'MahasiswaController@updatePassword');

// Sekretariat routes
$router->get('/sekretariat/dashboard', 'SekretariatController@dashboard');
$router->get('/sekretariat/pengajuan', 'SekretariatController@pengajuan');
$router->get('/sekretariat/pengajuan/detail', 'SekretariatController@pengajuanDetail');
$router->get('/sekretariat/pengajuan/detail/:id', 'SekretariatController@pengajuanDetail');
$router->post('/sekretariat/pengajuan/detail/:id', 'SekretariatController@pengajuanDetail');
$router->get('/sekretariat/peserta', 'SekretariatController@peserta');
$router->get('/sekretariat/dokumen', 'SekretariatController@dokumen');
$router->get('/sekretariat/laporan', 'SekretariatController@laporan');
$router->get('/sekretariat/laporan/export', 'SekretariatController@exportLaporan');
$router->get('/sekretariat/dokumen/download/:id', 'SekretariatController@downloadDokumen');
$router->post('/sekretariat/pengajuan/detail/:id/upload-final', 'SekretariatController@uploadFinal');
$router->post('/sekretariat/pengajuan/detail/:id/mulai-magang', 'SekretariatController@mulaiMagang');
$router->post('/sekretariat/pengajuan/detail/:id/tandai-selesai', 'SekretariatController@tandaiSelesai');
$router->post('/sekretariat/pengajuan/detail/:id/verifikasi-mundur', 'SekretariatController@verifikasiMundur');
$router->post('/sekretariat/pengajuan/tawarkan', 'SekretariatController@tawarkan');
$router->post('/sekretariat/pengajuan/finalisasi-tawaran', 'SekretariatController@finalisasiTawaran');

// Admin routes
$router->get('/admin/dashboard', 'AdminController@dashboard');
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/bidang', 'AdminController@bidang');
$router->get('/admin/audit-log', 'AdminController@auditLog');
$router->post('/admin/bidang/store', 'AdminController@storeDivisi');
$router->post('/admin/bidang/update/:id', 'AdminController@updateDivisi');
$router->post('/admin/bidang/toggle/:id', 'AdminController@toggleStatusDivisi');
$router->post('/admin/users/store', 'AdminController@storeUser');
$router->post('/admin/users/update/:id', 'AdminController@updateUser');
$router->post('/admin/users/toggle/:id', 'AdminController@toggleStatusUser');
