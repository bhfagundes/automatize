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

Route::get('/', 'TicketsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');


Route::get('/tkts/{id}','TicketsController@index2');

Route::resource('tickets', 'TicketsController');

Route::get('/massiveDelete','TicketsController@massiveDelete');