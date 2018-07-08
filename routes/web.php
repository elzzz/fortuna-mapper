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
Route::get('/', 'PagesController@index');

Route::resource('/mapper', 'MapsController');

Auth::routes();

Route::get('/dashboard', 'DashboardController@index');

// Facebook Socialite
Route::get('login/facebook', 'Auth\LoginController@redirectToFBProvider');
Route::get('login/facebook/callback', 'Auth\LoginController@handleFBProviderCallback');

// VK Socialite
Route::get('login/vk', 'Auth\LoginController@redirectToVKProvider');
Route::get('login/vk/callback', 'Auth\LoginController@handleVKProviderCallback');
