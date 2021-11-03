<?php

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

Auth::routes();

Route::get('/home','HomeController@index')->name('home');
// Route::get('disclaimer','HomeController@disclaimer');

// Route::get('insertApikey/','QueryController@insertApiKey');
Route::post('saveApiKey/','QueryController@saveApiKey');
Route::post('/fromstart','QueryController@fromstart');
Route::post('/fromstop','QueryController@fromstop');
Route::post('/setapikey','QueryController@setapikey');
Route::get('/getapikeyajax','QueryController@getapikeyajax');
Route::get('/typeexecute/{id}','QueryController@typeexecute');


Route::get('/preview/{string}','QueryController@preview');
Route::get('/showconfusionmatrix/{id}','QueryController@showconfusionmatrix');
Route::post('/fetchpercent','QueryController@fetchpercent');
// Route::post('/fetchPreview','QueryController@fetchPreview');
// Route::get('loading','QueryController@loading');
// Route::get('/getProgress','QueryController@getProgress');

// Route::get('progress-bar', 'QueryController@progress-bar');

Route::post('/executefstart','QueryController@executefstart');
Route::post('/checkretstart','QueryController@checkretstart');
Route::get('/query', 'QueryController@index');
Route::resource('query','QueryController')->except(['destroy']);//crea tutte le rotte per il crud completo
Route::get('/query/{query}/delete', 'QueryController@destroy');
// Route::get('/query/{id}/train','QueryController@train');

Route::get('/query/train/{id}','QueryController@train');
Route::post('/fetchtrain','QueryController@ptrain');
// Route::post('/savelabel','QueryController@savelabel');
Route::post('/forgetlabel','QueryController@forgetlabel');
Route::post('/undolabel','QueryController@undolabel');
Route::post('/apply','QueryController@apply');
Route::post('/change','QueryController@change');
Route::post('/count','QueryController@countlabel');

Route::get('/query/analysis/{id}','QueryController@build');
Route::post('/query/result','QueryController@result');

Route::get('auth', 'AuthorController@index');
