<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Candidatos por Local de Exame</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style type="text/css">
        :root {
            --primary-color: #1e40af;
            --secondary-color: #2563eb;
            --background-light: #f8fafc;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
        }

        body {
            font-family: 'Nunito', 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            background-color: #f0f4f8;
            color: var(--text-dark);
            line-height: 1.6;
        }

        .report-container {
            width: 100%;
            max-width: 1200px;
            margin: 2rem auto;
            background-color: white;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08), 0 10px 20px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .report-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2.5rem 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .report-header-content {
            flex-grow: 1;
            text-align: center;
        }

        .report-header h1 {
            font-size: 1.875rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .report-header-date {
            font-weight: 300;
            opacity: 0.85;
            font-size: 0.975rem;
        }

        .report-content {
            padding: 2.5rem 3rem;
        }

        .exam-location-section {
            margin-bottom: 1.75rem;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }

        .exam-location-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem 1.5rem;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .candidate-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .candidate-table thead {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .candidate-table th,
        .candidate-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            text-align: left;
        }

        .candidate-table th {
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
        }

        .candidate-table tr:nth-child(even) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .candidate-table tr:hover {
            background-color: var(--background-light);
        }

        .course-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        .course-name {
            font-weight: 600;
            color: var(--primary-color);
        }

        .candidate-count {
            color: var(--secondary-color);
            font-weight: 700;
        }

        .total-summary {
            background-color: var(--background-light);
            padding: 1.5rem 3rem;
            text-align: center;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
        }

        .total-summary p {
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .report-footer {
            background-color: rgba(0, 0, 0, 0.03);
            text-align: center;
            padding: 1.25rem;
            color: var(--text-muted);
            font-size: 0.8rem;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
        }

        @media (max-width: 640px) {
            .report-container {
                margin: 0;
                border-radius: 0;
            }

            .report-header, .report-content {
                padding: 1.5rem;
            }
        }

        .logo-placeholder {
            width: 120px;
            height: 50px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: 700;
        }
    </style>
</head>
<body class="antialiased">
    <div class="report-container">
        <div class="report-header">
            <div class="logo-placeholder">LOGO</div>
            <div class="report-header-content">
                <h1>Relatório de Candidatos por Local de Exame & Universidade</h1>
                <p class="report-header-date">{{ $date }}</p>
            </div>
        </div>

        <div class="report-content">
            @foreach($report as $localExam => $courses)
<div class="exam-location-section">
    <div class="exam-location-header">
        <span>LOCAL DE EXAME: {{ strtoupper($localExam) }}</span>
        <span>{{ count($courses) }} Cursos</span>
    </div>

    <table class="candidate-table">
        <thead>
            <tr>
                <th>Curso</th>
                <th>Código dos Candidatos</th>
				<th>Onde Quer estudar</th>				
                <th>Nome dos Candidatos</th>
                <th>Total de Candidatos</th>								
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr>
                <td>{{ $course['course'] }}</td>
                <td>
                    <ul>
                        @foreach($course['candidate_id'] as $id)
                        <li>{{ $id }}</li>
                        @endforeach
                    </ul>
                </td>	
				<td>
                    <ul>
                        @foreach($course['candidate_university'] as $university)
                        <li>{{ $university }}</li>
                        @endforeach
                    </ul>
                </td>					
                <td>
                    <ul>
                        @foreach($course['candidate_names'] as $name)
                        <li>{{ $name }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ $course['total_candidates'] }} Candidatos</td>			
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

            @endforeach

            <div class="total-summary">
                <p>Total de Locais de Exame: {{ count($report) }}</p>
                <p>Total de Candidatos: {{ 
                    array_reduce($report->toArray(), function($carry, $localExam) {
                        return $carry + array_reduce($localExam, function($subCarry, $course) {
                            return $subCarry + $course['total_candidates'];
                        }, 0);
                    }, 0) 
                }}</p>
            </div>
        </div>

        <div class="report-footer">
            <p>Este é um relatório automático gerado em {{ $date }}. Por favor, não responda.</p>
        </div>
    </div>
</body>
</html>