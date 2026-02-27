<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function store(Request $request , Colocation $colocation){
        $isMember = $colocation->memberships()
            ->where('user_id',auth()->id())
            ->whereNull('left_at')
            ->exists();

        abort_unless($isMember,403);

        Expense::create([
            'title'=>$request->title,
            'amount' => $request->amount,
            'expense_date' => $request->expense_date,
            'category_id' => $request->category_id,
            'payer_id' => auth()->id(),
            'colocation_id' => $colocation->id,
        ]);

        return back()->with('success', 'Dépense ajoutée avec succès.');


    }
}
