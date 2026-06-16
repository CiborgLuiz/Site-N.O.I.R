<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TerminalCommand extends Model
{
    protected $fillable = [
        'command',
        'response',
        'active',
    ];
}