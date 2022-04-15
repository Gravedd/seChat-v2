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
        $fields = $request->validate([
            'newname' => 'string|max:32|min:3',
            'newstatus' => 'string|max:256|min:3',
            'newemail' => 'string|unique:users,email|email',
        ]);
        $user = $request->user(); //get logged user
        //if request has something field then write him in user
        if ($request->newname) {
            $user->name = $request->newname;
        }
        if ($request->newstatus) {
            $user->status = $request->newstatus;
        }
        if ($request->newemail) {
            $user->email = $request->newemail;
        }
        //Save user
        $result = $user->save(); // if success returns true
        return response()->json(['status' => $result]);
    }

//    /**
//     * called from a method "changeSomething"
//     * change user name
//     * @param $request
//     * @return \Illuminate\Http\JsonResponse
//     * return status
//     */
//    public function changeName($request) {
//        //new name validation
//        $fields = $request->validate([
//            'newname' => 'required|string|max:32|min:3',
//        ]);
//        $user = $request->user(); //get logged user
//        $user->name = $request->newname; //change name
//        $result = $user->save(); // if success returns true
//        return response()->json(['status'=> $result]);
//    }
//
//    public function changeStatus($request) {
//        $fields = $request->validate([
//            'newstatus' => 'required|string|max:256|min:3',
//        ]);
//        $user = $request->user();
//        $user->status = $request->newstatus; //change status
//        $result = $user->save();
//        return response()->json(['status'=> $result]);
//    }


}
