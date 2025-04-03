<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuição de Júris</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Distribuição de Júris</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome do Candidato</th>
                <th>Disciplina</th>
                <th>Sala</th>
                <th>Escola</th>
                <th>Província</th>
            </tr>
        </thead>
        <tbody>
            @foreach($juries as $index => $jury)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $jury->candidate->name }}</td>
                    <td>{{ $jury->discipline }}</td>
                    <td>{{ $jury->room->name }}</td>
                    <td>{{ $jury->school->name }}</td>
                    <td>{{ $jury->province->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
