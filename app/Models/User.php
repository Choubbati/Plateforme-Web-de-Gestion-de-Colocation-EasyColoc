<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'global_role',
        'is_banned',
        'banned_at',
        'reputation_score',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',

            'is_banned' => 'boolean',
            'banned_at' => 'datetime',
            'reputation_score' => 'integer',
        ];
    }

    /* ================= Relations ================= */

    // User ↔ Colocation (many-to-many via memberships pivot)
    public function colocations()
    {
        return $this->belongsToMany(Colocation::class, 'memberships')
            ->withPivot('role', 'joined_at', 'left_at')
            ->withTimestamps();
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function activeMembership()
    {
        return $this->hasOne(Membership::class)->whereNull('left_at');
    }

    public function expensesPaid()
    {
        return $this->hasMany(Expense::class, 'payer_id');
    }

    public function settlementsToPay()
    {
        return $this->hasMany(Settlement::class, 'from_user_id');
    }

    public function settlementsToReceive()
    {
        return $this->hasMany(Settlement::class, 'to_user_id');
    }

    public function isAdminGlobal(): bool
    {
        return $this->global_role === 'admin';
    }
}
