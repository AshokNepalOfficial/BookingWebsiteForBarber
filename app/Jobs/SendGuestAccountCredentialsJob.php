<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\GuestAccountCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendGuestAccountCredentialsJob implements ShouldQueue
{
    use Queueable;

    public $user;
    public $temporaryPassword;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $temporaryPassword)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->user->email)
                ->send(new GuestAccountCreated($this->user, $this->temporaryPassword));
        } catch (\Exception $e) {
            Log::error('Failed to send guest account credentials email', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
