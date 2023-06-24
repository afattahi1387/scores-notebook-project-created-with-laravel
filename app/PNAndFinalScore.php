<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PNAndFinalScore extends Model
{
    protected $fillable = ['relation_ship_id', 'learner_id', 'first_term_PN_number', 'second_term_PN_number', 'first_term_development_score', 'second_term_development_score', 'first_term_final_score', 'second_term_final_score'];
}
