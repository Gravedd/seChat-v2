<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function getUserMessagesInDialog(Request $request, $user_id) {
        $uid = $request->user()->id;//айди авторизованного пользователя
        $messages = Message::Where(function ($query) use ($uid, $user_id)
        {
            $query->Where('sender_id', $uid)->Where('receiver_id', $user_id);
        })->orWhere(function ($query) use ($uid, $user_id){
            $query->Where('sender_id', $user_id)->Where('receiver_id', $uid);
        })->orderby('id', 'DESC')->paginate(30);
        return response()->json($messages);
    }
    public static function getUserMessagesInDialogWS($uid, $user_id) {
        $messages = Message::Where(function ($query) use ($uid, $user_id)
        {
            $query->Where('sender_id', $uid)->Where('receiver_id', $user_id);
        })->orWhere(function ($query) use ($uid, $user_id){
            $query->Where('sender_id', $user_id)->Where('receiver_id', $uid);
        })->orderby('id', 'DESC')->get();
        return $messages;
    }

    public static function SaveMessageInDB($sender_id, $receiver_id, $msg) {
        $message = new Message();
            $message->sender_id = $sender_id;
            $message->receiver_id = $receiver_id;
            $message->message = $msg;
        return $message->save();
    }

}
