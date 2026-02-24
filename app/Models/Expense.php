<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'title',
        'amount',
        'expense_date',
        'payer_id',
        'category_id',
        'colocation_id',
    ];

    protected $casts= [
        'expense_date'=> 'date',
    ];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);

    }


    public function payer()
    {
        return $this->belongsTo(User::class,'payer_id');
    }


}
