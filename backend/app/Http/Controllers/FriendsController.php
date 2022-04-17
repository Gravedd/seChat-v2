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
     * GET /friends/unproved
     *
     * Получить информацию о не подтвержденных друзьях пользователя
     *
     * @param Request $request - пустой
     * @return \Illuminate\Http\JsonResponse
     */
    public function friendlistapproved(Request $request) {
        return response()->json($this->getfriends($request->user()->id, 1));
    }

    /**
     * GET /friends/
     *
     * Получить информацию о подтвержденных друзьях пользователя
     *
     * @param Request $request - пустой
     * @return \Illuminate\Http\JsonResponse
     */
    public function friendlistunproved(Request $request) {
        return response()->json($this->getfriends($request->user()->id, 0));
    }
}
