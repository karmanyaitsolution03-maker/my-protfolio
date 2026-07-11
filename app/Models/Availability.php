<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = ['label', 'value', 'accent', 'position'];

    /** Map the friendly accent name to the CSS class used in the report card. */
    public function accentClass(): string
    {
        return match ($this->accent) {
            'cyan'  => 'cy',
            'green' => 'gn',
            default => '',
        };
    }
}
