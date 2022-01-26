<?php

use Ajifatur\Helpers\RouteExt;

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
Route::group(['middleware' => ['guest']], function() {

	// Home
	Route::get('/', function () {
	   return redirect()->route('auth.login');
	})->name('home');

	// Login
	Route::get('/login', 'Auth\LoginController@show')->name('auth.login');
	Route::post('/login', 'Auth\LoginController@authenticate')->name('auth.post-login');

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
	// Route::get('/register', 'Auth\RegisterController@showRegistrationForm');
	// Route::post('/register', 'Auth\RegisterController@submitRegistrationForm');
});
    
// Admin Capabilities
Route::group(['middleware' => ['admin']], function() {

	// Logout
	Route::post('/admin/logout', 'Auth\LoginController@logout')->name('admin.logout');

	// Dashboard
	Route::get('/admin', function() {
		return view('admin/dashboard/index');
	})->name('admin.dashboard');

	// Profile
	Route::get('/admin/profile', 'Update\ProfileController@detail')->name('admin.profile');
	Route::get('/admin/profile/edit', 'Update\ProfileController@edit')->name('admin.profile.edit');
	Route::post('/admin/profile/update', 'Update\ProfileController@update')->name('admin.profile.update');
	Route::get('/admin/profile/edit-password', 'Update\ProfileController@editPassword')->name('admin.profile.edit-password');
	Route::post('/admin/profile/update-password', 'Update\ProfileController@updatePassword')->name('admin.profile.update-password');

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

	// Selection
	Route::get('/admin/selection', 'Update\SelectionController@index')->name('admin.selection.index');
	Route::post('/admin/selection/store', 'Update\SelectionController@store')->name('admin.selection.store');
	Route::post('/admin/selection/update', 'Update\SelectionController@update')->name('admin.selection.update');
	Route::post('/admin/selection/convert', 'Update\SelectionController@convert')->name('admin.selection.convert');
	Route::post('/admin/selection/delete', 'Update\SelectionController@delete')->name('admin.selection.delete');

	// Test
	Route::get('/admin/test', 'Update\TestController@index')->name('admin.test.index');
	Route::get('/admin/test/create', 'Update\TestController@create')->name('admin.test.create');
	Route::post('/admin/test/store', 'Update\TestController@store')->name('admin.test.store');
	Route::get('/admin/test/edit/{id}', 'Update\TestController@edit')->name('admin.test.edit');
	Route::post('/admin/test/update', 'Update\TestController@update')->name('admin.test.update');
	Route::post('/admin/test/delete', 'Update\TestController@delete')->name('admin.test.delete');
	// Route::post('/admin/test/generate-path', 'TesController@generatePath');
	// Route::get('/admin/test/settings/{path}', 'TesController@settings');
	// Route::get('/admin/test/settings/{path}/{paket}', 'TesController@editSettings');
	// Route::post('/admin/test/settings/{path}/{paket}/update', 'TesController@updateSettings');

	// Position Test
	Route::get('/admin/position-test', 'Update\PositionTestController@index')->name('admin.position-test.index');
	Route::post('/admin/position-test/change', 'Update\PositionTestController@change')->name('admin.position-test.change');

	// STIFIn
	Route::get('/admin/stifin', 'Update\StifinController@index')->name('admin.stifin.index');
	Route::get('/admin/stifin/create', 'Update\StifinController@create')->name('admin.stifin.create');
	Route::post('/admin/stifin/store', 'Update\StifinController@store')->name('admin.stifin.store');
	Route::get('/admin/stifin/edit/{id}', 'Update\StifinController@edit')->name('admin.stifin.edit');
	Route::post('/admin/stifin/update', 'Update\StifinController@update')->name('admin.stifin.update');
	Route::post('/admin/stifin/delete', 'Update\StifinController@delete')->name('admin.stifin.delete');
	Route::get('/admin/stifin/print/{id}', 'Update\StifinController@print')->name('admin.stifin.print');

	// Result
	Route::get('/admin/result', 'Update\ResultController@index')->name('admin.result.index');
	Route::get('/admin/result/detail/{id}', 'Update\ResultController@detail')->name('admin.result.detail');
	Route::post('/admin/result/delete', 'Update\ResultController@delete')->name('admin.result.delete');
	Route::post('/admin/result/print', 'Update\ResultController@print')->name('admin.result.print');

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
	Route::get('/admin/employee/export', 'Update\EmployeeController@export')->name('admin.employee.export');
	// Route::post('/admin/employee/import', 'Update\EmployeeController@import')->name('admin.employee.import');

	// Applicant
	Route::get('/admin/applicant', 'Update\ApplicantController@index')->name('admin.applicant.index');
	Route::get('/admin/applicant/detail/{id}', 'Update\ApplicantController@detail')->name('admin.applicant.detail');
	Route::get('/admin/applicant/edit/{id}', 'Update\ApplicantController@edit')->name('admin.applicant.edit');
	Route::post('/admin/applicant/update', 'Update\ApplicantController@update')->name('admin.applicant.update');
	Route::post('/admin/applicant/delete', 'Update\ApplicantController@delete')->name('admin.applicant.delete');
	Route::get('/admin/applicant/export', 'Update\ApplicantController@export')->name('admin.applicant.export');
});

RouteExt::user();
RouteExt::menu();