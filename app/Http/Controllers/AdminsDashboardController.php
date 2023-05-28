<?php

namespace App\Http\Controllers;

use App\Lesson;
use App\LessonRoom;
use Illuminate\Http\Request;

class AdminsDashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function admins_dashboard() {
        $lesson_rooms = LessonRoom::all();
        $lessons = Lesson::all();
        return view('admins_dashboard.dashboard', ['lesson_rooms' => $lesson_rooms, 'lessons' => $lessons]);
    }
}
