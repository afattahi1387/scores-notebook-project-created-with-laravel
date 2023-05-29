<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\LessonRoom;
use Illuminate\Http\Request;
use App\Http\Requests\AddAndEditNameRequest;

class AdminsDashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function admins_dashboard() {
        $lesson_rooms = LessonRoom::all();
        $lessons = Lesson::all();
        if(isset($_GET['edit-lesson-room']) && !empty($_GET['edit-lesson-room'])) {
            $lesson_room_name = LessonRoom::find($_GET['edit-lesson-room'])['name'];
        } else {
            $lesson_room_name = '';
        }
        return view('admins_dashboard.dashboard', ['lesson_rooms' => $lesson_rooms, 'lessons' => $lessons, 'lesson_room_name' => $lesson_room_name]);
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
}
