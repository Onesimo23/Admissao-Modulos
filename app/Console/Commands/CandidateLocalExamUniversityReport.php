<?php

namespace App\Console\Commands;

use App\Http\Controllers\InscriptionStatisticsController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class CandidateLocalExamUniversityReport extends Command
{
    protected $signature = 'report:candidates-by-local-exam-university';
    protected $description = 'Generate report of candidates by local exam and course';

    public function handle()
    {
        $statisticsController = new InscriptionStatisticsController();
        $report = $statisticsController->getCandidatesByLocalExamAndCourseAndUniversity();

        // Option 1: Send email
        $emailData = [
            'report' => $report,
            'date' => Carbon::now()->format('d/m/Y'),
        ];

        Mail::send('emails.candidates-local-exam-report', $emailData, function ($message) {
            $message->to('clnhancale@gmail.com')
                   ->subject('Relatório de Candidatos por Local de Exame');
        });

        // Option 2: Generate HTML file
        $html = View::make('emails.candidates-local-exam-university-report', $emailData)->render();
        file_put_contents(storage_path('app/candidatos_local_exame-university_' . date('Ymd') . '.html'), $html);

        $this->info('Relatório gerado com sucesso!');
    }
}