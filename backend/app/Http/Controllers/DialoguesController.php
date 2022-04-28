<?php

namespace App\Http\Controllers;

use App\Models\Dialogue;
use App\Models\Message;
use Illuminate\Http\Request;

class DialoguesController extends Controller
{
    public function getDialogues(Request $request) {
        echo microtime().'<br><br>';
        $dialogues1 = Dialogue::Where('user_id', 1)->with('user2')->get();
        $dialogues2 = Dialogue::Where('user2_id', 1)->with('user1')->get();
        echo microtime().'<br><br>';

        echo microtime().'<br><br>';
        dd($dialogues2->merge($dialogues1));
        return response()->json($dialogues2->merge($dialogues1));
    }
}
