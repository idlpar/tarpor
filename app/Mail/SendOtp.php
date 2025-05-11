<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtp extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $type;

    public function __construct($otp, $type = 'login')
    {
        $this->otp = $otp;
        $this->type = $type;
    }

    public function build()
    {
        $subject = $this->type === 'password-reset' ? 'Password Reset OTP' : 'Account Verification OTP';
        return $this->subject($subject)
            ->view('emails.otp')
            ->with(['otp' => $this->otp]);
    }
}
