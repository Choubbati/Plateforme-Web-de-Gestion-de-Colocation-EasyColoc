<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'global_role',
        'is_banned',
        'banned_at',
        'reputation_score',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function colocation(){
        return $this->belongsToMany(Colocation::class,'memberships')->withPivot('role','joined_at','left_at')->withTimestamps();
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function activeMembership(){
        return $this->hasOne(Membership::class)->whereNull('left_at');
    }

    public function expensePaid()
    {
        return $this->hasMany(Expense::class,'payer_id');
    }

    public function settlementsToPay()
    {
        return $this->hasMany(Settlement::class,'from_user_id');
    }

    public function settlementToReceive(){
        return $this->hasMany(Settlement::class,'to_user_id');
    }

}
