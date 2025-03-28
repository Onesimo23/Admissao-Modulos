<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Candidatos - Exames de Admissão {{ now()->year }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            width: 80%;
            margin: auto;
            text-align: center;
        }

        h1,
        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .info {
            text-align: left;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>UNIVERSIDADE SAVE</h1>
        <h2>EXAMES DE ADMISSÃO - {{ now()->year }}</h2>
        <h3>LISTA DE CANDIDATOS POR SALAS DE EXAME</h3>

        <div class="info">
            <p><strong>DISCIPLINA:</strong> {{ $disciplina }}</p>
            <p><strong>PROVÍNCIA:</strong> {{ $provincia }}</p>
            <p><strong>DATA:</strong> {{ $data }} <strong>Hora:</strong> {{ $hora }}</p>
            <p><strong>SALA:</strong> {{ $sala }} <strong>H.Exame:</strong> {{ $horaExame }}</p>
            <p><strong>LOCAL:</strong> {{ $local }}</p>
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
                @foreach ($candidates as $index => $candidate)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ str_pad($candidate->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ strtoupper($candidate->name) }} {{ strtoupper($candidate->surname) }}</td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <footer>
            <p>Página 1</p>
        </footer>
    </div>
</body>

</html>