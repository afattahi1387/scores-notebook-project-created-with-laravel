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
use Illuminate\Support\Facades\Artisan;
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

    public function redirect_to_teachers_dashboard() {
        if(auth()->user()->type == 'teacher') {
            return redirect()->route('dashboard');
        }
    }

    public function admins_dashboard() {
        self::redirect_to_teachers_dashboard();

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

    public function restore_project_page() {
        self::redirect_to_teachers_dashboard();

        return view('admins_dashboard.restore_project_page');
    }

    public function restore_project_function() {
        self::redirect_to_teachers_dashboard();

        $admin_name = auth()->user()->name;
        $admin_username = auth()->user()->username;
        $admin_password = auth()->user()->password;

        Artisan::call('migrate:rollback');
        auth()->logout();
        Artisan::call('migrate');
        User::create([
            'name' => $admin_name,
            'type' => 'admin',
            'username' => $admin_username,
            'password' => $admin_password
        ]);

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'اطلاعات سایت شما کاملا پاک شد.');
        return redirect()->route('admins.dashboard');
    }

    public function insert_lesson_room(AddAndEditNameRequest $request) {
        self::redirect_to_teachers_dashboard();

        $name = $request->name;
        LessonRoom::create([
            'name' => $name
        ]);

        return redirect()->route('admins.dashboard');
    }

    public function update_lesson_room(AddAndEditNameRequest $request, LessonRoom $lesson_room) {
        self::redirect_to_teachers_dashboard();

        $name = $request->name;
        $lesson_room->update([
            'name' => $name
        ]);

        return redirect()->route('admins.dashboard');
    }

    public function delete_lesson_room(LessonRoom $lesson_room) {
        self::redirect_to_teachers_dashboard();

        foreach($lesson_room->relation_ships() as $relation_ship) {
            self::delete_relation_ship($relation_ship, false);
        }

        foreach($lesson_room->learners as $learner) {
            self::delete_learner($learner, false);
        }

        $lesson_room->delete();
        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'کلاس درس مورد نظر شما با موفقیت حذف شد.');
        return redirect()->route('admins.dashboard');
    }

    public function insert_lesson(AddAndEditNameRequest $request) {
        self::redirect_to_teachers_dashboard();

        $name = $request->name;
        Lesson::create([
            'name' => $name
        ]);

        return redirect()->route('admins.dashboard');
    }

    public function update_lesson(AddAndEditNameRequest $request, Lesson $lesson) {
        self::redirect_to_teachers_dashboard();
        
        $name = $request->name;
        $lesson->update([
            'name' => $name
        ]);

        return redirect()->route('admins.dashboard');
    }

    public function delete_lesson(Lesson $lesson) {
        self::redirect_to_teachers_dashboard();

        foreach($lesson->relation_ships as $relation_ship) {
            self::delete_relation_ship($relation_ship, false);
        }

        $lesson->delete();
        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'درس مورد نظر شما با موفقیت حذف شد.');
        return redirect()->route('admins.dashboard');
    }

    public function insert_teacher(AddTeacherRequest $request) {
        self::redirect_to_teachers_dashboard();
        
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
        self::redirect_to_teachers_dashboard();
        
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
        self::redirect_to_teachers_dashboard();
        
        DB::insert('INSERT INTO relation_ships VALUES (NULL, ?, ?, ?, ?)', [$teacher->id, $request->lesson_room, 'App\LessonRoom', $request->lesson]);

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'کلاس مورد نظر شما با موفقیت اضافه شد.');
        return redirect()->route('show.teacher.classes', ['teacher' => $teacher->id]);
    }

    public function update_relation_ship(AddRelationShipRequest $request, RelationShip $relation_ship) {
        self::redirect_to_teachers_dashboard();
        
        $lesson_room = $request->lesson_room;
        $lesson = $request->lesson;
        $teacher_id = $relation_ship->user_id;

        DB::update('UPDATE relation_ships SET userable_id = ?, lesson_id = ? WHERE id = ?', [$lesson_room, $lesson, $relation_ship->id]);

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'کلاس مورد نظر شما با موفقیت ویرایش شد.');
        return redirect()->route('show.teacher.classes', ['teacher' => $teacher_id]);
    }

    public function delete_relation_ship(RelationShip $relation_ship, $show_flash_message_and_redirect = true) {
        self::redirect_to_teachers_dashboard();

        $teacher_id = $relation_ship->user_id;

        foreach($relation_ship->roll_calls as $roll_call) {
            foreach($roll_call->student_attendances as $student_attendance) {
                $student_attendance->delete();
            }

            $roll_call->delete();
        }

        $relation_ship->delete();
        if($show_flash_message_and_redirect) {
            $show_flash_message = new ShowFlashMessageController();
            $show_flash_message->add_flash_message('success', 'کلاس مورد نظر شما با موفقیت حذف شد.');
            return redirect()->route('show.teacher.classes', ['teacher' => $teacher_id]);
        }
    }

    public function insert_learners_for_lesson_room(Request $requests, $lesson_room) { // TODO: add request
        self::redirect_to_teachers_dashboard();
        
        $keys = [];
        foreach($requests->all() as $key => $request) {
            if(substr($key, 0, 12) == 'learner_row_') {
                $keys[substr($key, 12)] = $requests['learner_row_' . substr($key, 12)];
            }
        }

        foreach($keys as $k => $value) {
            $new_learner = Learner::create([
                'row' => $keys[$value],
                'name' => $requests->all()['learner_name_' . $k],
                'lesson_room_id' => $lesson_room
            ]);

            $relation_ships = RelationShip::where('userable_id', $lesson_room)->where('userable_type', 'App\LessonRoom')->get();
            foreach($relation_ships as $relation_ship) {
                PNAndFinalScore::create([
                    'relation_ship_id' => $relation_ship->id,
                    'learner_id' => $new_learner->id
                ]);
            }
        }

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'دانش آموز شما برای این کلاس با موفقیت اضافه شد.');
        return redirect()->route('show.students.list.for.admins', ['lesson_room' => $lesson_room]);
    }

    public function update_learner(EditLearnerRequest $request, Learner $learner) {
        self::redirect_to_teachers_dashboard();
        
        $learner->update([
            'row' => $request->learner_row,
            'name' => $request->learner_name
        ]);

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'دانش آموز مورد نظر شما با موفقیت ویرایش شد.');
        return redirect()->route('show.students.list.for.admins', ['lesson_room' => $learner->lesson_room_id]);
    }

    public function delete_learner(Learner $learner, $show_flash_message_and_redirect = true) {
        self::redirect_to_teachers_dashboard();

        $lesson_room_id = $learner->lesson_room->id;
        foreach(PNAndFinalScore::where('learner_id', $learner->id) as $pn_and_final_score) {
            $pn_and_final_score->delete();
        }

        $learner->delete();

        if($show_flash_message_and_redirect) {
            $show_flash_message = new ShowFlashMessageController();
            $show_flash_message->add_flash_message('success', 'دانش آموز مورد نظر شما با موفقیت حذف شد.');
            return redirect()->route('show.students.list.for.admins', ['lesson_room' => $lesson_room_id]);
        }
    }

    public function show_students_list(LessonRoom $lesson_room) {
        self::redirect_to_teachers_dashboard();
        
        if(isset($_GET['edit-learner']) && !empty($_GET['edit-learner'])) {
            $learner_for_edit = Learner::find($_GET['edit-learner']);
        } else {
            $learner_for_edit = '';
        }
        return view('admins_dashboard.show_students_list_for_admin', ['lesson_room' => $lesson_room, 'learners' => $lesson_room->learners, 'learner_for_edit' => $learner_for_edit]);
    }

    public function show_teacher_classes(User $teacher) {
        self::redirect_to_teachers_dashboard();
        
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
