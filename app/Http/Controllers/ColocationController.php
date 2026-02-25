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
        $isMember = $colocation->memberships()
            ->where('user_id', auth()->id())
            ->whereNull('left_at')
            ->exists();

        if (!$isMember) {
            abort(403);
        }

        $members = $colocation->members()
            ->withPivot('role', 'joined_at', 'left_at')
            ->orderBy('name')
            ->get();

        return view('colocations.show', [
            'colocation' => $colocation,
            'members' => $members,
        ]);
    }
}
