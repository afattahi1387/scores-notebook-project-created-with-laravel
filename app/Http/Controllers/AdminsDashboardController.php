<?php

namespace App\Http\Controllers;

use App\User;
use App\Lesson;
use App\LessonRoom;
use Illuminate\Http\Request;
use App\Http\Requests\AddTeacherRequest;
use App\Http\Requests\EditTeacherRequest;
use App\Http\Requests\AddAndEditNameRequest;

class AdminsDashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function admins_dashboard() {
        $lesson_rooms = LessonRoom::all();
        $lessons = Lesson::all();
        $teachers = User::where('type', 'teacher')->orderBy('id', 'DESC')->get();

        if(isset($_GET['edit-lesson-room']) && !empty($_GET['edit-lesson-room'])) {
            $lesson_room_name = LessonRoom::find($_GET['edit-lesson-room'])['name'];
        } else {
            $lesson_room_name = '';
        }

        if(isset($_GET['edit-lesson']) && !empty($_GET['edit-lesson'])) {
            $lesson_name = Lesson::find($_GET['edit-lesson'])['name'];
        } else {
            $lesson_name = '';
        }

        if(isset($_GET['edit-teacher']) && !empty($_GET['edit-teacher'])) {
            $teacher_row = User::find($_GET['edit-teacher']);
        } else {
            $teacher_row = '';
        }

        return view('admins_dashboard.dashboard', ['lesson_rooms' => $lesson_rooms, 'lessons' => $lessons, 'lesson_room_name' => $lesson_room_name, 'lesson_name' => $lesson_name, 'teachers' => $teachers, 'teacher_row' => $teacher_row]);
    }

    public function insert_lesson_room(AddAndEditNameRequest $request) {
        $name = $request->name;
        LessonRoom::create([
            'name' => $name
        ]);

        return redirect()->route('admins.dashboard');
    }

    public function update_lesson_room(AddAndEditNameRequest $request, LessonRoom $lesson_room) {
        $name = $request->name;
        $lesson_room->update([
            'name' => $name
        ]);

        return redirect()->route('admins.dashboard');
    }

    public function insert_lesson(AddAndEditNameRequest $request) {
        $name = $request->name;
        Lesson::create([
            'name' => $name
        ]);

        return redirect()->route('admins.dashboard');
    }

    public function update_lesson(AddAndEditNameRequest $request, Lesson $lesson) {
        $name = $request->name;
        $lesson->update([
            'name' => $name
        ]);

        return redirect()->route('admins.dashboard');
    }

    public function insert_teacher(AddTeacherRequest $request) {
        // $user = User::where('password', bcrypt($request->password))->get();

        $name = $request->name;
        $username = $request->username;
        $password = bcrypt($request->password);

        User::create([
            'name' => $name,
            'type' => 'teacher',
            'username' => $username,
            'password' => $password
        ]);

        // TODO: added flash message
        return redirect()->route('admins.dashboard');
    }

    public function update_teacher(EditTeacherRequest $request, User $teacher) {
        // $user = User::where('password', bcrypt($request->password))->get();
        $name = $request->name;
        $username = $request->username;

        if($username != $teacher->username) {
            // TODO
        }
        
        if(!empty($request->new_password)) {
            $new_password = bcrypt($request->new_password);
        } else {
            $new_password = $teacher->password;
        }

        $teacher->update([
            'name' => $name,
            'username' => $username,
            'password' => $new_password
        ]);

        // TODO: added flash message
        return redirect()->route('admins.dashboard');
    }

    public function show_students_list(LessonRoom $lesson_room) {
        return view('dashboard.show_students_list', ['lesson_room' => $lesson_room, 'learners' => $lesson_room->learners]);
    }
}
