<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    const ROLE_USER = "user";
    const ROLE_BOT = "bot";

    public $fillable = ['chat_id', 'role', 'content'];
}
