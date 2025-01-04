<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guião de Pagamento  - {{ str_pad($candidate->id, 5, '0', STR_PAD_LEFT) }}</title>
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
		
        h1, h3 {
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
        .info-table th, .info-table td {
            border: 1px solid #e0e0e0;
            padding: 8px;
            text-align: left;
        }
        .info-table th {
            background-color: #f2f2f2;
            width: 40%;
            font-weight: bold;
            color: #0056b3;
        }		
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-top: 30px;
            font-size: 14px;
			text-align: justify;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
        }
		
		.status-pendente {
			color: red;
		}

		.status-confirmado {
			color: green;
			font-weight: bold;
		}			
    </style>
</head>
<body>
        <div class="header">
            <img src="frontend1/img/logo.png" alt="Logo" class="logo">
            <h3>Comissão de Exames de Admissão</h3>
            <h1>Guião de Pagamento</h1>
        </div>
    
    <p  style="text-align: justify;">Este guião serve de orientação para o pagamento da taxa de inscrição no banco e por conseguinte a validação da sua inscrição. <strong>NB</strong> - Este guião não serve de comprovativo de pagamento ou inscrição.</p>
    
    <table class="info-table">
        <tr>
            <th>Código de Candidato:</th>
            <td>{{ str_pad($candidate->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <th>Nome Completo:</th>
            <td>{{ strtoupper($candidate->name) }} {{ strtoupper($candidate->surname) }}</td>
        </tr>
        <tr>
            <th>Entidade:</th>
            <td><strong>{{ $entity }}</strong></td>
        </tr>
        <tr>
            <th>Referência:</th>
            <td><strong>{{ $reference }}</strong></td>
        </tr>
        <tr>
            <th>Valor:</th>
            <td><strong>{{ number_format($amount, 2) }} MT</strong></td>
        </tr>
        <tr>
            <th>Curso:</th>
            <td>{{ strtoupper($candidate->course->name) }}</td>
        </tr>
        <tr>
            <th>Local de Residência:</th>
            <td>{{ strtoupper($candidate->province->name) }}</td>
        </tr>
        <tr>
            <th>Local que pretende Estudar:</th>
            <td>{{ strtoupper($candidate->university->name) }}</td>
        </tr>
        <tr>
            <th>Regime:</th>
            <td>{{ $candidate->regime->name }}</td>
        </tr>
		@if($candidate->regime_id == 1)
        <tr>
            <th>Local que irá realizar o Exame:</th>
            <td>{{ strtoupper($candidate->localExam->name) }}</td>
        </tr>
		@endif		
		<tr>
			<th>Estado da inscrição:</th>
			<td class="{{ $candidate->payment->status < 1 ? 'status-pendente' : 'status-confirmado' }}">
				{{ $candidate->payment->status < 1 ? 'PENDENTE' : 'CONFIRMADA' }}
			</td>
		</tr>	
    </table>
    
    <div class="warning text-justify">
        <p><strong>Atenção:</strong> É obrigatório efectuar o pagamento no Millennium BIM. A Comissão de Exames de Admissão não se responsabiliza pelos pagamentos efectuados em outras instituições bancárias.</p>
        <p>Após efectuar o pagamento, aguarde 72 horas e volte a entrar na plataforma para verificar a actualização do Estado da Inscrição.</p>
    </div>
   
    <div class="footer">
	<hr>
        <p>Data: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>