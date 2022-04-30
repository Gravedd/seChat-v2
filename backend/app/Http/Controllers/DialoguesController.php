<?php

namespace App\Http\Controllers;

use App\Models\Dialogue;
use App\Models\Message;
use Illuminate\Http\Request;

class DialoguesController extends Controller
{
    public static function getDialogues($uid) {
        $dialogues1 = Dialogue::Where('user_id', $uid)->with('user2')->get();
        $dialogues2 = Dialogue::Where('user2_id', $uid)->with('user1')->get();
        return $dialogues2->merge($dialogues1);
    }
}
