<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = ['name', 'icon'];

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
