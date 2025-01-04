<?php
namespace App\Console\Commands;

use App\Http\Controllers\InscriptionStatisticsController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class InscriptionStatisticsExcelCommand extends Command
{
    protected $signature = 'app:inscription-statistics-excel';
    protected $description = 'Enviar estatísticas de inscrições por e-mail em Excel';

    private function styleHeader($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '2563EB'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ]);
    }

    private function styleSubHeader($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '1E40AF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    private function stylePendingCell($sheet, $cell)
    {
        $sheet->getStyle($cell)->applyFromArray([
            'font' => [
                'color' => ['rgb' => 'D97706'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'FEF3C7'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
    }

    private function styleConfirmedCell($sheet, $cell)
    {
        $sheet->getStyle($cell)->applyFromArray([
            'font' => [
                'color' => ['rgb' => '059669'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'D1FAE5'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
    }

    public function handle()
    {
        $statisticsController = new InscriptionStatisticsController();
        $counts = $statisticsController->getCounts();
        $date = Carbon::now()->format('d/m/Y');

        $spreadsheet = new Spreadsheet();
        
        // Resumo Global
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Resumo Global');
        
        // Cabeçalho
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'Estatísticas de Inscrições - ' . $date);
        $this->styleHeader($sheet, 'A1:D1');
        
        // Métricas principais
        $sheet->setCellValue('A3', 'Total de Candidatos');
        $sheet->setCellValue('B3', $counts['all_candidates']);
        $sheet->setCellValue('C3', 'Guião de Pagamentos Gerados');
        $sheet->setCellValue('D3', $counts['all_payments']);
        
        $sheet->setCellValue('A4', 'Pagamentos Pendentes');
        $sheet->setCellValue('B4', $counts['pending_payments']);
        $this->stylePendingCell($sheet, 'B4');
        $sheet->setCellValue('C4', 'Pagamentos Confirmados');
        $sheet->setCellValue('D4', $counts['confirmed_payments']);
        $this->styleConfirmedCell($sheet, 'D4');

        // Ajustar largura das colunas
        foreach(range('A','D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Resumo por Universidade
        $sheet->setCellValue('A6', 'Resumo por Universidade');
        $this->styleSubHeader($sheet, 'A6:D6');
        
        $sheet->setCellValue('A7', 'Universidade');
        $sheet->setCellValue('B7', 'Total');
        $sheet->setCellValue('C7', 'Pendentes');
        $sheet->setCellValue('D7', 'Confirmados');
        $this->styleHeader($sheet, 'A7:D7');
        
        $row = 8;
        foreach ($counts['university_summary'] as $universityName => $stats) {
            $sheet->setCellValue('A'.$row, strtoupper($universityName));
            $sheet->setCellValue('B'.$row, $stats['total']);
            $sheet->setCellValue('C'.$row, $stats['pending']);
            $this->stylePendingCell($sheet, 'C'.$row);
            $sheet->setCellValue('D'.$row, $stats['confirmed']);
            $this->styleConfirmedCell($sheet, 'D'.$row);
            $row++;
        }

        // Estatísticas Detalhadas
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Estatísticas Detalhadas');
        
        $row = 1;
        foreach ($counts['detailed_statistics'] as $groupStats) {
            // Cabeçalho da Universidade
            $sheet->mergeCells("A{$row}:F{$row}");
            $sheet->setCellValue("A{$row}", 'UNIVERSIDADE: ' . strtoupper($groupStats['university']));
            $this->styleSubHeader($sheet, "A{$row}:F{$row}");
            $row++;

            // Cabeçalho do Regime
            $sheet->mergeCells("A{$row}:F{$row}");
            $sheet->setCellValue("A{$row}", 'REGIME: ' . strtoupper($groupStats['regime']));
            $this->styleHeader($sheet, "A{$row}:F{$row}");
            $row++;

            // Cabeçalhos das colunas
            $sheet->setCellValue("A{$row}", 'Curso');
            $sheet->setCellValue("B{$row}", 'Total');
            $sheet->setCellValue("C{$row}", 'Pendentes');
            $sheet->setCellValue("D{$row}", 'Confirmados');
            $this->styleHeader($sheet, "A{$row}:F{$row}");
            $row++;

            // Dados dos cursos
            foreach ($groupStats['courses'] as $course) {
                $sheet->setCellValue("A{$row}", strtoupper($course['course']));
                $sheet->setCellValue("B{$row}", $course['total']);
                $sheet->setCellValue("C{$row}", $course['pending']);
                $this->stylePendingCell($sheet, "C{$row}");
                $sheet->setCellValue("D{$row}", $course['confirmed']);
                $this->styleConfirmedCell($sheet, "D{$row}");
                $row++;
            }

            // Total do regime
            $sheet->setCellValue("A{$row}", 'TOTAL ' . strtoupper($groupStats['regime']));
            $sheet->setCellValue("B{$row}", $groupStats['total']);
            $sheet->setCellValue("C{$row}", $groupStats['pending']);
            $this->stylePendingCell($sheet, "C{$row}");
            $sheet->setCellValue("D{$row}", $groupStats['confirmed']);
            $this->styleConfirmedCell($sheet, "D{$row}");
            $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
            
            $row += 2; // Espaço entre grupos
        }

        foreach(range('A','F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Salvar e enviar
        $filename = 'estatisticas_inscricoes_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        $path = storage_path('app/public/' . $filename);
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

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
    }
}