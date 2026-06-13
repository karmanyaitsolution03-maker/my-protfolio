<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title', 'kicker', 'description', 'color', 'wide', 'arch', 'metrics', 'tags', 'position'];
    protected $casts = ['wide' => 'boolean', 'arch' => 'array', 'metrics' => 'array', 'tags' => 'array'];
}
