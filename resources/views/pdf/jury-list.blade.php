<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuição de Júri</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 20px;
        }

        .logo {
            max-width: 80px;
            height: auto;
        }

        h1,
        h3 {
            color: #0056b3;
            margin: 10px 0;
        }

        h1 {
            font-size: 20px;
        }

        h3 {
            font-size: 18px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table th,
        .info-table td {
            border: 1px solid #e0e0e0;
            padding: 8px;
            text-align: left;
        }

        .info-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #0056b3;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="frontend1/img/logo.png" alt="Logo" class="logo">
        <h3>Comissão de Júri</h3>
        <h1>Distribuição de Júri</h1>
    </div>

    <!-- Cabeçalho com informações do júri -->
    <table class="info-table">
        <tr>
            <th>Júri:</th>
            <td>{{ $jury->name }}</td>
        </tr>
        <tr>
            <th>Sala:</th>
            <td>{{ $jury->room->name }}</td>
        </tr>
        <tr>
            <th>Escola:</th>
            <td>{{ $jury->school->name }}</td>
        </tr>
        <tr>
            <th>Disciplina:</th>
            <td>{{ $disciplinaNome }}</td>
        </tr>
        <tr>
            <th>Data:</th>
            <td>{{ $data }}</td>
        </tr>
        <tr>
            <th>Horário de Chegada</th>
            <td>{{ $horarioDisciplina }}</td>
        </tr>
        <tr>
            <th>Horário de Entrada:</th>
            <td>{{ $horarioEntrada }}</td>
        </tr>
    </table>

    <!-- Lista de candidatos -->
    <h3>Lista de Candidatos</h3>
    <table class="info-table">
        <thead>
            <tr>
                <th>Código de Candidato</th>
                <th>Nome Completo</th>
                <th>Curso</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($candidates as $candidate)
            <tr>
                <td>{{ str_pad($candidate->id, 5, '0', STR_PAD_LEFT) }}</td>

                <td>{{ strtoupper($candidate->name) }} {{ strtoupper($candidate->surname) }}</td>

                <td>{{ $candidate->course->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <hr>
        <p>Data: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>