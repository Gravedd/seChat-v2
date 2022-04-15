<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Void_;

class UsersController extends Controller
{
    /**
     * GET /users?page={page_number}&q={search_query}
     * get users with pagination by search query
     *
     * Requires a auth(token in header)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Return users(5)
     */
    public function findUsers () {
        if (isset($_GET['q'])) {
            $searchname = $_GET['q'];
            $users = User::Select('id', 'name', 'status')->Where('name', 'like', '%'. $searchname .'%')->paginate(5);
        } else {
            $users = User::Select('id', 'name', 'status')->paginate(5);
        }
        return response()->json($users, 200);
    }

    /**
     * GET /users/{user_id}
     * get user by id
     * Requires a auth(token in header)
     * @param $userid integer required
     * @return \Illuminate\Http\JsonResponse
     * Return user info
     */
    public function getUser ($userid) {
        $user = User::Select('id', 'name', 'status', 'created_at', 'updated_at')->find($userid);
        if (!isset($user)) {
            return response()->json($user, 404);
        }
        return response()->json($user);
    }


    /**
     * PATCH /users
     * change somethigns user information
     * Requires a auth(token in header)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * the method is selected according to the sent data
     */
    public function changeSomething(Request $request) {
        if ($request->newname) {
            return $this->changename($request);
        }
        return response()->json(['status' => false, 'message'=>'Ошибка. Не известный метод']);
    }

    /**
     * called from a method "changeSomething"
     * change user name
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * return status
     */
    public function changename($request) {
        //new name validation
        $fields = $request->validate([
            'newname' => 'required|string|max:32|min:3',
        ]);
        $user = $request->user(); //get logged user
        $user->name = $request->newname; //change name
        $result = $user->save(); // if success returns true
        return response()->json(['status'=> $result]);
    }



}
