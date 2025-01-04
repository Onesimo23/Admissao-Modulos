<?php 

namespace App\Console\Commands;

use App\Http\Controllers\InscriptionStatisticsController;
use Illuminate\Console\Command;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class InscriptionStatisticsWordCommand extends Command 
{
    protected $signature = 'app:inscription-statistics-word';
    protected $description = 'Gerar relatório de estatísticas de inscrições em Word';

    private $phpWord;
    private $section;

    private function addTitle($text) 
    {
        $this->section->addText(
            $text,
            [
                'bold' => true,
                'size' => 16,
                'color' => '2563EB',
                'name' => 'Arial',
            ],
            ['alignment' => Jc::CENTER, 'spaceAfter' => 300]
        );
    }

    private function addSubTitle($text) 
    {
        $this->section->addText(
            $text,
            [
                'bold' => true,
                'size' => 14,
                'color' => '1E40AF',
                'name' => 'Arial',
            ],
            ['alignment' => Jc::LEFT, 'spaceAfter' => 200, 'spaceBefore' => 200]
        );
    }

    private function createStyledTable($rows, $cols) 
    {
        $table = $this->section->addTable([
            'borderSize' => 6,
            'borderColor' => 'E2E8F0',
            'alignment' => Jc::CENTER,
            'cellMargin' => 80,
        ]);

        return $table;
    }

    private function addTableHeader($table, $headers) 
    {
        $row = $table->addRow();
        foreach ($headers as $header) {
            $cell = $row->addCell(2500, ['bgColor' => '2563EB']);
            $cell->addText(
                $header,
                ['bold' => true, 'color' => 'FFFFFF', 'name' => 'Arial'],
                ['alignment' => Jc::CENTER]
            );
        }
    }

    private function addStatusCell($cell, $value, $isPending = true) 
    {
        $bgColor = $isPending ? 'FEF3C7' : 'D1FAE5';
        $textColor = $isPending ? 'D97706' : '059669';

        $cell->addText(
            $value,
            ['color' => $textColor, 'name' => 'Arial'],
            ['alignment' => Jc::CENTER]
        );
    }

    public function handle() 
    {
        $statisticsController = new InscriptionStatisticsController();
        $counts = $statisticsController->getCounts();
        $date = Carbon::now()->format('d/m/Y');

        $this->phpWord = new PhpWord();
        $this->section = $this->phpWord->addSection();

        // Título Principal
        $this->addTitle('Estatísticas de Inscrições');
        $this->addText("Data: " . $date, ['alignment' => Jc::CENTER]);
        $this->section->addTextBreak();

        // Resumo Global
        $this->addSubTitle('Resumo Global');
        $table = $this->createStyledTable(2, 4);

        // Primeira linha
        $row = $table->addRow();
        $row->addCell(3000)->addText('Total de Candidatos', ['bold' => true]);
        $row->addCell(2000)->addText($counts['all_candidates'], [], ['alignment' => Jc::CENTER]);
        $row->addCell(3000)->addText('Guião de Pagamentos Gerados', ['bold' => true]);
        $row->addCell(2000)->addText($counts['all_payments'], [], ['alignment' => Jc::CENTER]);

        // Segunda linha
        $row = $table->addRow();
        $cell1 = $row->addCell(3000);
        $cell1->addText('Pagamentos Pendentes', ['bold' => true]);
        $cell2 = $row->addCell(2000, ['bgColor' => 'FEF3C7']);
        $this->addStatusCell($cell2, $counts['pending_payments'], true);

        $cell3 = $row->addCell(3000);
        $cell3->addText('Pagamentos Confirmados', ['bold' => true]);
        $cell4 = $row->addCell(2000, ['bgColor' => 'D1FAE5']);
        $this->addStatusCell($cell4, $counts['confirmed_payments'], false);

        $this->section->addTextBreak();

        // Resumo por Universidade
        $this->addSubTitle('Resumo por Universidade');
        $table = $this->createStyledTable(count($counts['university_summary']) + 1, 4);
        $this->addTableHeader($table, ['Universidade', 'Total', 'Pendentes', 'Confirmados']);

        foreach ($counts['university_summary'] as $universityName => $stats) {
            $row = $table->addRow();
            $row->addCell(3000)->addText(strtoupper($universityName));
            $row->addCell(2000)->addText($stats['total'], [], ['alignment' => Jc::CENTER]);
            $cell = $row->addCell(2000, ['bgColor' => 'FEF3C7']);
            $this->addStatusCell($cell, $stats['pending'], true);
            $cell = $row->addCell(2000, ['bgColor' => 'D1FAE5']);
            $this->addStatusCell($cell, $stats['confirmed'], false);
        }

        $this->section->addTextBreak();

        // Estatísticas Detalhadas
        $this->addSubTitle('Estatísticas Detalhadas');
        foreach ($counts['detailed_statistics'] as $groupStats) {
            $this->section->addText(
                'UNIVERSIDADE: ' . strtoupper($groupStats['university']),
                ['bold' => true, 'size' => 12],
                ['bgColor' => '1E40AF', 'color' => 'FFFFFF']
            );

            $this->section->addText(
                'REGIME: ' . strtoupper($groupStats['regime']),
                ['bold' => true, 'size' => 11],
                ['bgColor' => '2563EB', 'color' => 'FFFFFF']
            );

            $table = $this->createStyledTable(count($groupStats['courses']) + 2, 4);
            $this->addTableHeader($table, ['Curso', 'Total', 'Pendentes', 'Confirmados']);

            foreach ($groupStats['courses'] as $course) {
                $row = $table->addRow();
                $row->addCell(3000)->addText(strtoupper($course['course']));
                $row->addCell(2000)->addText($course['total'], [], ['alignment' => Jc::CENTER]);
                $cell = $row->addCell(2000, ['bgColor' => 'FEF3C7']);
                $this->addStatusCell($cell, $course['pending'], true);
                $cell = $row->addCell(2000, ['bgColor' => 'D1FAE5']);
                $this->addStatusCell($cell, $course['confirmed'], false);
            }

            $row = $table->addRow();
            $row->addCell(3000)->addText('TOTAL ' . strtoupper($groupStats['regime']), ['bold' => true]);
            $row->addCell(2000)->addText($groupStats['total'], ['bold' => true], ['alignment' => Jc::CENTER]);
            $cell = $row->addCell(2000, ['bgColor' => 'FEF3C7']);
            $this->addStatusCell($cell, $groupStats['pending'], true);
            $cell = $row->addCell(2000, ['bgColor' => 'D1FAE5']);
            $this->addStatusCell($cell, $groupStats['confirmed'], false);

            $this->section->addTextBreak();
        }

        $this->section->addText(
            'Este é um relatório gerado automaticamente pelo sistema.',
            ['size' => 8, 'color' => '64748B'],
            ['alignment' => Jc::CENTER]
        );

        $filename = 'estatisticas_inscricoes_' . Carbon::now()->format('Y-m-d') . '.docx';
        $path = storage_path('app/public/' . $filename);
        $this->phpWord->save($path);
		
        // Enviar e-mail
        $emails = ['clnhancale@gmail.com'];
        $bccEmails = ['clnhancale@gmail.com'];

        foreach ($emails as $email) {
            Mail::send('emails.inscription-statistics-excel', ['date' => $date], function ($message) use ($email, $bccEmails, $path, $filename) {
                $message->to($email)
                    ->subject('Estatísticas de Inscrições')
                    ->attach($path, [
                        'as' => $filename,
                        'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ]);

                foreach ($bccEmails as $bccEmail) {
                    $message->bcc($bccEmail);
                }
            });
        }

        unlink($path);		
		$this->info('E-mails com estatísticas de inscrições enviados com sucesso!');
        //$this->info('Relatório Word gerado com sucesso em: ' . $path);
    }

    private function addText($text, $paragraphStyle = []) 
    {
        $this->section->addText(
            $text,
            ['size' => 11, 'name' => 'Arial'],
            $paragraphStyle
        );
    }
}
