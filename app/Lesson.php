<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['name'];

    public function relation_ships() {
        return $this->hasMany(RelationShip::class);
    }
}
