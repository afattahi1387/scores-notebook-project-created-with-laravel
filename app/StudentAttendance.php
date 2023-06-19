<?php

namespace App;

use App\RollCall;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    protected $fillable = ['roll_call_id', 'learner_id', 'roll_call', 'score', 'description'];

    public function relavant_roll_call() {
        $roll_call_id = $this->roll_call_id;
        return RollCall::where('id', $roll_call_id)->get()[0];
    }
}
