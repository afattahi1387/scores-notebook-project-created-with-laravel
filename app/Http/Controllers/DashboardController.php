<?php

namespace App\Http\Controllers;

use App\User;
use App\Lesson;
use App\Learner;
use App\RollCall;
use App\LessonRoom;
use App\RelationShip;
use App\TeacherSetting;
use App\PNAndFinalScore;
use App\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AddDateRequest;
use App\Http\Controllers\ShowFlashMessageController;

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function redirect_to_admins_dashboard() {
        if(auth()->user()->type == 'admin') {
            return redirect()->route('admins.dashboard');
        }
    }

    public function dashboard() {
        self::redirect_to_admins_dashboard();

        $user = User::find(auth()->user()->id);
        $lesson_rooms = $user->lesson_rooms();
        return view('dashboard.dashboard', ['lesson_rooms' => $lesson_rooms]);
    }

    public function teachers_settings() {
        self::redirect_to_admins_dashboard();

        if(auth()->user()->type != 'teacher') {
            return redirect()->route('admins.dashboard');
        }

        return view('dashboard.teacher_settings');
    }

    public function set_settings(Request $request) {
        if(auth()->user()->type != 'teacher') {
            return redirect()->route('admins.dashboard');
        }

        $LOCP = empty($request->no_LOCP) ? $request->LOCP : null;
        $LOCN = empty($request->no_LOCN) ? $request->LOCN : null;
        $LOCA = empty($request->no_LOCA) ? $request->LOCA : null;

        TeacherSetting::find(auth()->user()->id)->update([
            'level_of_calculate_positive' => $LOCP,
            'level_of_calculate_negative' => $LOCN,
            'level_of_calculate_absences' => $LOCA
        ]);

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'تنظیمات شما با موفقیت ویرایش شد.');
        return redirect()->route('teachers.settings');
    }

    public function add_date(LessonRoom $lesson_room, Lesson $lesson) {
        self::redirect_to_admins_dashboard();

        $lesson_room_learners = $lesson_room->learners;
        return view('dashboard.add_date', ['lesson_room' => $lesson_room, 'lesson' => $lesson, 'lesson_room_learners' => $lesson_room_learners]);
    }

    public function insert_date(AddDateRequest $request, LessonRoom $lesson_room, Lesson $lesson) {
        self::redirect_to_admins_dashboard();

        $day = $request->day_number < 10 ? '0' . $request->day_number : $request->day_number;
        $month = $request->month_number < 10 ? '0' . $request->month_number : $request->month_number;
        $year = $request->year_number < 10 ? '0' . $request->year_number : $request->year_number;
        $term = $request->term;
        $date_description = !empty($request->date_description) ? $request->date_description : null;
        $lesson_room_learners = $lesson_room->learners;
        $roll_calls = array();
        $scores = array();
        $PNs = array();
        $descriptions = array();

        foreach($lesson_room_learners as $learner) {
            if(empty($request['roll_call_' . $learner->id])) {
                $roll_calls[$learner->id] = null;
            } else {
                $roll_calls[$learner->id] = $request['roll_call_' . $learner->id];
            }

            if(empty($request['score_' . $learner->id])) {
                $scores[$learner->id] = null;
            } else {
                $scores[$learner->id] = $request['score_' . $learner->id];
            }

            if(empty($request['PN_' . $learner->id])) {
                $PNs[$learner->id] = null;
            } else {
                $PNs[$learner->id] = $request['PN_' . $learner->id];
            }

            if(empty($request['description_' . $learner->id])) {
                $descriptions[$learner->id] = null;
            } else {
                $descriptions[$learner->id] = $request['description_' . $learner->id];
            }
        }

        $relation_ship = RelationShip::where('userable_id', $lesson_room->id)->where('userable_type', 'App\LessonRoom')->where('lesson_id', $lesson->id)->get()[0];

        $new_roll_call = RollCall::create([
            'date' => $year . '/' . $month . '/' . $day,
            'term' => $term,
            'date_description' => $date_description,
            'relation_ship_id' => $relation_ship->id
        ]);

        $new_id = $new_roll_call->id;
        foreach($lesson_room_learners as $learner) {
            StudentAttendance::create([
                'roll_call_id' => $new_id,
                'learner_id' => $learner->id,
                'roll_call' => $roll_calls[$learner->id],
                'score' => $scores[$learner->id],
                'description' => $descriptions[$learner->id]
            ]);

            if(!empty($PNs[$learner->id])) {
                $p_n_and_final_score = $learner->get_p_n_and_final_score();
                $old_PN = $p_n_and_final_score[$request->term . '_term_PN_number'];
                $p_n_and_final_score->update([
                    $request->term . '_term_PN_number' => $old_PN + $PNs[$learner->id]
                ]);
            }

            $show_flash_message = new ShowFlashMessageController();
            $show_flash_message->add_flash_message('success', 'تاریخ و حضور و غیاب مربوط به آن با موفقیت اضافه شد.');
            return redirect()->route('dashboard');
        }
    }

    public function show_students_list(LessonRoom $lesson_room, Lesson $lesson) {
        self::redirect_to_admins_dashboard();

        $relation_ship_id = RelationShip::where('user_id', auth()->user()->id)->where('userable_id', $lesson_room->id)->where('userable_type', 'App\LessonRoom')->where('lesson_id', $lesson->id)->get()[0]['id'];
        return view('dashboard.show_students_list', ['lesson_room' => $lesson_room, 'learners' => $lesson_room->learners, 'relation_ship_id' => $relation_ship_id]);
    }

    public function show_learner_information(Learner $learner, RelationShip $relation_ship) {
        self::redirect_to_admins_dashboard();

        $roll_calls = RollCall::where('relation_ship_id', $relation_ship->id)->get();
        $attendances = array();
        foreach($roll_calls as $roll_call) {
            $attendances[] = StudentAttendance::where('roll_call_id', $roll_call->id)->where('learner_id', $learner->id)->get()[0];
        }
        $score_for_edit = isset($_GET['edit-score']) && !empty($_GET['edit-score']) ? StudentAttendance::find($_GET['edit-score']) : '';
        $TDS_for_edit = isset($_GET['edit-term-development-score']) && !empty($_GET['edit-term-development-score']) ? PNAndFinalScore::where('relation_ship_id', $relation_ship->id)->where('learner_id', $learner->id)->get()[0] : '';
        return view('dashboard.show_learner_information', ['learner' => $learner, 'attendances' => $attendances, 'score_for_edit' => $score_for_edit, 'relation_ship' => $relation_ship, 'TDS_for_edit' => $TDS_for_edit]);
    }

    public function update_term_development_score(Request $request, $learner_id, $relation_ship_id, $term) {
        self::redirect_to_admins_dashboard();

        $p_n_and_final_score = PNAndFinalScore::where('relation_ship_id', $relation_ship_id)->where('learner_id', $learner_id)->get()[0];
        $p_n_and_final_score->update([
            $term . '_term_development_score' => $request['edit_term_development_score']
        ]);

        if($term == 'first') {
            $term_for_flash = 'اول';
        } elseif($term == 'second') {
            $term_for_flash = 'دوم';
        }

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', sprintf('نمره مستمر داده شده در ترم %s با موفقیت ثبت شد.', $term_for_flash));
        return redirect()->route('show.learner.information', ['learner' => $learner_id, 'relation_ship' => $relation_ship_id]);
    }

    public function update_term_final_score(Request $request, Learner $learner, RelationShip $relation_ship, $term) {
        self::redirect_to_admins_dashboard();

        $p_n_and_final_score = PNAndFinalScore::where('relation_ship_id', $relation_ship->id)->where('learner_id', $learner->id)->get()[0];
        $p_n_and_final_score->update([
            $term . '_term_final_score' => $request->term_final_score
        ]);

        if($term == 'first') {
            $term_for_flash = 'اول';
        } elseif($term == 'second') {
            $term_for_flash = 'دوم';
        }

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', sprintf('نمره پایانی ترم %s با موفقیت ثبت شد.', $term_for_flash));
        return redirect()->route('show.learner.information', ['learner' => $learner->id, 'relation_ship' => $relation_ship->id]);
    }

    public function update_score(Request $request, StudentAttendance $score) {
        self::redirect_to_admins_dashboard();

        $score->update([
            'score' => $request->score
        ]);

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'نمره شما با موفقیت ویرایش شد.');
        return redirect()->route('show.learner.information', ['learner' => $score->learner->id, 'relation_ship' => $score->relavant_roll_call()->relation_ship]);
    }

    public function change_pn_number(Request $request, Learner $learner, $relation_ship_id) {
        self::redirect_to_admins_dashboard();

        $p_n_and_final_score = PNAndFinalScore::where('relation_ship_id', $relation_ship_id)->where('learner_id', $learner->id)->get()[0];
        $p_n_and_final_score->update([
            'first_term_PN_number' => $request->first_term_PN_number,
            'second_term_PN_number' => $request->second_term_PN_number
        ]);

        $show_flash_message = new ShowFlashMessageController();
        $show_flash_message->add_flash_message('success', 'مثبت و منفی ترم اول و دوم برای این دانش آموز با موفقیت ثبت شد.');
        return redirect()->route('show.learner.information', ['learner' => $learner->id, 'relation_ship' => $relation_ship_id]);
    }
}
