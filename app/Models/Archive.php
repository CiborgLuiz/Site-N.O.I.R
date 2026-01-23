<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $fillable = [
        'name',
        'identifier',
        'classification',
        'image_path',
        'description'
    ];
}
