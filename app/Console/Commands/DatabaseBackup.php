<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Mail\DatabaseBackupMail; // Importe o Mailable

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
	 
	$filename = "backup-" . Carbon::now()->format('Y-m-d-H-i-s') . ".sql";
        $path = storage_path('app/db_backup/');
		
		// Ensure the backup directory exists
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
		
	$file = storage_path('app/db_backup/' . $filename);

        $command = "mysqldump --user=" . env('DB_USERNAME') .
                   " --password=" . env('DB_PASSWORD') .
                   " --host=" . env('DB_HOST') .
                   " " . env('DB_DATABASE') .
                   " > " . $file;

        $returnVar = NULL;
        $output = NULL;

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->error("Failed to backup database.");
            return 1;
        }

        $this->info("Database backup saved to: {$file}");
        
        
        // Send email with the backup file attached using the Mailable
        $recipients = ['clnhancale@gmail.com'];
        Mail::to($recipients)->send(new DatabaseBackupMail($file, $filename));

        $this->info('Database backup sent to ' . implode(', ', $recipients));

        
        return 0;
	
    }
}
