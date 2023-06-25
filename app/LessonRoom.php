<?php

namespace App;

use App\RelationShip;
use Illuminate\Database\Eloquent\Model;

class LessonRoom extends Model
{
    protected $fillable = ['name'];

    public function relation_ships() {
        return RelationShip::where('userable_id', $this->id)->where('userable_type', 'App\LessonRoom')->get();
    }

    public function learners() {
        return $this->hasMany(Learner::class)->orderBy('row', 'ASC');
    }
}
