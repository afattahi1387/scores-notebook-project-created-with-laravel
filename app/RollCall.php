<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RollCall extends Model
{
    protected $fillable = ['date', 'term', 'relation_ship_id'];
}
