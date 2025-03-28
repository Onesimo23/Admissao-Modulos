<td class="py-2 px-4 flex space-x-2">
    <!-- Botão Editar -->
    <button wire:click="editCandidate({{ $candidate->id }})" class="text-blue-500 hover:text-blue-700">
        <i class="fas fa-edit"></i> Editar
    </button>

    <!-- Botão Excluir -->
    <button wire:click="deleteCandidate({{ $candidate->id }})" class="text-red-500 hover:text-red-700">
        <i class="fas fa-trash"></i> Excluir
    </button>
</td>