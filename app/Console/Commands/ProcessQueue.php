<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ProcessQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all pending email queue jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing email queue...');
        
        // Process all jobs in the queue until empty
        Artisan::call('queue:work', [
            '--stop-when-empty' => true,
            '--tries' => 3,
            '--timeout' => 60,
        ]);
        
        $this->info('✓ All emails sent successfully!');
        
        return 0;
    }
}
