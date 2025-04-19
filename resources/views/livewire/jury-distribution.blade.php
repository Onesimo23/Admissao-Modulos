<div class="p-4 bg-white shadow rounded-lg">
    <h2 class="text-xl font-bold mb-4">Distribuição de Júris</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Botão para Refazer Júris -->
        <button wire:click="resetAndDistributeJuries" class="flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition duration-200">
            <i class="fas fa-sync-alt mr-2"></i>
            <span>Refazer Júris</span>
        </button>

        <!-- Botão para Adicionar Novos Candidatos -->
        <button wire:click="distributeNewCandidates" class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition duration-200">
            <i class="fas fa-user-plus mr-2"></i>
            <span>Adicionar Novos Candidatos</span>
        </button>

        <!-- Botão para Baixar Júris -->
        <div class="relative">
            <button id="downloadButton" class="flex items-center justify-center w-full px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition duration-200">
                <i class="fas fa-download mr-2"></i>
                <span>Baixar Júris</span>
            </button>
            <!-- Dropdown para escolher entre PDF ou Word -->
            <div id="downloadOptions" class="absolute hidden bg-white shadow-lg rounded-lg mt-2 w-full z-10">
                <button wire:click="downloadPdf" class="flex items-center px-4 py-2 hover:bg-gray-100 w-full text-left">
                    <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                    <span>Baixar em PDF</span>
                </button>
                <button wire:click="downloadWord" class="flex items-center px-4 py-2 hover:bg-gray-100 w-full text-left">
                    <i class="fas fa-file-word text-blue-600 mr-2"></i>
                    <span>Baixar em Word</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const downloadButton = document.getElementById('downloadButton');
        const downloadOptions = document.getElementById('downloadOptions');

        downloadButton.addEventListener('click', function() {
            // Alternar a visibilidade do dropdown
            downloadOptions.classList.toggle('hidden');
        });

        // Fechar o dropdown ao clicar fora
        document.addEventListener('click', function(event) {
            if (!downloadButton.contains(event.target) && !downloadOptions.contains(event.target)) {
                downloadOptions.classList.add('hidden');
            }
        });
    });
</script>