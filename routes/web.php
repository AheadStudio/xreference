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

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/home', function () {
    return redirect()->route('home');
});
Route::post('/feedback', 'HomeController@feedback');
Route::any('/result', 'HomeController@result');

Route::get('/account/upload', 'UsersController@upload');
Route::post('/account/download', 'ReferencesController@downloadExcel');
Route::resource('/account', 'UsersController');

Route::post('/reference/import', 'ReferencesController@import');
Route::post('/reference/rate', 'ReferencesController@rate');
Route::resource('/reference', 'ReferencesController');


Route::resource('/component', 'ComponentsController');