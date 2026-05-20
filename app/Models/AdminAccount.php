<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAccount extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
        'disabled_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'disabled_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function createdInviteKeys()
    {
        return $this->hasMany(AdminInviteKey::class, 'created_by');
    }

    public function usedInviteKeys()
    {
        return $this->hasMany(AdminInviteKey::class, 'used_by');
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }
}
