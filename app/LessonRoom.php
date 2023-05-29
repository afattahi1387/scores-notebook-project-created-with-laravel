<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonRoom extends Model
{
    protected $fillable = ['name'];

    public function learners() {
        return $this->hasMany(Learner::class)->orderBy('row', 'ASC');
    }
}
