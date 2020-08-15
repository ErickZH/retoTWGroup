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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('publications', 'PublicationController@index')->name('publications.index')->middleware('auth');
Route::get('publications/create', 'PublicationController@create')->name('publications.create')->middleware('auth');
Route::post('publications', 'PublicationController@store')->name('publications.store')->middleware('auth');
Route::get('publications/{publication}', 'PublicationController@show')->name('publications.show')->middleware('auth');
Route::post('publications/{publication}/comment', 'PublicationController@comment')->name('publications.comment')->middleware('auth');
