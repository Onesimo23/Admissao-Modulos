<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos os Júris - Exames de Admissão {{ now()->year }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1, h2, h3 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .info {
            margin-bottom: 20px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <h1>UNIVERSIDADE SAVE</h1>
    <h2>EXAMES DE ADMISSÃO - {{ now()->year }}</h2>
    <h3>LISTA DE TODOS OS JÚRIS</h3>

    @if ($juries->isNotEmpty())
        @foreach ($juries->groupBy('examSubject.name') as $discipline => $disciplineJuries)
            <div class="info">
                <h2>Disciplina: {{ $discipline }}</h2>
            </div>

            @foreach ($disciplineJuries as $jury)
                <div class="info">
                    <p><strong>Júri:</strong> {{ $jury->name }}</p>
                    <p><strong>Província:</strong> {{ $jury->room->school->province->name ?? 'N/A' }}</p>
                    <p><strong>Data:</strong> {{ $jury->examSubject->exam_date ?? 'N/A' }}</p>
                    <p><strong>Hora:</strong> {{ $jury->examSubject->start_time ?? 'N/A' }}</p>
                    <p><strong>Sala:</strong> {{ $jury->room->name ?? 'N/A' }}</p>
                    <p><strong>Local:</strong> {{ $jury->room->school->name ?? 'N/A' }}</p>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Ord</th>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Assinatura</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jury->candidates as $index => $candidate)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ str_pad($candidate->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ strtoupper($candidate->name) }} {{ strtoupper($candidate->surname) }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="page-break