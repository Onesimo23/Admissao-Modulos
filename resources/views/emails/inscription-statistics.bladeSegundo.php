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

        h2, h3, h4 {
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

        .university-header {
            background-color: #004466;
            color: white;
            font-size: 1.1em;
            padding: 15px;
        }

        .regime-header {
            background-color: #0077b3;
            color: white;
        }

        .summary-row {
            font-weight: bold;
            background-color: #e6f3ff;
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

        <table style="margin-left: 20px;">
            <tr>
                <th>Universidade</th>
                <th>Total</th>
                <th>Pendentes</th>
                <th>Confirmados</th>
            </tr>
            @foreach($counts['university_summary'] as $universityName => $stats)
            <tr>
                <td>{{ strtoupper($universityName) }}</td>
                <td>{{ $stats['total'] }}</td>
                <td class="status-pending">{{ $stats['pending'] }}</td>
                <td class="status-confirmed">{{ $stats['confirmed'] }}</td>
            </tr>
            @endforeach
        </table>

    </div>
	
   <h3>Inscrições por Regime</h3>
    <table>
        <tr>
            <th>Regime</th>
            <th>Total</th>
            <th>Pendentes</th>
            <th>Confirmadas</th>
        </tr>
        @foreach($counts['regime_summary'] as $regimeName => $stats)
        <tr>
            <td>{{ strtoupper($regimeName) }}</td>
            <td>{{ $stats['total'] }}</td>
            <td class="status-pending">{{ $stats['pending'] }}</td>
            <td class="status-confirmed">{{ $stats['confirmed'] }}</td>
        </tr>
        @endforeach
    </table>
	

    <h3>Estatísticas Detalhadas por Universidade e Regime</h3>
    
    @foreach($counts['detailed_statistics'] as $groupStats)
        <div style="margin-bottom: 30px;">
            <div class="university-header">
                UNIVERSIDADE: {{ strtoupper($groupStats['university']) }}
            </div>
            
            <table>
                <tr class="regime-header">
                    <th colspan="5">REGIME: {{ strtoupper($groupStats['regime']) }}</th>
                </tr>
                <tr>
                    <th>Curso</th>
                    <th>Total</th>
                    <th>Pendentes</th>
                    <th>Confirmados</th>
                </tr>
                
                @foreach($groupStats['courses'] as $course)
                <tr>
                    <td>{{ strtoupper($course['course']) }}</td>
                    <td>{{ $course['total'] }}</td>
                    <td class="status-pending">{{ $course['pending'] }}</td>
                    <td class="status-confirmed">{{ $course['confirmed'] }}</td>
                </tr>
                @endforeach
                
                <tr class="summary-row">
                    <td>TOTAL {{ strtoupper($groupStats['regime']) }}</td>
                    <td>{{ $groupStats['total'] }}</td>
                    <td class="status-pending">{{ $groupStats['pending'] }}</td>
                    <td class="status-confirmed">{{ $groupStats['confirmed'] }}</td>
                </tr>
            </table>
        </div>
    @endforeach

</body>
</html>