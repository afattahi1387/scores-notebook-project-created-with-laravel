<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    protected $fillable = ['roll_call_id', 'learner_id', 'roll_call', 'score', 'description'];
}
