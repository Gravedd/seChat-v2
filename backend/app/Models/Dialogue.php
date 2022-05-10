<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Dialogue extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'dialogues';

    protected function serializeDate(DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }

    public function user1() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function user2() {
        return $this->belongsTo(User::class, 'user2_id');
    }

}
