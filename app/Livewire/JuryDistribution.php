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

class JuryDistribution extends Component
{
    use Interactions;

    public function distributeJuries()
    {
        JuryDistributionModel::truncate(); // Limpa distribuições anteriores

        // Filtra candidatos com regime_id = 1 (regime laboral)
        $candidates = Candidate::with(['province', 'course.courseexamSubjects'])
            ->where('regime_id', 1)
            ->get();

        foreach ($candidates as $candidate) {
            $provinceId = $candidate->province_id;
            $course = $candidate->course;

            // Pega as disciplinas (exames) que o curso do candidato exige
            $examSubjects = $course->courseexamSubjects;

            // Obter escolas e salas da província do candidato
            $schools = School::where('province_id', $provinceId)->get();

            if ($examSubjects->count() < 2) {
                // Se não houver duas disciplinas, pula o candidato
                continue;
            }

            $schoolIndex = 0;
            $roomIndex = 0;

            foreach ($examSubjects as $subject) {
                // Log::info("Distribuindo: Candidato {$candidate->id}, Disciplina {$subject->id}");            

                $school = $schools[$schoolIndex] ?? null;

                if (!$school) break;

                $rooms = Room::where('school_id', $school->id)->get();

                if (!isset($rooms[$roomIndex])) break;

                $room = $rooms[$roomIndex];

                JuryDistributionModel::create([
                    'candidate_id' => $candidate->id,
                    'room_id' => $room->id,
                    'school_id' => $school->id,
                    'province_id' => $provinceId,
                    'exam_subject_id' => $subject->id,
                    
                ]);
                // Log::info("Distribuição criada para candidato {$candidate->id} e disciplina {$subject->id}");

                // Verifica capacidade da sala
                if ($room->capacity <= JuryDistributionModel::where('room_id', $room->id)->count()) {
                    $roomIndex++;
                }

                // Se acabaram as salas da escola atual, vai para próxima
                if ($roomIndex >= $rooms->count()) {
                    $schoolIndex++;
                    $roomIndex = 0;
                }
            }
        }

        $this->toast()->success('Sucesso', 'Júris distribuídos com sucesso!')->send();
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
    


    public function render()
    {
        return view('livewire.jury-distribution');
    }
}
