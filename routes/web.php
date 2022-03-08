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

// \Ajifatur\Helpers\RouteExt::auth();
\Ajifatur\Helpers\RouteExt::admin();

// Guest Capabilities
Route::group(['middleware' => ['guest']], function() {

	// Home
	Route::get('/', function () {
	   return redirect()->route('auth.login');
	})->name('home');

	// Login
	Route::get('/login', 'Auth\LoginController@show')->name('auth.login');
	Route::post('/login', '\Ajifatur\FaturHelper\Http\Controllers\Auth\LoginController@authenticate');

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
	Route::get('/lowongan/{url}', 'VacancyController@visit');

	// Register as Applicant
	// Route::post('/register', 'RegisterController@store')->name('auth.register');

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
	Route::get('/admin/profile', 'ProfileController@detail')->name('admin.profile');
	Route::get('/admin/profile/edit', 'ProfileController@edit')->name('admin.profile.edit');
	Route::post('/admin/profile/update', 'ProfileController@update')->name('admin.profile.update');
	Route::get('/admin/profile/edit-password', 'ProfileController@editPassword')->name('admin.profile.edit-password');
	Route::post('/admin/profile/update-password', 'ProfileController@updatePassword')->name('admin.profile.update-password');

	// Office
	Route::get('/admin/office', 'OfficeController@index')->name('admin.office.index');
	Route::get('/admin/office/create', 'OfficeController@create')->name('admin.office.create');
	Route::post('/admin/office/store', 'OfficeController@store')->name('admin.office.store');
	Route::get('/admin/office/edit/{id}', 'OfficeController@edit')->name('admin.office.edit');
	Route::post('/admin/office/update', 'OfficeController@update')->name('admin.office.update');
	Route::post('/admin/office/delete', 'OfficeController@delete')->name('admin.office.delete');

	// Position
	Route::get('/admin/position', 'PositionController@index')->name('admin.position.index');
	Route::get('/admin/position/create', 'PositionController@create')->name('admin.position.create');
	Route::post('/admin/position/store', 'PositionController@store')->name('admin.position.store');
	Route::get('/admin/position/edit/{id}', 'PositionController@edit')->name('admin.position.edit');
	Route::post('/admin/position/update', 'PositionController@update')->name('admin.position.update');
	Route::post('/admin/position/delete', 'PositionController@delete')->name('admin.position.delete');

	// Vacancy
	Route::get('/admin/vacancy', 'VacancyController@index')->name('admin.vacancy.index');
	Route::get('/admin/vacancy/create', 'VacancyController@create')->name('admin.vacancy.create');
	Route::post('/admin/vacancy/store', 'VacancyController@store')->name('admin.vacancy.store');
	Route::get('/admin/vacancy/edit/{id}', 'VacancyController@edit')->name('admin.vacancy.edit');
	Route::post('/admin/vacancy/update', 'VacancyController@update')->name('admin.vacancy.update');
	Route::post('/admin/vacancy/delete', 'VacancyController@delete')->name('admin.vacancy.delete');
	Route::get('/admin/vacancy/applicant/{id}', 'VacancyController@applicant')->name('admin.vacancy.applicant');
	Route::post('/admin/vacancy/update-status', 'VacancyController@updateStatus')->name('admin.vacancy.update-status');

	// Selection
	Route::get('/admin/selection', 'SelectionController@index')->name('admin.selection.index');
	Route::post('/admin/selection/store', 'SelectionController@store')->name('admin.selection.store'); // TBC
	Route::post('/admin/selection/update', 'SelectionController@update')->name('admin.selection.update');
	Route::post('/admin/selection/convert', 'SelectionController@convert')->name('admin.selection.convert'); // TBC
	Route::post('/admin/selection/delete', 'SelectionController@delete')->name('admin.selection.delete');

	// Test // TBC
	Route::get('/admin/test', 'TestController@index')->name('admin.test.index');
	Route::get('/admin/test/create', 'TestController@create')->name('admin.test.create');
	Route::post('/admin/test/store', 'TestController@store')->name('admin.test.store');
	Route::get('/admin/test/edit/{id}', 'TestController@edit')->name('admin.test.edit');
	Route::post('/admin/test/update', 'TestController@update')->name('admin.test.update');
	Route::post('/admin/test/delete', 'TestController@delete')->name('admin.test.delete');
	// Route::post('/admin/test/generate-path', 'TesController@generatePath');
	// Route::get('/admin/test/settings/{path}', 'TesController@settings');
	// Route::get('/admin/test/settings/{path}/{paket}', 'TesController@editSettings');
	// Route::post('/admin/test/settings/{path}/{paket}/update', 'TesController@updateSettings');

	// Position Test
	Route::get('/admin/position-test', 'PositionTestController@index')->name('admin.position-test.index');
	Route::post('/admin/position-test/change', 'PositionTestController@change')->name('admin.position-test.change');

	// STIFIn
	Route::get('/admin/stifin', 'StifinController@index')->name('admin.stifin.index');
	Route::get('/admin/stifin/create', 'StifinController@create')->name('admin.stifin.create');
	Route::post('/admin/stifin/store', 'StifinController@store')->name('admin.stifin.store');
	Route::get('/admin/stifin/edit/{id}', 'StifinController@edit')->name('admin.stifin.edit');
	Route::post('/admin/stifin/update', 'StifinController@update')->name('admin.stifin.update');
	Route::post('/admin/stifin/delete', 'StifinController@delete')->name('admin.stifin.delete');
	Route::get('/admin/stifin/print/{id}', 'StifinController@print')->name('admin.stifin.print');

	// Result
	Route::get('/admin/result', 'ResultController@index')->name('admin.result.index');
	Route::get('/admin/result/detail/{id}', 'ResultController@detail')->name('admin.result.detail');
	Route::post('/admin/result/delete', 'ResultController@delete')->name('admin.result.delete');
	Route::post('/admin/result/print', 'ResultController@print')->name('admin.result.print');

	// HRD
	Route::get('/admin/hrd', 'HRDController@index')->name('admin.hrd.index');
	Route::get('/admin/hrd/create', 'HRDController@create')->name('admin.hrd.create');
	Route::post('/admin/hrd/store', 'HRDController@store')->name('admin.hrd.store');
	Route::get('/admin/hrd/detail/{id}', 'HRDController@detail')->name('admin.hrd.detail');
	Route::get('/admin/hrd/edit/{id}', 'HRDController@edit')->name('admin.hrd.edit');
	Route::post('/admin/hrd/update', 'HRDController@update')->name('admin.hrd.update');
	Route::post('/admin/hrd/delete', 'HRDController@delete')->name('admin.hrd.delete');

	// Employee
	Route::get('/admin/employee', 'EmployeeController@index')->name('admin.employee.index');
	Route::get('/admin/employee/create', 'EmployeeController@create')->name('admin.employee.create');
	Route::post('/admin/employee/store', 'EmployeeController@store')->name('admin.employee.store');
	Route::get('/admin/employee/detail/{id}', 'EmployeeController@detail')->name('admin.employee.detail');
	Route::get('/admin/employee/edit/{id}', 'EmployeeController@edit')->name('admin.employee.edit');
	Route::post('/admin/employee/update', 'EmployeeController@update')->name('admin.employee.update');
	Route::post('/admin/employee/delete', 'EmployeeController@delete')->name('admin.employee.delete');
	Route::get('/admin/employee/export', 'EmployeeController@export')->name('admin.employee.export');
	// Route::post('/admin/employee/import', 'EmployeeController@import')->name('admin.employee.import');

	// Applicant
	Route::get('/admin/applicant', 'ApplicantController@index')->name('admin.applicant.index');
	Route::get('/admin/applicant/create', 'ApplicantController@create')->name('admin.applicant.create');
	Route::post('/admin/applicant/store', 'ApplicantController@store')->name('admin.applicant.store');
	Route::get('/admin/applicant/detail/{id}', 'ApplicantController@detail')->name('admin.applicant.detail');
	Route::get('/admin/applicant/edit/{id}', 'ApplicantController@edit')->name('admin.applicant.edit');
	Route::post('/admin/applicant/update', 'ApplicantController@update')->name('admin.applicant.update');
	Route::post('/admin/applicant/delete', 'ApplicantController@delete')->name('admin.applicant.delete');
	Route::get('/admin/applicant/export', 'ApplicantController@export')->name('admin.applicant.export');
	Route::post('/admin/applicant/convert', 'ApplicantController@convert')->name('admin.applicant.convert');

	/******************************** */

	// Sync
	Route::get('/admin/sync/user', 'SyncController@user');
	Route::get('/admin/sync/applicant', 'SyncController@applicant');
	Route::get('/admin/sync/applicant/attachment', 'SyncController@applicantAttachment');
	Route::get('/admin/sync/applicant/socmed', 'SyncController@applicantSocmed');
	Route::get('/admin/sync/applicant/guardian', 'SyncController@applicantGuardian');
	Route::get('/admin/sync/applicant/skill', 'SyncController@applicantSkill');
	Route::get('/admin/sync/employee', 'SyncController@employee');
	Route::get('/admin/sync/internship', 'SyncController@internship');
	Route::get('/admin/sync/hrd', 'SyncController@hrd');

	Route::get('/admin/sync/company-test', 'SyncController@companyTest');
	Route::get('/admin/sync/position-test', 'SyncController@positionTest');
	Route::get('/admin/sync/position-skill', 'SyncController@positionSkill');
	Route::get('/admin/sync/selection', 'SyncController@selection');
});