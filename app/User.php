<?php

namespace App;

use App\Lesson;
use App\LessonRoom;
use App\RelationShip;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'username', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function lesson_rooms() {
        $rows = RelationShip::where('userable_type', 'App\LessonRoom')->where('user_id', auth()->user()->id)->get();
        $classes = array();
        foreach($rows as $row) {
            $array = $row;
            $array['class_name'] = LessonRoom::where('id', $array['userable_id'])->get()[0]['name'];
            $array['lesson_name'] = Lesson::where('id', $array['lesson_id'])->get()[0]['name'];
            $classes[] = $array;
        }
        
        return $classes;
    }
}
