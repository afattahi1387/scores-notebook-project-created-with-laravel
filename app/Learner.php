<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Learner extends Model
{
    protected $fillable = ['row', 'name', 'lesson_room_id', 'PN_number'];

    public function attendances() {
        return $this->hasMany(StudentAttendance::class);
    }

    public function average_of_term_scores($term) {
        $attendances = $this->attendances;
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

    public function average_of_first_term_scores() {
        return self::average_of_term_scores('first');
    }

    public function average_of_second_term_scores() {
        return self::average_of_term_scores('second');
    }
}
