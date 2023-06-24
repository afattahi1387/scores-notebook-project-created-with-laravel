<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShowFlashMessageController extends Controller
{
    public function add_flash_message($message_type, $message_text) {
        session()->put(env('FLASH_NAME') . '_' . $message_type , $message_text);
    }

    public function get_flashed_messages() {
        $all_sessions = session()->all();
        $flashed_messages = array();
        $flashed_messages_for_delete = array();

        foreach(array_keys($all_sessions) as $session) {
            if(substr($session, 0, strlen(env('FLASH_NAME')) + 1) == env('FLASH_NAME') . '_') {
                $flashed_messages[substr($session, strlen(env('FLASH_NAME')) + 1)] = $all_sessions[$session];
                $flashed_messages_for_delete[] = $session;
            }
        }

        foreach($flashed_messages_for_delete as $flash) {
            session()->forget($flash);
            session()->forget([$flash]);
        }

        dd($all_sessions, $flashed_messages, $flashed_messages_for_delete);
    }
}
