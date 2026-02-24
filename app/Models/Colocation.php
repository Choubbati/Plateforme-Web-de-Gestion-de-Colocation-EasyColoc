<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = [
        'name','status','owner_id','cancelled_at'
    ];

    public function owner(){
        return $this->belongsTo(User::class,'owner_id');
    }

    public function members(){
        return $this->belongsToMany(User::class,'membership')
            ->withPivot('role','joined_at','left_at')
            ->withTimestamps();
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function categories()
    {
        return $this->hasMany(Colocation::class);
    }

    public function expense()
    {
        return $this->hasMany(Expense::class);
    }

    public function settlement()
    {
        return $this->hasMany(Settlement::class);
    }
}
