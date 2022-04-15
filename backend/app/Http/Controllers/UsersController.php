<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}
