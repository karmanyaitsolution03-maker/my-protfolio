<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['company', 'sub', 'role', 'period', 'live', 'responsibilities', 'position'];
    protected $casts = ['live' => 'boolean', 'responsibilities' => 'array'];
}
