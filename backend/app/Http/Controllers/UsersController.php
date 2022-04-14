<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function findUsers () {
        if (isset($_GET['q'])) {
            $searchname = $_GET['q'];
            $users = User::Select('id', 'name', 'created_at', 'status')->Where('name', 'like', '%'. $searchname .'%')->paginate(3);
        } else {
            $users = User::Select('id', 'name', 'created_at', 'status')->paginate(3);
        }
        return response()->json($users, 200);
    }
}
