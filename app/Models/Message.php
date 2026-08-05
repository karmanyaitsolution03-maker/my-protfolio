<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['name', 'email', 'message', 'ai_category', 'ai_summary', 'ai_reply_draft', 'replied_at'];
    protected $casts = ['replied_at' => 'datetime'];
}
