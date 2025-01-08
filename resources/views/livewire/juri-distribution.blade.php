<div>
    <h2>Distribuição de Juris</h2>

    <!-- Filtro de Província -->
    <div>
        <label for="provincia">Província:</label>
        <select id="provincia" wire:model="provincia_id">
            <option value="">Selecione uma província</option>
            @foreach ($provincias as $provincia)
                <option value="{{ $provincia->id }}">{{ $provincia->nome }}</option>
            @endforeach
        </select>
    </div>

    <!-- Filtro de Disciplina -->
    <div>
        <label for="disciplina">Disciplina:</label>
        <select id="disciplina" wire:model="disciplina_id">
            <option value="">Selecione uma disciplina</option>
            @foreach ($disciplinas as $disciplina)
                <option value="{{ $disciplina->id }}">{{ $disciplina->nome }}</option>
            @endforeach
        </select>
    </div>

    <!-- Botão para Distribuir -->
    <div>
        <button wire:click="distribuir">Distribuir</button>
    </div>

    <!-- Lista de Distribuição -->
    @if ($juris)
        <h3>Distribuição de Candidatos</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Candidato</th>
                    <th>Sala</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($juris as $juri)
                    <tr>
                        <td>{{ $juri['candidate_id'] }}</td>
                        <td>{{ $juri['sala_id'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Botão para Salvar -->
        <button wire:click="saveJuris">Salvar Distribuição</button>
    @endif

    @if (session()->has('message'))
        <div>
            {{ session('message') }}
        </div>
    @endif
</div>
