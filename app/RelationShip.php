<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelationShip extends Model
{
    protected $fillable = ['user_id', 'userable_id', 'userable_type', 'lesson_id'];

    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }
}
