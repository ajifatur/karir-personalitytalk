<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Lowongan;
use App\Models\Pelamar;
use App\Models\User;

class ApplicantMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * int id
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Get the applicant
        $applicant = User::find($this->id);

        return $this->from('administrator@psikologanda.com')->markdown('email/applicant')->subject('Notifikasi')->with([
            'applicant' => $applicant,
        ]);
    }
}
