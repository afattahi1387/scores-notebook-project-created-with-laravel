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

class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function dashboard() {
        $user = User::find(auth()->user()->id);
        $lesson_rooms = $user->lesson_rooms();
        return view('dashboard.dashboard', ['lesson_rooms' => $lesson_rooms]);
    }

    public function teachers_settings() {
        // if(auth()->user()->type != 'teacher') {
        //     TODO: set it
        //     TODO: add flash message
        // }

        return view('dashboard.teacher_settings');
    }

    public function set_settings(Request $request) {
        // if(auth()->user()->type != 'teacher') {
        //     TODO: set it
        //     TODO: add flash message
        // }

        $LOCP = empty($request->no_LOCP) ? $request->LOCP : null;
        $LOCN = empty($request->no_LOCN) ? $request->LOCN : null;
        $LOCA = empty($request->no_LOCA) ? $request->LOCA : null;

        TeacherSetting::find(auth()->user()->id)->update([
            'level_of_calculate_positive' => $LOCP,
            'level_of_calculate_negative' => $LOCN,
            'level_of_calculate_absences' => $LOCA
        ]);

        // TODO: add flash message
        return redirect()->route('teachers.settings');
    }

    public function add_date(LessonRoom $lesson_room, Lesson $lesson) {
        $lesson_room_learners = $lesson_room->learners;
        return view('dashboard.add_date', ['lesson_room' => $lesson_room, 'lesson' => $lesson, 'lesson_room_learners' => $lesson_room_learners]);
    }

    public function insert_date(AddDateRequest $request, LessonRoom $lesson_room, Lesson $lesson) {
        $day = $request->day_number < 10 ? '0' . $request->day_number : $request->day_number;
        $month = $request->month_number < 10 ? '0' . $request->month_number : $request->month_number;
        $year = $request->year_number < 10 ? '0' . $request->year_number : $request->year_number;
        $term = $request->term;
        $lesson_room_learners = $lesson_room->learners;
        $roll_calls = array();
        $scores = array();
        $PNs = array();
        $descriptions = array();

        foreach($lesson_room_learners as $learner) {
            $roll_calls[$learner->id] = $request['roll_call_' . $learner->id];

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
                $old_PN = $learner->PN_number;
                $learner->update([
                    'PN_number' => $old_PN + $PNs[$learner->id]
                ]);
            }

            return redirect()->route('dashboard');
        }
    }

    public function show_students_list(LessonRoom $lesson_room, Lesson $lesson) {
        $relation_ship_id = RelationShip::where('user_id', auth()->user()->id)->where('userable_id', $lesson_room->id)->where('userable_type', 'App\LessonRoom')->where('lesson_id', $lesson->id)->get()[0]['id'];
        return view('dashboard.show_students_list', ['lesson_room' => $lesson_room, 'learners' => $lesson_room->learners, 'relation_ship_id' => $relation_ship_id]);
    }

    public function show_learner_information(Learner $learner, RelationShip $relation_ship) {
        $roll_calls = RollCall::where('relation_ship_id', $relation_ship->id)->get();
        $attendances = array();
        foreach($roll_calls as $roll_call) {
            $attendances[] = StudentAttendance::where('roll_call_id', $roll_call->id)->where('learner_id', $learner->id)->get()[0];
        }
        $score_for_edit = isset($_GET['edit-score']) && !empty($_GET['edit-score']) ? StudentAttendance::find($_GET['edit-score']) : '';
        return view('dashboard.show_learner_information', ['learner' => $learner, 'attendances' => $attendances, 'score_for_edit' => $score_for_edit, 'relation_ship' => $relation_ship]);
    }

    public function update_term_final_score(Request $request, Learner $learner, RelationShip $relation_ship, $term) {
        $p_n_and_final_score = PNAndFinalScore::where('relation_ship_id', $relation_ship->id)->where('learner_id', $learner->id)->get()[0];
        $p_n_and_final_score->update([
            $term . '_term_final_score' => $request->term_final_score
        ]);

        // TODO: add flash message
        return redirect()->route('show.learner.information', ['learner' => $learner->id, 'relation_ship' => $relation_ship->id]);
    }

    public function update_score(Request $request, StudentAttendance $score) { // TODO: add request
        $score->update([
            'score' => $request->score
        ]);

        // TODO: add flash message
        return redirect()->route('show.learner.information', ['learner' => $score->learner->id]);
    }

    public function change_pn_number(Request $request, Learner $learner) {
        $learner->update([
            'first_term_PN_number' => $request->first_term_PN_number,
            'second_term_PN_number' => $request->second_term_PN_number
        ]);

        // TODO: add flash message
        return redirect()->route('show.learner.information', ['learner' => $learner->id]);
    }
}
