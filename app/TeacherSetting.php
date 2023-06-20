<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherSetting extends Model
{
    protected $fillable = ['user_id', 'level_of_calculate_positive', 'level_of_calculate_negative', 'level_of_calculate_absences'];
}
