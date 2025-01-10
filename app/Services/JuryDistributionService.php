<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Room;
use App\Models\Juri;
use App\Models\JuryDistribution;
use App\Models\Province;
use App\Models\Disciplina;
use Illuminate\Support\Facades\DB;

class JuryDistributionService
{
    public function distribute()
    {
        // Desabilitar temporariamente as verificações de chave estrangeira
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Limpar as tabelas 'juris' e 'jury_distributions' antes de realizar a distribuição
        Juri::truncate();
        JuryDistribution::truncate();
        
        // Reabilitar as verificações de chave estrangeira
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Filtrar candidatos com status = 1 e regime_id = 1
        $candidates = Candidate::where('status', 1)
                               ->where('regime_id', 1)
                               ->get()
                               ->groupBy('local_exam'); // Agrupar por província

        foreach ($candidates as $provinceId => $provinceCandidates) {
            $province = Province::find($provinceId);
            if (!$province) continue;

            // Obter salas disponíveis na província
            $availableRooms = Room::whereHas('school', function ($query) use ($provinceId) {
                $query->where('province_id', $provinceId);
            })->get();

            if ($availableRooms->isEmpty()) continue;

            // Agrupar candidatos por disciplina
            $candidatesByDiscipline = $this->groupCandidatesByDiscipline($provinceCandidates);

            // Distribuir candidatos por disciplina
            foreach ($candidatesByDiscipline as $disciplineName => $disciplineCandidates) {
                // Ordenar candidatos por peso da disciplina (prioridade)
                $sortedCandidates = $this->sortCandidatesByPriority($disciplineCandidates, $disciplineName);
                
                // Distribuir candidatos nas salas
                $this->distributeCandidatesToRooms($sortedCandidates, $availableRooms, $disciplineName);
            }
        }
    }

    private function groupCandidatesByDiscipline($candidates)
    {
        $groupedCandidates = [];

        foreach ($candidates as $candidate) {
            $disciplinas = Disciplina::where('course_id', $candidate->course_id)->first();
            if (!$disciplinas) continue;

            // Adicionar candidato ao grupo da disciplina1
            $groupedCandidates[$disciplinas->disciplina1][] = [
                'candidate' => $candidate,
                'disciplina' => $disciplinas,
                'is_disciplina1' => true
            ];

            // Adicionar candidato ao grupo da disciplina2
            $groupedCandidates[$disciplinas->disciplina2][] = [
                'candidate' => $candidate,
                'disciplina' => $disciplinas,
                'is_disciplina1' => false
            ];
        }

        return $groupedCandidates;
    }

    private function sortCandidatesByPriority($candidates, $disciplineName)
    {
        return collect($candidates)->sort(function ($a, $b) {
            $weightA = $a['is_disciplina1'] ? $a['disciplina']->peso1 : $a['disciplina']->peso2;
            $weightB = $b['is_disciplina1'] ? $b['disciplina']->peso1 : $b['disciplina']->peso2;
            
            // Ordenar por peso (1 tem prioridade sobre 2)
            return $weightA - $weightB;
        })->values()->all();
    }

    private function distributeCandidatesToRooms($candidates, $rooms, $disciplineName)
    {
        $currentRoomIndex = 0;
        $currentRoom = $rooms[$currentRoomIndex];
        $currentJury = null;

        foreach ($candidates as $candidateData) {
            $candidate = $candidateData['candidate'];
            $disciplina = $candidateData['disciplina'];
            
            // Se não houver júri atual ou o júri atual estiver cheio, criar novo júri
            if (!$currentJury || $this->isJuryFull($currentJury, $currentRoom)) {
                // Se a sala atual estiver cheia, passar para próxima sala
                if ($this->isJuryFull($currentJury, $currentRoom)) {
                    $currentRoomIndex++;
                    if ($currentRoomIndex >= count($rooms)) {
                        // Não há mais salas disponíveis
                        break;
                    }
                    $currentRoom = $rooms[$currentRoomIndex];
                }

                $currentJury = $this->createJury($currentRoom, $disciplineName, $disciplina->id);
            }

            // Criar distribuição do júri
            JuryDistribution::create([
                'juri_id' => $currentJury->id,
                'candidate_id' => $candidate->id,
                'disciplina_id' => $disciplina->id
            ]);
        }
    }

    private function isJuryFull($jury, $room)
    {
        if (!$jury) return false;
        
        $currentCount = JuryDistribution::where('juri_id', $jury->id)->count();
        return $currentCount >= $room->capacity;
    }

    private function createJury($room, $disciplineName, $disciplinaId)
    {
        return Juri::create([
            'room_id' => $room->id,
            'disciplina_id' => $disciplinaId,
            'name' => "Júri {$disciplineName} - Sala {$room->name}",
            'school_id' => $room->school_id
        ]);
    }
}
