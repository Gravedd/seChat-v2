<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * Get all messages of users 1 and 2
     *
     * @param $uid
     * @param $user_id
     * @return mixed - messages
     */
    public static function getUserMessagesInDialogWS($uid, $user_id) {
        $messages = Message::Where(function ($query) use ($uid, $user_id)
        {
            $query->Where('sender_id', $uid)->Where('receiver_id', $user_id);
        })->orWhere(function ($query) use ($uid, $user_id){
            $query->Where('sender_id', $user_id)->Where('receiver_id', $uid);
        })->orderby('id', 'DESC')->get();
        return $messages;
    }

    /**
     * Аdd message to database
     *
     * @param $sender_id
     * @param $receiver_id
     * @param $msg - message
     * @return bool
     */
    public static function SaveMessageInDB($sender_id, $receiver_id, $msg) {
        $message = new Message();
            $message->sender_id = $sender_id;
            $message->receiver_id = $receiver_id;
            $message->message = $msg;
        return $message->save();
    }

}
