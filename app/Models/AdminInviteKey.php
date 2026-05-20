<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminInviteKey extends Model
{
    protected $fillable = [
        'code_hash',
        'role',
        'active',
        'created_by',
        'used_by',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
            'used_at' => 'datetime',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(AdminAccount::class, 'created_by');
    }

    public function usedBy()
    {
        return $this->belongsTo(AdminAccount::class, 'used_by');
    }

    public function isAvailable(): bool
    {
        return $this->active && $this->used_at === null;
    }
}
