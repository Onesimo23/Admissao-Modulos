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
    private $roomSchedules = [];
    private $roomOccupancy = [];

    public function distribute()
    {
        // Desabilitar temporariamente as verificações de chave estrangeira
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Limpar as tabelas 'juris' e 'jury_distributions' antes de realizar a distribuição
        Juri::truncate();
        JuryDistribution::truncate();
        
        // Reabilitar as verificações de chave estrangeira
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Inicializar arrays de controle
        $this->roomSchedules = [];
        $this->roomOccupancy = [];

        // Filtrar candidatos com status = 1 e regime_id = 1
        $candidates = Candidate::where('status', 1)
                               ->where('regime_id', 1)
                               ->get()
                               ->groupBy('local_exam');

        foreach ($candidates as $provinceId => $provinceCandidates) {
            $province = Province::find($provinceId);
            if (!$province) continue;

            // Obter salas disponíveis na província
            $availableRooms = Room::whereHas('school', function ($query) use ($provinceId) {
                $query->where('province_id', $provinceId);
            })->get();

            if ($availableRooms->isEmpty()) continue;

            // Inicializar controle para cada sala
            foreach ($availableRooms as $room) {
                $this->roomSchedules[$room->id] = [];
                $this->roomOccupancy[$room->id] = [];
            }

            // Agrupar candidatos por disciplina
            $candidatesByDiscipline = $this->groupCandidatesByDiscipline($provinceCandidates);

            // Distribuir candidatos por disciplina
            foreach ($candidatesByDiscipline as $disciplineName => $disciplineCandidates) {
                $sortedCandidates = $this->sortCandidatesByPriority($disciplineCandidates, $disciplineName);
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
            $isDisciplina1 = $candidateData['is_disciplina1'];
            $examTime = $isDisciplina1 ? $disciplina->horario_disciplina1 : $disciplina->horario_disciplina2;
            
            // Verificar se precisa criar novo júri ou mudar de sala
            if (!$currentJury || $this->needsNewJury($currentRoom, $examTime)) {
                // Procurar uma sala disponível que não tenha conflito de horário e capacidade
                $availableRoom = $this->findAvailableRoom($rooms, $disciplina, $isDisciplina1);
                
                if (!$availableRoom) {
                    // Não há salas disponíveis
                    break;
                }
                
                $currentRoom = $availableRoom;
                $currentJury = $this->createJury($currentRoom, $disciplineName, $disciplina->id);
                
                // Registrar o horário e inicializar ocupação
                $this->roomSchedules[$currentRoom->id][] = [
                    'disciplina_id' => $disciplina->id,
                    'exam_time' => $examTime,
                    'jury_id' => $currentJury->id
                ];

                if (!isset($this->roomOccupancy[$currentRoom->id][$examTime])) {
                    $this->roomOccupancy[$currentRoom->id][$examTime] = 0;
                }
            }

            // Verificar se ainda há espaço na sala para este horário
            if ($this->roomOccupancy[$currentRoom->id][$examTime] >= $currentRoom->capacity) {
                // Procurar nova sala
                $availableRoom = $this->findAvailableRoom($rooms, $disciplina, $isDisciplina1);
                if (!$availableRoom) {
                    break;
                }
                $currentRoom = $availableRoom;
                $currentJury = $this->createJury($currentRoom, $disciplineName, $disciplina->id);
                
                // Registrar novo horário e inicializar ocupação
                $this->roomSchedules[$currentRoom->id][] = [
                    'disciplina_id' => $disciplina->id,
                    'exam_time' => $examTime,
                    'jury_id' => $currentJury->id
                ];
                $this->roomOccupancy[$currentRoom->id][$examTime] = 0;
            }

            // Criar distribuição do júri e atualizar ocupação
            JuryDistribution::create([
                'juri_id' => $currentJury->id,
                'candidate_id' => $candidate->id,
                'disciplina_id' => $disciplina->id
            ]);

            $this->roomOccupancy[$currentRoom->id][$examTime]++;
        }
    }

    private function needsNewJury($room, $examTime)
    {
        // Verifica se a sala já atingiu sua capacidade para este horário
        return isset($this->roomOccupancy[$room->id][$examTime]) && 
               $this->roomOccupancy[$room->id][$examTime] >= $room->capacity;
    }

    private function findAvailableRoom($rooms, $disciplina, $isDisciplina1)
    {
        $examTime = $isDisciplina1 ? $disciplina->horario_disciplina1 : $disciplina->horario_disciplina2;

        foreach ($rooms as $room) {
            // Verificar se a sala tem conflito de horário
            if ($this->hasTimeConflict($room, $examTime)) {
                continue;
            }

            // Verificar se a sala tem capacidade disponível
            if (!isset($this->roomOccupancy[$room->id][$examTime]) || 
                $this->roomOccupancy[$room->id][$examTime] < $room->capacity) {
                return $room;
            }
        }
        return null;
    }

    private function hasTimeConflict($room, $examTime)
    {
        if (!isset($this->roomSchedules[$room->id])) {
            return false;
        }

        foreach ($this->roomSchedules[$room->id] as $schedule) {
            if (date('Y-m-d H:i', strtotime($schedule['exam_time'])) === 
                date('Y-m-d H:i', strtotime($examTime))) {
                return true;
            }
        }

        return false;
    }

    private function createJury($room, $disciplineName, $disciplinaId)
    {
        return Juri::create([
            'room_id' => $room->id,
            'disciplina_id' => $disciplinaId,
            'name' => "Júri {$disciplineName} - {$room->name}",
            'school_id' => $room->school_id
        ]);
    }
}