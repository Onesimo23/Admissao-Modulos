<div class="p-4 bg-white shadow rounded-lg">
    <h2 class="text-xl font-bold mb-4">Distribuição de Júris</h2>
    
    <div class="flex space-x-4">
        <button wire:click="resetAndDistributeJuries" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
            Refazer Júris (Zerar e Criar Novamente)
        </button>
        
        <button wire:click="distributeNewCandidates" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Adicionar Novos Candidatos
        </button>
        
        <button wire:click="downloadPdf" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            Baixar Júris em PDF
        </button>
    </div>
</div>
