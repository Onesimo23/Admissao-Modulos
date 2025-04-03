<?php

namespace App\Livewire;

use App\Models\Candidate;
use App\Models\JuryDistribution as JuryDistributionModel;
use App\Models\Room;
use App\Models\School;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use TallStackUi\Traits\Interactions;
use Illuminate\Support\Facades\Response;

class JuryDistribution extends Component
{
    use Interactions;

    public function distributeJuries()
    {
        JuryDistributionModel::truncate(); // Limpa distribuições anteriores

        $candidates = Candidate::with(['province', 'course'])->get();
        $groupedByProvince = $candidates->groupBy('province_id');

        foreach ($groupedByProvince as $provinceId => $candidatesInProvince) {
            $groupedByDiscipline = $candidatesInProvince->groupBy('course.discipline');

            foreach ($groupedByDiscipline as $discipline => $candidatesInDiscipline) {
                $schools = School::where('province_id', $provinceId)->get();

                foreach ($schools as $school) {
                    $rooms = Room::where('school_id', $school->id)->get();
                    $roomIndex = 0;

                    foreach ($candidatesInDiscipline as $candidate) {
                        if (!isset($rooms[$roomIndex])) {
                            break;
                        }

                        JuryDistributionModel::create([
                            'candidate_id' => $candidate->id,
                            'room_id' => $rooms[$roomIndex]->id,
                            'school_id' => $school->id,
                            'province_id' => $provinceId,
                            'discipline' => $discipline
                        ]);

                        if ($rooms[$roomIndex]->capacity <= JuryDistributionModel::where('room_id', $rooms[$roomIndex]->id)->count()) {
                            $roomIndex++;
                        }
                    }
                }
            }
        }

        $this->toast()->success('Sucesso', 'Júris distribuídos com sucesso!')->send();
    }

    public function downloadPdf()
    {
        $juries = JuryDistributionModel::with(['candidate', 'room', 'school', 'province'])->get(); // Corrigido para usar o modelo correto

        $pdf = Pdf::loadView('pdf.jury_distribution', compact('juries'));

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
