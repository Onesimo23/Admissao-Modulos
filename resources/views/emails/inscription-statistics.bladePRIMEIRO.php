<!DOCTYPE html>
<html>
<head>
    <title>Estatísticas de Inscrições</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            padding: 20px;
        }

        h2, h3 {
            color: #006699;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #006699;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <h2>Estatísticas de Inscrições - {{ $date }}</h2>

    <div style="display: flex;">
        <table style="margin-right: 20px;">
            <tr>
                <th>Global</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>Total de Candidatos</td>
                <td>{{ $counts['all_candidates'] }}</td>
            </tr>
            <tr>
                <td>Total de Pagamentos</td>
                <td>{{ $counts['all_payments'] }}</td>
            </tr>
            <tr>
                <td>Pagamentos Pendentes</td>
                <td>{{ $counts['pending_payments'] }}</td>
            </tr>
            <tr>
                <td>Pagamentos Confirmados</td>
                <td>{{ $counts['confirmed_payments'] }}</td>
            </tr>
        </table>
    </div>

    <h3>Inscrições por Curso</h3>
    <table>
        <tr>
            <th>Curso</th>
            <th>Total</th>
            <th>Pendentes</th>
            <th>Confirmadas</th>
        </tr>
        @foreach($counts['candidates_by_course'] as $courseName => $stats)
        <tr>
            <td>{{ strtoupper($courseName) }}</td>
            <td>{{ $stats['total'] }}</td>
            <td class="status-pending">{{ $stats['pending'] }}</td>
            <td class="status-confirmed">{{ $stats['confirmed'] }}</td>
        </tr>
        @endforeach
    </table>

    <h3>Inscrições por Universidade</h3>
    <table>
        <tr>
            <th>Universidade</th>
            <th>Total</th>
            <th>Pendentes</th>
            <th>Confirmadas</th>
        </tr>
        @foreach($counts['candidates_by_university'] as $universityName => $stats)
        <tr>
            <td>{{ strtoupper($universityName) }}</td>
            <td>{{ $stats['total'] }}</td>
            <td class="status-pending">{{ $stats['pending'] }}</td>
            <td class="status-confirmed">{{ $stats['confirmed'] }}</td>
        </tr>
        @endforeach
    </table>

    <h3>Inscrições por Regime</h3>
    <table>
        <tr>
            <th>Regime</th>
            <th>Total</th>
            <th>Pendentes</th>
            <th>Confirmadas</th>
        </tr>
        @foreach($counts['candidates_by_regime'] as $regimeName => $stats)
        <tr>
            <td>{{ strtoupper($regimeName) }}</td>
            <td>{{ $stats['total'] }}</td>
            <td class="status-pending">{{ $stats['pending'] }}</td>
            <td class="status-confirmed">{{ $stats['confirmed'] }}</td>
        </tr>
        @endforeach
    </table>

    <h3>Inscrições por Universidade e Curso</h3>
    <table>
        <tr>
            <th>Universidade</th>
            <th>Curso</th>
            <th>Total</th>
            <th>Pendentes</th>
            <th>Confirmadas</th>
        </tr>
        @foreach($counts['candidates_by_university_course'] as $universityName => $courses)
            @foreach($courses as $stats)
            <tr>
                <td>{{ strtoupper($stats['university']) }}</td>
                <td>{{ strtoupper($stats['course']) }}</td>
                <td>{{ $stats['total'] }}</td>
                <td class="status-pending">{{ $stats['pending'] }}</td>
                <td class="status-confirmed">{{ $stats['confirmed'] }}</td>
            </tr>
            @endforeach
        @endforeach
    </table>
</body>
</html>