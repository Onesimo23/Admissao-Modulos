<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Distribuição de Júris</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2, h3 { margin: 0; padding: 0; }
        .jury-header { margin-bottom: 10px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    @php
        $grouped = $juries->groupBy(function($jury) {
            return $jury->room_id . '-' . $jury->discipline;
        });
    @endphp

    @foreach($grouped as $groupKey => $juryGroup)
        @php
            $first = $juryGroup->first();
        @endphp

        <div class="jury-section">
            <div class="jury-header">
                <h2>Júri da Sala: {{ $first->room->name }}</h2>
                <h3>Disciplina: {{ $first->candidate->course->courseExamSubjects->first()->name ?? $first->discipline }}</h3>
                <p><strong>Escola:</strong> {{ $first->school->name }} | <strong>Província:</strong> {{ $first->province->name }}</p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome do Candidato</th>
                        <th>Curso</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($juryGroup as $index => $jury)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $jury->candidate->name }}</td>
                            <td>{{ $jury->candidate->course->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
