<?php

namespace App\Http\Controllers;

use App\Mail\ColocationInvitationMail;
use App\Models\Colocation;
use App\Models\Invitation;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    // Owner: create invitation
    public function store(Request $request, Colocation $colocation)
    {
        $isOwner = $colocation->memberships()
            ->where('user_id', auth()->id())
            ->whereNull('left_at')
            ->where('role', 'owner')
            ->exists();

        abort_unless($isOwner, 403);

        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $alreadyPending = $colocation->invitations()
            ->where('email', $data['email'])
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->exists();

        if ($alreadyPending) {
            return back()->with('error', 'Une invitation valide existe déjà pour cet email.');
        }

        $invitation = Invitation::create([
            'colocation_id' => $colocation->id,
            'email' => $data['email'],
            'token' => Str::random(48),
            'status' => 'pending',
            'expires_at' => now()->addDays(3),
        ]);

        $link = route('invitations.show', $invitation->token);

        Mail::to($invitation->email)->send(
            new ColocationInvitationMail($invitation, $link)
        );

        return back()->with('success', 'Invitation envoyée par email.');
    }

    // Invited user: open invitation page
    public function show(string $token)
    {
        $inv = Invitation::where('token', $token)->firstOrFail();

        // checks
        if ($inv->status !== 'pending' || $inv->expires_at->isPast()) {
            return view('invitations.invalid', ['message' => 'Invitation invalide ou expirée.']);
        }

        // email must match current user email
        if (auth()->user()->email !== $inv->email) {
            return view('invitations.invalid', ['message' => 'Cette invitation ne correspond pas à votre email.']);
        }

        // no active membership allowed
        if (auth()->user()->activeMembership()->exists()) {
            return view('invitations.invalid', ['message' => 'Vous avez déjà une colocation active.']);
        }

        return view('invitations.show', ['invitation' => $inv]);
    }

    public function accept(string $token)
    {
        $inv = Invitation::where('token', $token)->firstOrFail();

        if ($inv->status !== 'pending' || $inv->expires_at->isPast()) {
            return redirect()->route('dashboard')->with('error', 'Invitation invalide ou expirée.');
        }

        if (auth()->user()->email !== $inv->email) {
            return redirect()->route('dashboard')->with('error', 'Email non conforme à l’invitation.');
        }

        if (auth()->user()->activeMembership()->exists()) {
            return redirect()->route('dashboard')->with('error', 'Vous avez déjà une colocation active.');
        }

        // create membership
        Membership::create([
            'user_id' => auth()->id(),
            'colocation_id' => $inv->colocation_id,
            'role' => 'member',
            'joined_at' => now(),
            'left_at' => null,
        ]);

        $inv->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Invitation acceptée. Bienvenue !');
    }

    public function refuse(string $token)
    {
        $inv = Invitation::where('token', $token)->firstOrFail();

        if ($inv->status !== 'pending' || $inv->expires_at->isPast()) {
            return redirect()->route('dashboard')->with('error', 'Invitation invalide ou expirée.');
        }

        if (auth()->user()->email !== $inv->email) {
            return redirect()->route('dashboard')->with('error', 'Email non conforme à l’invitation.');
        }

        $inv->update([
            'status' => 'refused',
            'refused_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Invitation refusée.');
    }
}
