<?php

namespace App;

use App\RollCall;
use App\RelationShip;
use App\PNAndFinalScore;
use App\StudentAttendance;
use Illuminate\Database\Eloquent\Model;

class Learner extends Model
{
    protected $fillable = ['row', 'name', 'lesson_room_id', 'first_term_PN_number', 'second_term_PN_number', 'first_term_final_scores', 'second_term_final_scores'];

    public function get_p_n_and_final_score() {
        return PNAndFinalScore::where('learner_id', $this->id)->get()[0];
    }

    public function attendances($relation_ship_id) {
        $roll_calls = RollCall::where('relation_ship_id', $relation_ship_id)->get();
        $attendances = array();
        foreach($roll_calls as $roll_call) {
            $attendances[] = StudentAttendance::where('roll_call_id', $roll_call->id)->where('learner_id', $this->id)->get()[0];
        }

        return $attendances;
    }

    public function average_of_term_scores($relation_ship_id, $term) {
        $attendances = $this->attendances($relation_ship_id);
        $sum_of_scores = 0;
        $number_of_scores = 0;
        foreach($attendances as $attendance) {
            if(!empty($attendance->score) && $attendance->relavant_roll_call()->term == $term) {
                $sum_of_scores += $attendance->score;
                $number_of_scores++;
            }
        }

        return ($sum_of_scores != 0 && $number_of_scores != 0) ? ($sum_of_scores / $number_of_scores) : 0;
    }

    public function calculate_term_development_score($relation_ship_id, $term) {
        if(self::average_of_term_scores($relation_ship_id, $term) == 0) {
            return 0;
        }
        $score = self::average_of_term_scores($relation_ship_id, $term);
        $teacher_settings = TeacherSetting::where('user_id', RelationShip::find($relation_ship_id)->user_id)->get()[0];
        $p_n_and_final_score = PNAndFinalScore::where('relation_ship_id', $relation_ship_id)->where('learner_id', $this->id)->get()[0];
        if(!empty($teacher_settings['level_of_calculate_positive']) && !empty($teacher_settings['level_of_calculate_negative'])) {
            if(!empty($p_n_and_final_score[$term . '_term_PN_number'])) {
                $score += $p_n_and_final_score[$term . '_term_PN_number'] > 0 ? $p_n_and_final_score[$term . '_term_PN_number'] * $teacher_settings['level_of_calculate_positive'] : $p_n_and_final_score[$term . '_term_PN_number'] * $teacher_settings['level_of_calculate_negative'];;
            }
        }

        if(!empty($teacher_settings['level_of_calculate_absences'])) {
            $absences = StudentAttendance::where('learner_id', $this->id)->where('roll_call', 'absent')->get();
            $number_of_absences = 0;
            foreach($absences as $absence) {
                $absence_roll_call = RollCall::find($absence->roll_call_id);
                if(RelationShip::find($relation_ship_id)->check_roll_call_exists($absence->roll_call_id) && $absence_roll_call->term == $term) {
                    $number_of_absences++;
                }
            }
            
            $score -= ($number_of_absences * $teacher_settings['level_of_calculate_absences']);
        }

        if(is_float($score)) { // TODO: correct it
            $score = round($score);
        }
        
        if($score >= 20) {
            $score = 20;
        } elseif($score <= 0) {
            $score = 0;
        }
        
        return $score;
    }
}
