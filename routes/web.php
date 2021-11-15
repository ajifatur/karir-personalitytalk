<?php

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

// Guest Capabilities
Route::group(['middleware' => ['guest']], function(){

	// Home
	Route::get('/', function () {
	   return redirect('/login');
	})->name('home');

	// Login
	Route::get('/login', 'Auth\LoginController@showLoginForm');
	Route::post('/login', 'Auth\LoginController@login');

	// Applicant Register
	Route::get('/lowongan/{code}/daftar/step-1', 'ApplicantRegisterController@showRegistrationFormStep1');
	Route::post('/lowongan/{code}/daftar/step-1', 'ApplicantRegisterController@submitRegistrationFormStep1');
	Route::get('/lowongan/{code}/daftar/step-2', 'ApplicantRegisterController@showRegistrationFormStep2');
	Route::post('/lowongan/{code}/daftar/step-2', 'ApplicantRegisterController@submitRegistrationFormStep2');
	Route::get('/lowongan/{code}/daftar/step-3', 'ApplicantRegisterController@showRegistrationFormStep3');
	Route::post('/lowongan/{code}/daftar/step-3', 'ApplicantRegisterController@submitRegistrationFormStep3');
	Route::get('/lowongan/{code}/daftar/step-4', 'ApplicantRegisterController@showRegistrationFormStep4');
	Route::post('/lowongan/{code}/daftar/step-4', 'ApplicantRegisterController@submitRegistrationFormStep4');
	Route::get('/lowongan/{code}/daftar/step-5', 'ApplicantRegisterController@showRegistrationFormStep5');
	Route::post('/lowongan/{code}/daftar/step-5', 'ApplicantRegisterController@submitRegistrationFormStep5');

	// URL Form
	Route::get('/lowongan/{url}', 'LowonganController@visitForm');

	// Register as General Member
	Route::get('/register', 'Auth\RegisterController@showRegistrationForm');
	Route::post('/register', 'Auth\RegisterController@submitRegistrationForm');
});
    
// Admin Capabilities
Route::group(['middleware' => ['admin']], function(){

	// Logout
	Route::post('/admin/logout', 'AdminLoginController@logout')->name('auth.logout');

	// Dashboard
	Route::get('/admin', function() {
		return view('layouts/admin/main');
	})->name('admin.dashboard');

	// Office
	Route::get('/admin/office', 'Update\OfficeController@index')->name('admin.office.index');
	Route::get('/admin/office/create', 'Update\OfficeController@create')->name('admin.office.create');
	Route::post('/admin/office/store', 'Update\OfficeController@store')->name('admin.office.store');
	Route::get('/admin/office/edit/{id}', 'Update\OfficeController@edit')->name('admin.office.edit');
	Route::post('/admin/office/update', 'Update\OfficeController@update')->name('admin.office.update');
	Route::post('/admin/office/delete', 'Update\OfficeController@delete')->name('admin.office.delete');

	// Position
	Route::get('/admin/position', 'Update\PositionController@index')->name('admin.position.index');
	Route::get('/admin/position/create', 'Update\PositionController@create')->name('admin.position.create');
	Route::post('/admin/position/store', 'Update\PositionController@store')->name('admin.position.store');
	Route::get('/admin/position/edit/{id}', 'Update\PositionController@edit')->name('admin.position.edit');
	Route::post('/admin/position/update', 'Update\PositionController@update')->name('admin.position.update');
	Route::post('/admin/position/delete', 'Update\PositionController@delete')->name('admin.position.delete');

	// Vacancy
	Route::get('/admin/vacancy', 'Update\VacancyController@index')->name('admin.vacancy.index');
	Route::get('/admin/vacancy/create', 'Update\VacancyController@create')->name('admin.vacancy.create');
	Route::post('/admin/vacancy/store', 'Update\VacancyController@store')->name('admin.vacancy.store');
	Route::get('/admin/vacancy/edit/{id}', 'Update\VacancyController@edit')->name('admin.vacancy.edit');
	Route::post('/admin/vacancy/update', 'Update\VacancyController@update')->name('admin.vacancy.update');
	Route::post('/admin/vacancy/delete', 'Update\VacancyController@delete')->name('admin.vacancy.delete');
	Route::get('/admin/vacancy/applicant/{id}', 'Update\VacancyController@applicant')->name('admin.vacancy.applicant');
	Route::post('/admin/vacancy/update-status', 'Update\VacancyController@updateStatus')->name('admin.vacancy.update-status');

	// STIFIn
	Route::get('/admin/stifin', 'Update\StifinController@index')->name('admin.stifin.index');
	Route::get('/admin/stifin/create', 'Update\StifinController@create')->name('admin.stifin.create');
	Route::post('/admin/stifin/store', 'Update\StifinController@store')->name('admin.stifin.store');
	Route::get('/admin/stifin/edit/{id}', 'Update\StifinController@edit')->name('admin.stifin.edit');
	Route::post('/admin/stifin/update', 'Update\StifinController@update')->name('admin.stifin.update');
	Route::post('/admin/stifin/delete', 'Update\StifinController@delete')->name('admin.stifin.delete');
	Route::get('/admin/stifin/print/{id}', 'Update\StifinController@print')->name('admin.stifin.print');

	// HRD
	Route::get('/admin/hrd', 'Update\HRDController@index')->name('admin.hrd.index');
	Route::get('/admin/hrd/create', 'Update\HRDController@create')->name('admin.hrd.create');
	Route::post('/admin/hrd/store', 'Update\HRDController@store')->name('admin.hrd.store');
	Route::get('/admin/hrd/detail/{id}', 'Update\HRDController@detail')->name('admin.hrd.detail');
	Route::get('/admin/hrd/edit/{id}', 'Update\HRDController@edit')->name('admin.hrd.edit');
	Route::post('/admin/hrd/update', 'Update\HRDController@update')->name('admin.hrd.update');
	Route::post('/admin/hrd/delete', 'Update\HRDController@delete')->name('admin.hrd.delete');

	// Employee
	Route::get('/admin/employee', 'Update\EmployeeController@index')->name('admin.employee.index');
	Route::get('/admin/employee/create', 'Update\EmployeeController@create')->name('admin.employee.create');
	Route::post('/admin/employee/store', 'Update\EmployeeController@store')->name('admin.employee.store');
	Route::get('/admin/employee/detail/{id}', 'Update\EmployeeController@detail')->name('admin.employee.detail');
	Route::get('/admin/employee/edit/{id}', 'Update\EmployeeController@edit')->name('admin.employee.edit');
	Route::post('/admin/employee/update', 'Update\EmployeeController@update')->name('admin.employee.update');
	Route::post('/admin/employee/delete', 'Update\EmployeeController@delete')->name('admin.employee.delete');
	// Route::get('/admin/employee/export', 'Update\EmployeeController@export')->name('admin.employee.export');
	// Route::post('/admin/employee/import', 'Update\EmployeeController@import')->name('admin.employee.import');

	// Applicant
	Route::get('/admin/applicant', 'Update\ApplicantController@index')->name('admin.applicant.index');
	Route::get('/admin/applicant/detail/{id}', 'Update\ApplicantController@detail')->name('admin.applicant.detail');
	// Route::get('/admin/applicant/edit/{id}', 'Update\ApplicantController@edit')->name('admin.applicant.edit');
	// Route::post('/admin/applicant/update', 'Update\ApplicantController@update')->name('admin.applicant.update');
	Route::post('/admin/applicant/delete', 'Update\ApplicantController@delete')->name('admin.applicant.delete');
	// Route::get('/admin/applicant/export', 'Update\ApplicantController@export')->name('admin.applicant.export');


	///////////////////////////////////////////

	// Route::get('/admin', 'DashboardController@index');
	// Route::get('/admin/send-email', 'ApplicantRegisterController@sendMailToHRD');
	
	// Profil
	Route::get('/admin/profil', 'HRDController@profile');
	Route::get('/admin/profil/edit', 'HRDController@editProfil');
	Route::post('/admin/profil/update', 'HRDController@updateProfil');
	Route::get('/admin/profil/edit-password', 'HRDController@editPassword');
	Route::post('/admin/profil/update-password', 'HRDController@updatePassword');

	// Update Sistem
	Route::get('/admin/update-sistem', function(){
		// View
		return view('update-sistem/index');
	});

	// // Kantor Menu
	// Route::get('/admin/kantor', 'KantorController@index');
	// Route::get('/admin/kantor/create', 'KantorController@create');
	// Route::post('/admin/kantor/store', 'KantorController@store');
	// Route::get('/admin/kantor/edit/{id}', 'KantorController@edit');
	// Route::post('/admin/kantor/update', 'KantorController@update');
	// Route::post('/admin/kantor/delete', 'KantorController@delete');

	// // Jabatan Menu
	// Route::get('/admin/posisi', 'PosisiController@index');
	// Route::get('/admin/posisi/create', 'PosisiController@create');
	// Route::post('/admin/posisi/store', 'PosisiController@store');
	// Route::get('/admin/posisi/edit/{id}', 'PosisiController@edit');
	// Route::post('/admin/posisi/update', 'PosisiController@update');
	// Route::post('/admin/posisi/delete', 'PosisiController@delete');

	// // Lowongan Menu
	// Route::get('/admin/lowongan', 'LowonganController@index');
	// Route::get('/admin/lowongan/create', 'LowonganController@create');
	// Route::post('/admin/lowongan/store', 'LowonganController@store');
	// Route::get('/admin/lowongan/pelamar/{id}', 'LowonganController@applicant');
	// Route::post('/admin/lowongan/update-status', 'LowonganController@updateStatus');
	// Route::get('/admin/lowongan/edit/{id}', 'LowonganController@edit');
	// Route::post('/admin/lowongan/update', 'LowonganController@update');
	// Route::post('/admin/lowongan/delete', 'LowonganController@delete');

	// Seleksi Menu
	Route::get('/admin/seleksi', 'SeleksiController@index');
	Route::post('/admin/seleksi/store', 'SeleksiController@store');
	Route::post('/admin/seleksi/data', 'SeleksiController@data');
	Route::post('/admin/seleksi/update', 'SeleksiController@update');
	Route::post('/admin/seleksi/convert', 'SeleksiController@convert');
	Route::post('/admin/seleksi/delete', 'SeleksiController@delete');

	// Agama Menu
	// Route::get('/admin/agama', 'AgamaController@index');
	// Route::get('/admin/agama/create', 'AgamaController@create');
	// Route::post('/admin/agama/store', 'AgamaController@store');
	// Route::get('/admin/agama/edit/{id}', 'AgamaController@edit');
	// Route::post('/admin/agama/update', 'AgamaController@update');
	// Route::post('/admin/agama/delete', 'AgamaController@delete');

	// Tes Menu
	Route::get('/admin/tes', 'TesController@index');
	Route::get('/admin/tes/create', 'TesController@create');
	Route::post('/admin/tes/store', 'TesController@store');
	Route::get('/admin/tes/edit/{id}', 'TesController@edit');
	Route::post('/admin/tes/update', 'TesController@update');
	Route::post('/admin/tes/delete', 'TesController@delete');
	Route::post('/admin/tes/generate-path', 'TesController@generatePath');
	Route::get('/admin/tes/settings/{path}', 'TesController@settings');
	Route::get('/admin/tes/settings/{path}/{paket}', 'TesController@editSettings');
	Route::post('/admin/tes/settings/{path}/{paket}/update', 'TesController@updateSettings');

	// Hasil Menu
	// Route::get('/admin/hasil', 'HasilController@index');
	Route::get('/admin/hasil/karyawan', 'HasilController@employee');
	Route::get('/admin/hasil/pelamar', 'HasilController@applicant');
	Route::get('/admin/hasil/magang', 'HasilController@internship');
	Route::get('/admin/hasil/detail/{id}', 'HasilController@detail');
	Route::post('/admin/hasil/print', 'HasilController@pdf');
	Route::post('/admin/hasil/delete', 'HasilController@delete');
	Route::get('/admin/hasil/json/karyawan', 'HasilController@json_employee');
	Route::get('/admin/hasil/json/pelamar', 'HasilController@json_applicant');
	Route::get('/admin/hasil/json/magang', 'HasilController@json_internship');

	// STIFIn Menu
	// Route::get('/admin/stifin', 'StifinController@index');
	// Route::get('/admin/stifin/create', 'StifinController@create');
	// Route::post('/admin/stifin/store', 'StifinController@store');
	// Route::get('/admin/stifin/edit/{id}', 'StifinController@edit');
	// Route::post('/admin/stifin/update', 'StifinController@update');
	// Route::post('/admin/stifin/delete', 'StifinController@delete');
	// Route::get('/admin/stifin/print/{id}', 'StifinController@print');

	// Admin Menu
	// Route::get('/admin/list', 'UserController@admin');
	// Route::get('/admin/create', 'UserController@createAdmin');
	// Route::post('/admin/store', 'UserController@storeAdmin');
	// Route::get('/admin/edit/{id}', 'UserController@editAdmin');
	// Route::post('/admin/update', 'UserController@updateAdmin');
	// Route::post('/admin/delete', 'UserController@delete');

	// HRD Menu
	// Route::get('/admin/hrd', 'HRDController@index');
	// Route::get('/admin/hrd/create', 'HRDController@create');
	// Route::post('/admin/hrd/store', 'HRDController@store');
	// Route::get('/admin/hrd/edit/{id}', 'HRDController@edit');
	// Route::post('/admin/hrd/update', 'HRDController@update');
	// Route::post('/admin/hrd/delete', 'HRDController@delete');

	// Karyawan Menu
	// Route::get('/admin/karyawan', 'KaryawanController@index');
	// Route::get('/admin/karyawan/create', 'KaryawanController@create');
	// Route::post('/admin/karyawan/store', 'KaryawanController@store');
	// Route::get('/admin/karyawan/detail/{id}', 'KaryawanController@detail');
	// Route::get('/admin/karyawan/edit/{id}', 'KaryawanController@edit');
	// Route::post('/admin/karyawan/update', 'KaryawanController@update');
	// Route::post('/admin/karyawan/delete', 'KaryawanController@delete');
	// Route::get('/admin/karyawan/export', 'KaryawanController@export');
	// Route::post('/admin/karyawan/import', 'KaryawanController@import');
	// Route::get('/admin/karyawan/json', 'KaryawanController@json');

	// Pelamar Menu
	// Route::get('/admin/pelamar', 'PelamarController@index');
	// Route::get('/admin/pelamar/detail/{id}', 'PelamarController@detail');
	// Route::get('/admin/pelamar/edit/{id}', 'PelamarController@edit');
	// Route::post('/admin/pelamar/update', 'PelamarController@update');
	// Route::post('/admin/pelamar/delete', 'PelamarController@delete');
	// Route::get('/admin/pelamar/export', 'PelamarController@export');
	// Route::get('/admin/pelamar/json', 'PelamarController@json');

	// // General Member Menu
	// Route::get('/admin/umum', 'UserController@general');
	// Route::post('/admin/umum/delete', 'UserController@delete');

	/*
	// Role Menu
	Route::get('/admin/role', 'RoleController@index');
	Route::get('/admin/role/create', 'RoleController@create');
	Route::post('/admin/role/store', 'RoleController@store');
	Route::get('/admin/role/edit/{id}', 'RoleController@edit');
	Route::post('/admin/role/update', 'RoleController@update');

	// Tipe Tes Menu
	Route::get('/admin/tes/tipe/{id}', 'PaketSoalController@index');
	Route::get('/admin/tes/tipe/{id}/paket/create', 'PaketSoalController@create');
	Route::post('/admin/tes/tipe/{id}/paket/store', 'PaketSoalController@store');
	Route::get('/admin/tes/tipe/{id}/paket/edit/{id_paket}', 'PaketSoalController@edit');
	Route::post('/admin/tes/tipe/{id}/paket/update', 'PaketSoalController@update');
	Route::post('/admin/tes/tipe/{id}/paket/update-status', 'PaketSoalController@updateStatus');
	Route::post('/admin/tes/tipe/{id}/paket/delete', 'PaketSoalController@delete');

	// Tutorial Tes Menu
	Route::get('/admin/tes/tipe/{id}/paket/tutorial/{id_paket}', 'TutorialController@index');
	Route::post('/admin/tes/tipe/{id}/paket/tutorial/save', 'TutorialController@save');
	Route::post('/admin/tes/tipe/{id}/paket/tutorial/delete', 'TutorialController@delete');

	// Keterangan Tes Menu
	Route::get('/admin/tes/tipe/{id}/paket/keterangan/{id_paket}', 'KeteranganController@index');
	Route::post('/admin/tes/tipe/{id}/paket/keterangan/save', 'KeteranganController@save');
	Route::post('/admin/tes/tipe/{id}/paket/keterangan/delete', 'KeteranganController@delete');

	// Soal Tes Menu
	Route::get('/admin/tes/tipe/{id}/paket/soal/{id_paket}', 'SoalController@index');
	Route::get('/admin/tes/tipe/{id}/soal/create/{id_paket}', 'SoalController@create');
	Route::post('/admin/tes/tipe/{id}/soal/store', 'SoalController@store');
	Route::get('/admin/tes/tipe/{id}/soal/edit/{id_soal}', 'SoalController@edit');
	Route::post('/admin/tes/tipe/{id}/soal/update', 'SoalController@update');
	Route::post('/admin/tes/tipe/{id}/soal/delete', 'SoalController@delete');
	Route::get('/admin/tes/tipe/{id}/soal/export/{id_paket}', 'SoalController@exportExcel');
	Route::get('/admin/tes/tipe/{id}/soal/import/{id_paket}', 'SoalController@importForm');
	Route::post('/admin/tes/tipe/{id}/soal/import/post', 'SoalController@importExcel');
	*/
});