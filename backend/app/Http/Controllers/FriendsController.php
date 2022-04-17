<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    /**
     * Найти друзей какого-либо пользователя
     *
     * Метод ищет в БД друзей пользователя и их профили(id, name),
     * И возвращает коллекцию
     *
     * @param $userid string - айди пользователя которому необходимо найти друзей
     * @param $type integer - тип ( 0 - не подтверждено, 1 - подтверждено )
     * @return mixed
     */
    public function getfriends($userid, $type){
        $friendsuser = Friend::
        //Выбрать где айди пользователя равен айди авторизированого
        Where('user_id', $userid)
        //Выбрать тип ( 0 - не подтверждено, 1 - подтверждено )
        ->Where('friends.type', $type)
            //С получением информации и друге
            ->with('frienduser')
            ->get();
        $userfriends = Friend::
            //Выбрать где айди пользователя равен айди авторизированого пользователя наоборот
            Where('friend_id', $userid)
            //Выбрать тип ( 0 - не подтверждено, 1 - подтверждено )
            ->Where('friends.type', $type)
            //С получением информации и друге
            ->with('userfriend')
            ->get();
        $result = $friendsuser->merge($userfriends);
        return $result;
    }

    /**
     * GET /friends/
     *
     * Получить информацию о подтвержденных друзьях пользователя
     *
     * @param Request $request - пустой
     * @return \Illuminate\Http\JsonResponse
     */
    public function friendlistapproved(Request $request) {
        return response()->json($this->getfriends($request->user()->id, 1));
    }

    /**
     * GET /friends/unproved
     *
     * Получить информацию о взодящих заявках в друзья
     *
     * @param Request $request - пустой
     * @return \Illuminate\Http\JsonResponse
     */
    public function friendlistunproved(Request $request) {
        $userfriends = Friend::
        //Выбрать где айди пользователя равен айди авторизированого пользователя наоборот
        Where('friend_id', $request->user()->id)
            //Выбрать тип ( 0 - не подтверждено, 1 - подтверждено )
            ->Where('friends.type', 0)
            //С получением информации и друге
            ->with('userfriend')
            ->get();

        return response()->json($userfriends);
    }

    /**
     * POST /friends/{friend_id}
     *
     * Запрос в друзья пользователю {friend_id} от пользователя с запросом
     *
     * @param Request $request
     * @param $friend_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function friendrequest(Request $request, $friend_id) {
        $id = $request->user()->id;
        $friendreq = Friend::Where('user_id', $id)->Where('friend_id', $friend_id)->first();
        if ($friendreq) {
            return response()->json(["status"=>false, 'message'=>'Ваш запрос в друзья уже существует']);
        }
        $friendreqvv = Friend::Where('user_id', $friend_id)->Where('friend_id', $id)->first();
        if ($friendreqvv) {
            $friendreqvv->type = 1;
            $result = $friendreqvv->save();
            return response()->json(["status"=> $result, 'message'=>'Запрос в друзья подтвержден']);
        }
        $friendrequest = new Friend;
            $friendrequest->user_id = $id;
            $friendrequest->friend_id = $friend_id;
        $result = $friendrequest->save();
        return response()->json(["status"=> $result, 'message'=>'Запрос в друзья успешно отправлен']);
    }
    public function deletefriend(Request $request, $friend_id) {
        $id = $request->user()->id;
        $friendentry = Friend::
            Where(function ($query) use ($id, $friend_id){
                $query->Where('user_id', $id)->Where('friend_id', $friend_id);
            })->
            orWhere(function ($query)  use ($id, $friend_id){
                $query->Where('user_id', $friend_id)->Where('friend_id', $id);
            })->first();
            $result = $friendentry->delete();
        return response()->json(['status'=> $result, 'message'=> 'Успешно удален из друзей']);
    }

    public static function checkIfUserIsFriend($id, $friend_id, $type){
        $friend = Friend::
        Where(function ($query) use ($id, $friend_id){
            $query->Where('user_id', $id)->Where('friend_id', $friend_id);
        })->
        orWhere(function ($query)  use ($id, $friend_id){
            $query->Where('user_id', $friend_id)->Where('friend_id', $id);
        })->Where('type', $type)->first();

        if (isset($friend)) {
            return $friend->type;
        } else {
            return false;
        }
    }


}
