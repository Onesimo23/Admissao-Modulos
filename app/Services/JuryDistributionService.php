<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Room;
use App\Models\Juri;
use App\Models\JuryDistribution;
use App\Models\Province; // Importando o modelo Province para verificar a província

class JuryDistributionService
{
    public function distribute()
    {
        // Obter todos os candidatos
        $candidates = Candidate::all();

        foreach ($candidates as $candidate) {
            // Obter o valor de 'local_exam', que é o número da província onde o candidato escolheu realizar o exame
            $provinceIdFromLocalExam = $candidate->local_exam;

            // Verificar se o valor de local_exam corresponde a uma província existente
            $provinceFromLocalExam = Province::find($provinceIdFromLocalExam);

            if (!$provinceFromLocalExam) {
                // Se não encontrar a província correspondente ao local_exam, ignorar o candidato
                continue;
            }

            // Filtrar as salas de exame que correspondem à província onde o candidato deseja fazer o exame
            $availableRooms = Room::whereHas('school', function ($query) use ($provinceFromLocalExam) {
                // A província da escola deve ser igual à província onde o candidato quer realizar o exame
                $query->where('province_id', $provinceFromLocalExam->id);
            })->get();

            // Verificar o curso do candidato e sua disciplina
            $course = $candidate->course; // Assumindo que um candidato pertence a um curso

            if (!$course || !$course->disciplina) {
                // Ignorar se o curso ou disciplina não existirem
                continue;
            }

            $disciplina = $course->disciplina; // Relacionamento 1:1

            foreach ($availableRooms as $room) {
                // Criar ou encontrar o júri para a sala e disciplina
                $jury = $this->findOrCreateJury($room, $disciplina);

                // Verificar a capacidade da sala e o número atual de candidatos
                $roomCapacity = $room->capacity;
                $currentCount = JuryDistribution::where('juri_id', $jury->id)->count();

                if ($currentCount < $roomCapacity) {
                    // Associar o candidato ao júri
                    JuryDistribution::create([
                        'juri_id' => $jury->id,
                        'candidate_id' => $candidate->id,
                        'disciplina_id' => $disciplina->id, // Associando a disciplina
                    ]);

                    // Interromper o loop para evitar associar o candidato a múltiplas salas
                    break;
                }
            }
        }
    }

    private function findOrCreateJury($room, $disciplina)
    {
        // Criar ou encontrar o júri para a sala e disciplina
        return Juri::firstOrCreate(
            ['room_id' => $room->id, 'disciplina_id' => $disciplina->id],
            [
                'name' => 'Júri para a Sala ' . $room->name . ' e Disciplina ' . $disciplina->name,
                'school_id' => $room->school_id,
            ]
        );
    }
}
