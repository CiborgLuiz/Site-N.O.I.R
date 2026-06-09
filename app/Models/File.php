<?php

namespace App\Models;

use App\Support\MediaStorage;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'folder_id',
        'name',
        'type',
        'content',
        'path'
    ];

    public function getUrlAttribute(): string
    {
        return MediaStorage::url($this->path);
    }

    public function getPathLabelAttribute(): string
    {
        return MediaStorage::label($this->path);
    }
}
