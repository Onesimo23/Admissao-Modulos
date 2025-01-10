<?php

namespace App\Console\Commands;

use App\Services\MonitorAssignmentService;
use Illuminate\Console\Command;

class AssignMonitorsToJuries extends Command
{
    protected $signature = 'juri:assign-monitors';
    protected $description = 'Atribui monitores disponíveis a júris nas mesmas províncias, garantindo as restrições';

    protected $monitorAssignmentService;

    public function __construct(MonitorAssignmentService $monitorAssignmentService)
    {
        parent::__construct();
        $this->monitorAssignmentService = $monitorAssignmentService;
    }

    public function handle()
    {
        try {
            $this->monitorAssignmentService->assignMonitorsToJuries();
            $this->info('Monitores foram atribuídos aos júris com sucesso.');
        } catch (\Exception $e) {
            $this->error('Erro: ' . $e->getMessage());
        }
    }
}
