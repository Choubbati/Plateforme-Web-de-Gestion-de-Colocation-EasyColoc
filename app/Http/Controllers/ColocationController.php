<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Membership;
use Illuminate\Http\Request;

class ColocationController extends Controller
{
    public function create()
    {
        if (auth()->user()->activeMembership()->exists()) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous avez déjà une colocations active.');
        }

        return view('colocations.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->activeMembership()->exists()) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous avez déjà une colocations active.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $colocation = Colocation::create([
            'name' => $data['name'],
            'status' => 'active',
            'owner_id' => auth()->id(),
        ]);

        Membership::create([
            'user_id' => auth()->id(),
            'colocation_id' => $colocation->id,
            'role' => 'owner',
            'joined_at' => now(),
            'left_at' => null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Colocation créée avec succès.');
    }



    public function show(Colocation $colocation)
    {
        // load العلاقات اللي محتاجين
        $colocation->load(['activeMembers', 'categories', 'memberships']);

        // membership ديال المستخدم الحالي
        $myMembership = $colocation->memberships
            ->where('user_id', auth()->id())
            ->whereNull('left_at')
            ->first();

        // حماية: غير active member يشوف
        abort_unless($myMembership, 403);

        // expenses ديال هاد colocation (نحتاج payer_id)
        $expenses = \App\Models\Expense::where('colocation_id', $colocation->id)->get();

        $activeMembers = $colocation->activeMembers;

        $membersCount = max(1, $activeMembers->count()); // احتياط
        $totalSpent = $expenses->sum('amount');
        $share = $membersCount ? ($totalSpent / $membersCount) : 0;

        // حساب paid و balance لكل member
        $balances = $activeMembers->map(function ($member) use ($expenses, $share) {
            $paid = $expenses->where('payer_id', $member->id)->sum('amount');
            $balance = $paid - $share;

            return [
                'user' => $member,
                'paid' => round($paid, 2),
                'share' => round($share, 2),
                'balance' => round($balance, 2),
            ];
        });

        return view('colocations.show', [
            'colocation' => $colocation,
            'myMembership' => $myMembership,
            'expenses' => $expenses,
            'balances' => $balances,
            'totalSpent' => round($totalSpent, 2),
            'share' => round($share, 2),
        ]);
    }
}
