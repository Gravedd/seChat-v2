<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;
    public function frienduser(){
        return $this->hasOne(User::class, 'id', 'friend_id')->select('users.id', 'users.name');
    }
    public function userfriend(){
        return $this->hasOne(User::class, 'id', 'user_id')->select('users.id', 'users.name');
    }
}
