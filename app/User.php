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

    public function lesson_rooms($get_user_authentication = true, $orderby_DESC = false) {
        if($get_user_authentication) {
            $user_id = auth()->user()->id;
        } else {
            $user_id = $this->id;
        }
        $rows = $orderby_DESC ? RelationShip::where('userable_type', 'App\LessonRoom')->where('user_id', $user_id)->orderBy('id', 'DESC')->get() : RelationShip::where('userable_type', 'App\LessonRoom')->where('user_id', $user_id)->get();
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
