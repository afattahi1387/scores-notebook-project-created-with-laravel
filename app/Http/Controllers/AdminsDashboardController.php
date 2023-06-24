<?php

namespace App\Http\Controllers;

use App\User;
use App\Lesson;
use App\Learner;
use App\LessonRoom;
use App\RelationShip;
use App\TeacherSetting;
use App\PNAndFinalScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AddTeacherRequest;
use App\Http\Requests\EditLearnerRequest;
use App\Http\Requests\EditTeacherRequest;
use App\Http\Requests\AddAndEditNameRequest;
use App\Http\Requests\AddRelationShipRequest;
use App\Http\Controllers\ShowFlashMessageController;

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

        $new_user = User::create([
            'name' => $name,
            'type' => 'teacher',
            'username' => $username,
            'password' => $password
        ]);

        TeacherSetting::create([
            'user_id' => $new_user->id
        ]);

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'دبیر جدید با موفقیت اضافه شد.');
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

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'دبیر مورد نظر شما با موفقیت ویرایش شد.');
        return redirect()->route('admins.dashboard');
    }

    public function insert_relation_ship(AddRelationShipRequest $request, User $teacher) {
        DB::insert('INSERT INTO relation_ships VALUES (NULL, ?, ?, ?, ?)', [$teacher->id, $request->lesson_room, 'App\LessonRoom', $request->lesson]);

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'کلاس مورد نظر شما با موفقیت اضافه شد.');
        return redirect()->route('show.teacher.classes', ['teacher' => $teacher->id]);
    }

    public function update_relation_ship(AddRelationShipRequest $request, RelationShip $relation_ship) {  // TODO: change request
        $lesson_room = $request->lesson_room;
        $lesson = $request->lesson;
        $teacher_id = $relation_ship->user_id;

        DB::update('UPDATE relation_ships SET userable_id = ?, lesson_id = ? WHERE id = ?', [$lesson_room, $lesson, $relation_ship->id]);

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'کلاس مورد نظر شما با موفقیت ویرایش شد.');
        return redirect()->route('show.teacher.classes', ['teacher' => $teacher_id]);
    }

    public function insert_learners_for_lesson_room(Request $requests, $lesson_room) { // TODO: add request
        $keys = [];
        foreach($requests->all() as $key => $request) {
            if(substr($key, 0, 12) == 'learner_row_') {
                $keys[substr($key, 12)] = $requests['learner_row_' . substr($key, 12)];
            }
        }

        foreach($keys as $k => $value) {
            Learner::create([
                'row' => $keys[$value],
                'name' => $requests->all()['learner_name_' . $k],
                'lesson_room_id' => $lesson_room
            ]);

            // PNAndFinalScore::create([]); TODO: create records in p_n_and_final_scores table for new learner

        }
        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'دانش آموز شما برای این کلاس با موفقیت اضافه شد.');
        return redirect()->route('show.students.list.for.admins', ['lesson_room' => $lesson_room]);
    }

    public function update_learner(EditLearnerRequest $request, Learner $learner) {
        $learner->update([
            'row' => $request->learner_row,
            'name' => $request->learner_name
        ]);

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'دانش آموز مورد نظر شما با موفقیت ویرایش شد.');
        return redirect()->route('show.students.list.for.admins', ['lesson_room' => $learner->lesson_room_id]);
    }

    public function show_students_list(LessonRoom $lesson_room) {
        if(isset($_GET['edit-learner']) && !empty($_GET['edit-learner'])) {
            $learner_for_edit = Learner::find($_GET['edit-learner']);
        } else {
            $learner_for_edit = '';
        }
        return view('admins_dashboard.show_students_list_for_admin', ['lesson_room' => $lesson_room, 'learners' => $lesson_room->learners, 'learner_for_edit' => $learner_for_edit]);
    }

    public function show_teacher_classes(User $teacher) {
        $classes = $teacher->lesson_rooms(false, true);
        $lesson_rooms = LessonRoom::all();
        $lessons = Lesson::all();

        $relation_ship_information = isset($_GET['edit-relation-ship']) && !empty($_GET['edit-relation-ship']) ? RelationShip::find($_GET['edit-relation-ship']) : '';
        if(!empty($relation_ship_information)) {
            $relation_ship_information['lesson_room'] = LessonRoom::find($relation_ship_information->userable_id);
            $relation_ship_information['lesson'] = $relation_ship_information->lesson;
        }
        return view('admins_dashboard.show_teacher_classes', ['teacher' => $teacher, 'classes' => $classes, 'lesson_rooms' => $lesson_rooms, 'lessons' => $lessons, 'relation_ship_information' => $relation_ship_information]);
    }
}
