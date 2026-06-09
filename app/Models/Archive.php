<?php

namespace App\Models;

use App\Support\MediaStorage;
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

    public function getImageUrlAttribute(): string
    {
        return MediaStorage::url($this->image_path);
    }

    public function getImageLabelAttribute(): string
    {
        return MediaStorage::label($this->image_path);
    }
}
