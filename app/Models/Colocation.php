<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;


class Colocation extends Model
{
    protected $fillable = [
        'name','status','owner_id','cancelled_at'
    ];

    public function owner(){
        return $this->belongsTo(User::class,'owner_id');
    }

    public function members(){
        return $this->belongsToMany(User::class,'memberships')
            ->withPivot('role','joined_at','left_at')
            ->withTimestamps();
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function activeMembers()
    {
        return $this->belongsToMany(User::class, 'memberships')
            ->wherePivotNull('left_at')
            ->withPivot('role', 'joined_at', 'left_at')
            ->withTimestamps();
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'colocation_id');
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
