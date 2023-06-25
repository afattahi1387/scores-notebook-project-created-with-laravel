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

Route::get('/home', function() {
    return redirect('/');
});

Route::get('/flashed', 'ShowFlashMessageController@get_flashed_messages');

Route::get('/add-flash/{message_type}/{message_text}', 'ShowFlashMessageController@add_flash_message');

Route::get('/redirect-to-dashboard', function() {
    if(auth()->user()->type == 'teacher') {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('admins.dashboard');
    }
})->middleware('auth')->name('redirect.to.dashboard');

Auth::routes();

Route::prefix('panel')->group(function() {
    Route::get('/add-date/{lesson_room}/{lesson}', 'DashboardController@add_date')->name('add.date');

    Route::post('/insert-date/{lesson_room}/{lesson}', 'DashboardController@insert_date')->name('insert.date');

    Route::get('/show-students-list/{lesson_room}/{lesson}', 'DashboardController@show_students_list')->name('show.students.list');

    Route::get('/settings', 'DashboardController@teachers_settings')->name('teachers.settings');

    Route::post('/set-settings', 'DashboardController@set_settings')->name('set.settings');
});

Route::get('/show-learner-information/{learner}/{relation_ship}', 'DashboardController@show_learner_information')->name('show.learner.information');

Route::put('/update-term-development-score/{learner_id}/{relation_ship_id}/{term}', 'DashboardController@update_term_development_score')->name('update.term.development.score');

Route::put('/update-term-final-score/{learner}/{relation_ship}/{term}', 'DashboardController@update_term_final_score')->name('update.term.final.score');

Route::put('/update-score/{score}', 'DashboardController@update_score')->name('update.score');

Route::post('/change-pn-number/{learner}/{relation_ship_id}', 'DashboardController@change_pn_number')->name('change.pn.number');

Route::prefix('admins')->group(function() {
    Route::get('/dashboard', 'AdminsDashboardController@admins_dashboard')->name('admins.dashboard');

    Route::get('/restore-project-page', 'AdminsDashboardController@restore_project_page')->name('restore.project.page');

    Route::get('/restore-project-function', 'AdminsDashboardController@restore_project_function')->name('restore.project.function');

    Route::post('/insert-lesson-room', 'AdminsDashboardController@insert_lesson_room')->name('insert.lesson.room');

    Route::put('/update-lesson-room/{lesson_room}', 'AdminsDashboardController@update_lesson_room')->name('update.lesson.room');

    Route::delete('/delete-lesson-room/{lesson_room}', 'AdminsDashboardController@delete_lesson_room')->name('delete.lesson.room');

    Route::post('/insert-lesson', 'AdminsDashboardController@insert_lesson')->name('insert.lesson');

    Route::put('/update-lesson/{lesson}', 'AdminsDashboardController@update_lesson')->name('update.lesson');

    Route::delete('/delete-lesson/{lesson}', 'AdminsDashboardController@delete_lesson')->name('delete.lesson');

    Route::post('/insert-teacher', 'AdminsDashboardController@insert_teacher')->name('insert.teacher');

    Route::put('/update-teacher/{teacher}', 'AdminsDashboardController@update_teacher')->name('update.teacher');

    Route::post('/insert-relation-ship/{teacher}', 'AdminsDashboardController@insert_relation_ship')->name('insert.relation.ship');

    Route::put('/update-relation-ship/{relation_ship}', 'AdminsDashboardController@update_relation_ship')->name('update.relation.ship');

    Route::delete('/delete-relation-ship/{relation_ship}', 'AdminsDashboardController@delete_relation_ship')->name('delete.relation.ship');

    Route::post('/insert-learners-for-lesson-room/{lesson_room}', 'AdminsDashboardController@insert_learners_for_lesson_room')->name('insert.learners.for.lesson.room');

    Route::put('/update-learner/{learner}', 'AdminsDashboardController@update_learner')->name('update.learner');

    Route::delete('/delete-learner/{learner}', 'AdminsDashboardController@delete_learner')->name('delete.learner');

    Route::get('/show-students-list/{lesson_room}', 'AdminsDashboardController@show_students_list')->name('show.students.list.for.admins');

    Route::get('/show-teacher-classes/{teacher}', 'AdminsDashboardController@show_teacher_classes')->name('show.teacher.classes');
});
