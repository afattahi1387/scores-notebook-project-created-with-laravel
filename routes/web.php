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

Route::get('/', 'DashboardController@dashboard')->name('dashboard');

Route::get('/login', 'MainController@login')->name('login');

Route::get('/redirect-to-dashboard', 'DashboardController@redirect_to_dashboard')->name('redirect.to.dashboard');

Auth::routes();
