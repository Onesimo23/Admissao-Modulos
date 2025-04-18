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
    @foreach($juries as $groupKey => $juryGroup)
    @php
    $first = $juryGroup->first();
    $examSubject = $first->examSubject;
    $room = $first->room;
    $school = $first->school;
    $province = $first->province;
    @endphp

    <div class="cabecalho" style="text-align: center; text-transform: uppercase;">
        <img src="{{ public_path('up.png') }}" alt="Logotipo" style="width: 100px; height: auto;">
        <h3>Universidade Save</h3>
        <h3>Exames de Admissão de {{ date('Y') }}</h3>
        <h3>Lista de Candidatos por Sala de Exame</h3>
    </div>
    <hr>

    <div style="background-color: #f5f5f5; padding: 10px; margin-bottom: 20px;">
        <table style="width: 100%; border-collapse: collapse; border: none;" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width: 50%; padding: 5px; border: none;"><strong>DISCIPLINA:</strong> {{ $examSubject->name }}</td>
                <td style="width: 50%; padding: 5px; text-align: right; border: none;"><strong>Data:</strong> {{ $examSubject->exam_date }}</td>
            </tr>
            <tr>
                <td style="width: 50%; padding: 5px; border: none;"><strong>PROVÍNCIA:</strong> {{ $province->name }}</td>
                <td style="width: 50%; padding: 5px; text-align: right; border: none;"><strong>Hora:</strong> {{ $examSubject->start_time }}</td>
            </tr>
            <tr>
                <td style="width: 50%; padding: 5px; border: none;"><strong>SALA:</strong> {{ $room->name }}</td>
                <td style="width: 50%; padding: 5px; text-align: right; border: none;"><strong>H.Entrada:</strong> {{ $examSubject->arrival_time }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-bottom: 10px;">
        <strong>LOCAL:</strong> {{ $school->name }}
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
                    <td>{{ $jury->candidate->surname }} {{ $jury->candidate->name }}</td>
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
                $y = 820;
                $x = 270;
                $pdf->text($x, $y, $pageText, $font, $size);
            ');
        }
    </script>
    </div>
</body>

</html>