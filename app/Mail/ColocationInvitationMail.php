<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ColocationInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invitation $invitation,
        public string $link
    ) {}

    public function build()
    {
        return $this->subject('Invitation à rejoindre une colocation - EasyColoc')
            ->view('emails.invitations.colocation')
            ->with([
                'invitation' => $this->invitation,
                'link' => $this->link,
            ]);
    }
}
