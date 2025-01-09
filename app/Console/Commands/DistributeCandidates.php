<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\JuryDistributionService;

class DistributeCandidates extends Command
{
    // Definindo a assinatura e a descrição do comando
    protected $signature = 'juri:distribute';
    protected $description = 'Distribui os candidatos automaticamente para os júris';

    // Injeção de dependência do serviço
    protected $juryDistributionService;

    // Construtor com a injeção do serviço
    public function __construct(JuryDistributionService $juryDistributionService)
    {
        parent::__construct();
        
        // Atribuindo o serviço à variável
        $this->juryDistributionService = $juryDistributionService;
    }

    // Método handle para executar a lógica do comando
    public function handle()
    {
        // Chama o serviço para distribuir os candidatos
        $this->juryDistributionService->distribute();

        // Retorna uma mensagem informando o sucesso
        $this->info('Candidatos distribuídos com sucesso!');
    }
}
