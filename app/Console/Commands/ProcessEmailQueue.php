<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ProcessEmailQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:process-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process email queue jobs (for shared hosting without queue worker)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing email queue...');
        
        // Process all pending jobs in the queue
        // This runs once and exits (perfect for cron)
        Artisan::call('queue:work', [
            '--stop-when-empty' => true,  // Stop when no jobs left
            '--tries' => 3,                // Retry failed jobs 3 times
            '--timeout' => 60,             // 60 second timeout per job
        ]);
        
        $this->info('Email queue processed successfully!');
        
        return 0;
    }
}
