<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function findUsers () {
        if (isset($_GET['q'])) {
            $searchname = $_GET['q'];
            $users = User::Select('id', 'name', 'status')->Where('name', 'like', '%'. $searchname .'%')->paginate(5);
        } else {
            $users = User::Select('id', 'name', 'status')->paginate(5);
        }
        return response()->json($users, 200);
    }
    public function getUser ($userid) {
        $user = User::Select('id', 'name', 'status', 'created_at', 'updated_at')->find($userid);
        if (!isset($user)) {
            return response()->json($user, 404);
        }
        return response()->json($user);
    }
}
