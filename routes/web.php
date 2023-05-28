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

Route::prefix('panel')->group(function() {
    Route::get('/add-date/{lesson_room}/{lesson}', 'DashboardController@add_date')->name('add.date');

    Route::post('/insert-date/{lesson_room}/{lesson}', 'DashboardController@insert_date')->name('insert.date');

    Route::get('/show-students-list/{lesson_room}/{lesson}', 'DashboardController@show_students_list')->name('show.students.list');
});
