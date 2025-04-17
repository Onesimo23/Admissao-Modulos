<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Distribuição de Júris</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2,
        h3 {
            margin: 0;
            padding: 0;
        }

        .jury-header {
            margin-bottom: 10px;
        }

        .page-break {
            page-break-after: always;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
        }

        .footer hr {
            margin: 0;
            border: none;
            border-top: 1px solid #000;
        }
    </style>
</head>

<body>
    @foreach($juries as $roomId => $juryGroup)
    <div class="cabecalho" style="text-align: center; text-transform: uppercase;">
        <img src="https://sig.unisave.ac.mz/sigeup/public/dist/img/up.png" alt="Logotipo">
        <h3>Universidade Save</h3>
        <h3>Exames de Admissão de {{ date('Y') }}</h3>
        <h3>Lista de Candidatos por Sala de Exame</h3>
    </div>
    <hr>

    <div style="background-color: #f5f5f5; padding: 10px; margin-bottom: 20px;">
        <table style="width: 100%; border-collapse: collapse; border: none;" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width: 50%; padding: 5px; border: none;"><strong>DISCIPLINA:</strong> {{ $juryGroup->first()->candidate->course->courseExamSubjects->first()->name ?? $juryGroup->first()->discipline }}</td>
                <td style="width: 50%; padding: 5px; text-align: right; border: none;"><strong>Data:</strong> {{ $juryGroup->first()->candidate->course->courseExamSubjects->first()->exam_date ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="width: 50%; padding: 5px; border: none;"><strong>PROVÍNCIA:</strong> {{ $juryGroup->first()->province->name }}</td>
                <td style="width: 50%; padding: 5px; text-align: right; border: none;"><strong>Hora:</strong> {{ $juryGroup->first()->candidate->course->courseExamSubjects->first()->start_time ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="width: 50%; padding: 5px; border: none;"><strong>SALA:</strong> {{ $juryGroup->first()->room->name }}</td>
                <td style="width: 50%; padding: 5px; text-align: right; border: none;"><strong>H.Entrada:</strong> {{ $juryGroup->first()->candidate->course->courseExamSubjects->first()->arrival_time ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 10px;">
        <strong>LOCAL:</strong> {{ $juryGroup->first()->school->name }}
    </div>

    <div class="jury-section">
        <table>
            <thead>
                <tr>
                    <th>Nrº de Candidato</th>
                    <th>Nome do Candidato</th>
                    <th>Assinatura do Candidato</th>
                </tr>
            </thead>
            <tbody>
                @foreach($juryGroup as $jury)
                <tr>
                    <td>{{ $jury->candidate->id }}</td>
                    <td>{{ $jury->candidate->name }} {{ $jury->candidate->surname }}</td>
                    <td></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>
    @endforeach

    <div class="footer">
        <hr>
        <script type="text/php">
            if (isset($pdf)) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial", "normal");
            $size = 9;
            $pageText = "Página " . $PAGE_NUM . " de " . $PAGE_COUNT;
            $y = 820; // Ajusta a posição vertical para o rodapé
            $x = 270; // Centraliza o texto horizontalmente
            $pdf->text($x, $y, $pageText, $font, $size);
        ');
    }
</script>
    </div>
</body>

</html>