<?php

namespace App\Console\Commands;

use App\Http\Controllers\InscriptionStatisticsController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class InscriptionStatisticsCommand extends Command
{
    protected $signature = 'app:inscription-statistics';
    protected $description = 'Enviar estatísticas de inscrições por e-mail';

    public function handle()
    {
        $statisticsController = new InscriptionStatisticsController();
        $counts = $statisticsController->getCounts();

        $emailData = [
            'counts' => $counts,
            'date' => Carbon::now()->format('d/m/Y'),
        ];

        $emails = ['clnhancale@gmail.com'];
        $bccEmails = ['clnhancale@gmail.com'];

        foreach ($emails as $email) {
            Mail::send('emails.inscription-statistics', $emailData, function ($message) use ($email, $bccEmails) {
                $message->to($email)
                    ->subject('Estatísticas de Inscrições');
                foreach ($bccEmails as $bccEmail) {
                    $message->bcc($bccEmail);
                }
            });
        }

        $this->info('E-mails com estatísticas de inscrições enviados com sucesso!');
    }
}