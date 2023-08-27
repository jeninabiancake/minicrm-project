<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', function () {
        return view('pages.home.index');
    });
    Route::resource('/users', 'UsersController');
    Route::get('/my-profile', 'UsersController@getProfile');
    Route::get('/my-profile/edit', 'UsersController@getEditProfile');
    Route::patch('/my-profile/edit', 'UsersController@postEditProfile');
    Route::resource('/permissions', 'PermissionsController');
    Route::resource('/roles', 'RolesController');
    Route::get('/users/role/{id}', 'UsersController@getRole');
    Route::put('/users/role/{id}', 'UsersController@updateRole');
    Route::resource('/documents', 'DocumentsController');
    Route::get('/documents/{id}/assign', 'DocumentsController@getAssignDocument');
    Route::put('/documents/{id}/assign', 'DocumentsController@postAssignDocument');
    Route::get('/forbidden', function () {
        return view('pages.forbidden.forbidden_area');
    });
});
Route::get('/', function () {
   return redirect()->to('/admin');
});
Auth::routes();