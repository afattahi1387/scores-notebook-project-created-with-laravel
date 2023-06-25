<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RollCall extends Model
{
    protected $fillable = ['date', 'term', 'date_description', 'relation_ship_id'];

    public function relation_ship() {
        return $this->belongsTo(RelationShip::class);
    }

    public function student_attendances() {
        return $this->hasMany(StudentAttendance::class);
    }
}
