<?php

namespace App\Livewire;

use App\Models\Candidate;
use App\Models\JuryDistribution as JuryDistributionModel;
use App\Models\Room;
use App\Models\School;
use Livewire\Component;
use App\Models\Course;
use Barryvdh\DomPDF\Facade\Pdf;
use TallStackUi\Traits\Interactions;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;

class JuryDistribution extends Component
{
    use Interactions;

    public function distributeJuries()
    {
        JuryDistributionModel::truncate(); // Limpa distribuições anteriores

        $candidates = Candidate::with(['course.courseexamSubjects'])
            ->where('regime_id', 1)
            ->whereHas('payments', fn($q) => $q->where('status', 1))
            ->get();

        foreach ($candidates as $candidate) {
            $examProvinceId = $candidate->local_exam;
            $examSubjects = $candidate->course->courseexamSubjects;

            $schools = School::where('province_id', $examProvinceId)->get();

            if ($examSubjects->count() < 2) continue;

            $schoolIndex = 0;
            $roomIndex = 0;

            foreach ($examSubjects as $subject) {
                $school = $schools[$schoolIndex] ?? null;

                if (!$school) break;

                $rooms = Room::where('school_id', $school->id)->get();

                if (!isset($rooms[$roomIndex])) break;

                $room = $rooms[$roomIndex];

                JuryDistributionModel::create([
                    'candidate_id' => $candidate->id,
                    'room_id' => $room->id,
                    'school_id' => $school->id,
                    'province_id' => $examProvinceId, // agora baseado no local de exame
                    'exam_subject_id' => $subject->id,
                ]);

                if ($room->capacity <= JuryDistributionModel::where('room_id', $room->id)->count()) {
                    $roomIndex++;
                }

                if ($roomIndex >= $rooms->count()) {
                    $schoolIndex++;
                    $roomIndex = 0;
                }
            }
        }

        $this->toast()->success('Sucesso', 'Júris distribuídos com sucesso!')->send();
    }


    public function distributeNewCandidates()
    {
        $candidates = Candidate::with(['course.courseexamSubjects'])
            ->where('regime_id', 1)
            ->whereHas('payments', fn($q) => $q->where('status', 1))
            ->whereDoesntHave('juryDistributions')
            ->get();

        foreach ($candidates as $candidate) {
            $examProvinceId = $candidate->local_exam;
            $examSubjects = $candidate->course->courseexamSubjects;

            if ($examSubjects->count() < 2) continue;

            foreach ($examSubjects as $subject) {
                $existingJuries = JuryDistributionModel::where('exam_subject_id', $subject->id)
                    ->where('province_id', $examProvinceId)
                    ->get()
                    ->groupBy('room_id');

                $roomAssigned = false;

                foreach ($existingJuries as $roomId => $juries) {
                    $room = Room::find($roomId);
                    if ($room && $juries->count() < $room->capacity) {
                        JuryDistributionModel::create([
                            'candidate_id' => $candidate->id,
                            'room_id' => $room->id,
                            'school_id' => $room->school_id,
                            'province_id' => $examProvinceId,
                            'exam_subject_id' => $subject->id,
                        ]);
                        $roomAssigned = true;
                        break;
                    }
                }

                if (!$roomAssigned) {
                    $schools = School::where('province_id', $examProvinceId)->get();
                    foreach ($schools as $school) {
                        foreach ($school->rooms as $room) {
                            $currentCount = JuryDistributionModel::where('room_id', $room->id)
                                ->where('exam_subject_id', $subject->id)
                                ->count();
                            if ($currentCount < $room->capacity) {
                                JuryDistributionModel::create([
                                    'candidate_id' => $candidate->id,
                                    'room_id' => $room->id,
                                    'school_id' => $school->id,
                                    'province_id' => $examProvinceId,
                                    'exam_subject_id' => $subject->id,
                                ]);
                                break 2;
                            }
                        }
                    }
                }
            }
        }

        $this->toast()->success('Sucesso', 'Novos candidatos distribuídos com sucesso!')->send();
    }


    public function resetAndDistributeJuries()
    {
        JuryDistributionModel::truncate();
        $this->distributeJuries();
        $this->toast()->success('Júris refeitos com sucesso!', 'Todos os júris foram recriados.')->send();
    }
    public function downloadPdf()
    {
        $juries = JuryDistributionModel::with(['candidate', 'room', 'school', 'province', 'examSubject'])
            ->get()
            ->sortBy(function ($jury) {
                $examDate = $jury->examSubject->exam_date ?? '9999-12-31';
                $startTime = $jury->examSubject->start_time ?? '23:59:59';
                return $examDate . ' ' . $startTime;
            })
            ->groupBy(function ($jury) {
                // Agrupar por disciplina e sala ao mesmo tempo (chave combinada)
                return $jury->exam_subject_id . '-' . $jury->room_id;
            });

        $pdf = Pdf::loadView('pdf.jury_distribution', compact('juries'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isPhpEnabled', true);

        return Response::streamDownload(
            fn() => print($pdf->output()),
            'jury_distribution.pdf'
        );
    }

    public function downloadWord()
    {
        $juries = JuryDistributionModel::with(['candidate', 'room', 'school', 'province', 'examSubject'])
            ->get()
            ->sortBy(function ($jury) {
                $examDate = $jury->examSubject->exam_date ?? '9999-12-31';
                $startTime = $jury->examSubject->start_time ?? '23:59:59';
                return $examDate . ' ' . $startTime;
            })
            ->groupBy(function ($jury) {
                return $jury->exam_subject_id . '-' . $jury->room_id;
            });
    
        $phpWord = new PhpWord();
    
        // Define o estilo da tabela com bordas
        $phpWord->addTableStyle('juryTableStyle', [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
        ], [
            'align' => 'center',
        ]);
    
        $section = $phpWord->addSection();
    
        foreach ($juries as $groupKey => $juryGroup) {
            $first = $juryGroup->first();
            $examSubject = $first->examSubject;
            $room = $first->room;
            $school = $first->school;
            $province = $first->province;
    
            // Adiciona o logotipo
            $section->addImage(public_path('up.png'), [
                'width' => 50,
                'height' => 50,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]);
    
            $section->addText("Universidade Save", ['bold' => true, 'size' => 16], ['alignment' => 'center']);
            $section->addText("Exames de Admissão de " . date('Y'), ['bold' => true, 'size' => 14], ['alignment' => 'center']);
            $section->addText("Lista de Candidatos por Sala de Exame", ['bold' => true, 'size' => 12], ['alignment' => 'center']);
            $section->addTextBreak(1);
    
            $section->addText("DISCIPLINA: {$examSubject->name}");
            $section->addText("DATA: {$examSubject->exam_date}");
            $section->addText("HORA: {$examSubject->start_time}");
            $section->addText("H. ENTRADA: {$examSubject->arrival_time}");
            $section->addText("PROVÍNCIA: {$province->name}");
            $section->addText("SALA: {$room->name}");
            $section->addText("LOCAL: {$school->name}");
            $section->addTextBreak(1);
    
            // Tabela com bordas
            $table = $section->addTable('juryTableStyle');
    
            $table->addRow();
            $table->addCell(3000)->addText('Nrº de Candidato', ['bold' => true]);
            $table->addCell(6000)->addText('Nome do Candidato', ['bold' => true]);
            $table->addCell(4000)->addText('Assinatura do Candidato', ['bold' => true]);
    
            foreach ($juryGroup as $jury) {
                $table->addRow();
                $table->addCell()->addText($jury->candidate->id);
                $table->addCell()->addText($jury->candidate->surname . ' ' . $jury->candidate->name);
                $table->addCell()->addText('');
            }
    
            $section->addPageBreak();
        }
    
        $filename = 'jury_distribution.docx';
        $tempFile = storage_path("app/public/{$filename}");
        IOFactory::createWriter($phpWord, 'Word2007')->save($tempFile);
    
        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
    
    

    public function render()
    {
        return view('livewire.jury-distribution')->layout('layouts.admin');
    }
}
