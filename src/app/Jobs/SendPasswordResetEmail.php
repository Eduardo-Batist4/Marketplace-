<?php

namespace App\Jobs;

use App\Mail\PasswordResetEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPasswordResetEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $passwordReset;
    /**
     * Create a new job instance.
     */
    public function __construct($email, $passwordReset)
    {
        $this->email = $email;
        $this->passwordReset = $passwordReset;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new PasswordResetEmail($this->passwordReset));
    }
}
