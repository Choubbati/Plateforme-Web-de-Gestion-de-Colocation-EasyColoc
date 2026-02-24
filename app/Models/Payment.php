<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'settlement_id',
        'payer_id',
        'receiver_id',
        'amount',
        'paid_at',
    ];

    protected $dates = ['paid_at'];

    public function settlement()
    {
        return $this->belongsTo(Settlement::class);
    }

    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
