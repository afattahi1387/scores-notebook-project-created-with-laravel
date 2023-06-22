<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelationShip extends Model
{
    protected $fillable = ['user_id', 'userable_id', 'userable_type', 'lesson_id'];

    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }

    public function roll_calls() {
        return $this->hasMany(RollCall::class);
    }

    public function get_roll_calls_id() {
        $roll_calls_id = array();
        foreach($this->roll_calls as $roll_call) {
            $roll_calls_id[] = $roll_call->id;
        }

        return $roll_calls_id;
    }

    public function check_roll_call_exists($roll_call_id) {
        $roll_calls_id = self::get_roll_calls_id();
        if(in_array($roll_call_id, $roll_calls_id)) {
            return true;
        }

        return false;
    }
}
