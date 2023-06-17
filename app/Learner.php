<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Learner extends Model
{
    protected $fillable = ['row', 'name', 'lesson_room_id', 'PN_number'];
}
