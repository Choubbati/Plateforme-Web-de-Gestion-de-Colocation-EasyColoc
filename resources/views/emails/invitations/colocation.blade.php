<p>Bonjour,</p>

<p>
    Vous avez été invité à rejoindre la colocation
    <strong>{{ $invitation->colocation->name }}</strong>.
</p>

<p>
    Cliquez ici pour accepter l’invitation :
    <a href="{{ $link }}">{{ $link }}</a>
</p>

<p>
    Cette invitation expire le :
    <strong>{{ $invitation->expires_at->format('Y-m-d H:i') }}</strong>
</p>

<p>Merci,<br>EasyColoc</p>
