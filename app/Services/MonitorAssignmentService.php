<?php

namespace App\Services;

use App\Models\Juri;
use App\Models\Monitor;
use Illuminate\Support\Facades\DB;

class MonitorAssignmentService
{
    public function assignMonitorsToJuries()
    {
        // Desabilitar temporariamente as verificações de chave estrangeira
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Obter todos os júris que ainda não têm 2 monitores
        $juris = Juri::with('monitors')->get()->filter(function ($jury) {
            return $jury->monitors->count() < 2;
        });

        // Obter monitores disponíveis (status verdadeiro e sem júris atribuídos)
        $monitors = Monitor::where('status', true)
                           ->whereDoesntHave('juris')  // Garantir que o monitor não tenha júri
                           ->get();

        // Reabilitar as verificações de chave estrangeira
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        foreach ($juris as $jury) {
            $neededMonitors = 2 - $jury->monitors->count(); // Quantos monitores faltam para o júri

            // Obter monitores disponíveis na mesma província do júri
            $provinceMonitors = $monitors->filter(function ($monitor) use ($jury) {
                return $monitor->province_id === $jury->school->province_id;
            })->take($neededMonitors);  // Limitar à quantidade necessária

            if ($provinceMonitors->isEmpty()) {
                continue; // Pula se não houver monitores disponíveis na mesma província
            }

            // Atribuir os monitores ao júri
            foreach ($provinceMonitors as $monitor) {
                // Verifica se o monitor já foi atribuído a outro júri
                $jury->monitors()->attach($monitor->id); // Associar o monitor ao júri

                // Remover o monitor da lista de disponíveis
                $monitors = $monitors->filter(fn($m) => $m->id !== $monitor->id);
            }
        }
    }
}
