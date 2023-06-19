<?php

namespace App\Http\Controllers;

use App\User;
use App\Lesson;
use App\Learner;
use App\RollCall;
use App\LessonRoom;
use App\RelationShip;
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
        return view('dashboard.show_students_list', ['lesson_room' => $lesson_room, 'learners' => $lesson_room->learners]);
    }

    public function show_learner_information(Learner $learner) {
        $score_for_edit = isset($_GET['edit-score']) && !empty($_GET['edit-score']) ? StudentAttendance::find($_GET['edit-score']) : '';
        return view('dashboard.show_learner_information', ['learner' => $learner, 'attendances' => $learner->attendances, 'score_for_edit' => $score_for_edit]);
    }

    public function update_term_final_score(Request $request, Learner $learner, $term) {
        $learner->update([
            $term . '_term_final_scores' => $request->term_final_score
        ]);

        // TODO: add flash message
        return redirect()->route('show.learner.information', ['learner' => $learner->id]);
    }

    public function update_score(Request $request, StudentAttendance $score) { // TODO: add request
        $score->update([
            'score' => $request->score
        ]);

        // TODO: add flash message
        return redirect()->route('show.learner.information', ['learner' => $score->learner->id]);
    }
}
