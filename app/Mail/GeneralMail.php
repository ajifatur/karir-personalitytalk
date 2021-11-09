<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class GeneralMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * int id pelamar
     * @return void
     */
    public function __construct($id)
    {
        $this->user = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Get data user
        $user = User::find($this->user);
        $token = md5($user->username);

        return $this->from('administrator@psikologanda.com')->markdown('email/general')->subject('Notifikasi')->with([
            'user' => $user,
            'token' => $token,
        ]);
    }
}
