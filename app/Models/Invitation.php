<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'colocation_id',
        'email',
        'token',
        'status',
        'expires_at',
        'accepted_at',
        'refused_at',
    ];

    protected $dates = [
        'expires_at',
        'accepted_at',
        'refused_at',
    ];

    public function colocation(){
        return $this->belongsTo(Colocation::class);
    }
}
