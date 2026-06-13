<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillCategory extends Model
{
    protected $fillable = ['name', 'icon', 'wide', 'position'];
    protected $casts = ['wide' => 'boolean'];

    public function skills()
    {
        return $this->hasMany(Skill::class)->orderBy('position');
    }
}
