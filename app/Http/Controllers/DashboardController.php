<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $memberships = auth()->user()->activeMembership()->with('colocation')->first();
        return view('dashboard', [
            'membership' => $memberships,
            'colocation' => $memberships?->colocation,
        ]);
    }
}
