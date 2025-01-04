<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatísticas de Inscrições</title>
	
<style type="text/css">
    /* Reset base */
    * {
        margin: 0;
        padding: 0;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    /* Estilos base */
    body {
        margin: 0;
        padding: 0;
        width: 100% !important;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        background-color: #f5f5f5;
    }

    img {
        border: 0;
        outline: none;
        text-decoration: none;
        -ms-interpolation-mode: bicubic;
    }

    table {
        border-collapse: collapse;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%;
    }

    table td {
        border-collapse: collapse;
    }

    /* Classes principais */
    .email-container {
        width: 100%;
        max-width: 600px;
        margin: auto;
        background-color: #ffffff;
    }

    .header {
        background-color: #2563eb;
        padding: 30px 0;
        text-align: center;
        color: #ffffff;
    }

    .content {
        padding: 30px 0;
    }

    .stats-card {
        background-color: #f8fafc;
        padding: 15px 0;
        margin-bottom: 10px;
    }

    .section-title {
        color: #1e40af;
        font-size: 18px;
        margin: 30px 0 15px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #2563eb;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #d97706;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .status-confirmed {
        background-color: #d1fae5;
        color: #059669;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .footer {
        padding: 20px 0;
        text-align: center;
        color: #64748b;
        font-size: 12px;
        border-top: 1px solid #e2e8f0;
    }

</style>	
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f5f5f5; color: #333333;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f5f5f5;">
        <tr>
            <td align="center" style="padding: 20px;">
                <!-- Container Principal -->
                <table cellpadding="0" cellspacing="0" border="0" width="600" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Cabeçalho -->
                    <tr>
                        <td style="padding: 30px 40px; text-align: center; background-color: #2563eb; border-radius: 8px 8px 0 0;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Estatísticas de Inscrições</h1>
                            <p style="color: #ffffff; margin: 10px 0 0 0;">{{ $date }}</p>
                        </td>
                    </tr>

                    <!-- Resumo Global -->
                    <tr>
                        <td style="padding: 30px 40px;">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 15px; background-color: #f8fafc; border-radius: 8px; width: 50%;">
                                        <p style="margin: 0; color: #64748b; font-size: 14px;">Total de Candidatos</p>
                                        <h2 style="margin: 5px 0 0 0; color: #2563eb; font-size: 24px;">{{ $counts['all_candidates'] }}</h2>
                                    </td>
                                    <td width="20">&nbsp;</td>
                                    <td style="padding: 15px; background-color: #f8fafc; border-radius: 8px; width: 50%;">
                                        <p style="margin: 0; color: #64748b; font-size: 14px;">Guião de Pagamentos Gerados</p>
                                        <h2 style="margin: 5px 0 0 0; color: #2563eb; font-size: 24px;">{{ $counts['all_payments'] }}</h2>
                                    </td>
                                </tr>
                                <tr><td colspan="3" height="20"></td></tr>
                                <tr>
                                    <td style="padding: 15px; background-color: #f8fafc; border-radius: 8px;">
                                        <p style="margin: 0; color: #64748b; font-size: 14px;">Pagamentos Pendentes</p>
                                        <h2 style="margin: 5px 0 0 0; color: #d97706; font-size: 24px;">{{ $counts['pending_payments'] }}</h2>
                                    </td>
                                    <td width="20">&nbsp;</td>
                                    <td style="padding: 15px; background-color: #f8fafc; border-radius: 8px;">
                                        <p style="margin: 0; color: #64748b; font-size: 14px;">Pagamentos Confirmados</p>
                                        <h2 style="margin: 5px 0 0 0; color: #059669; font-size: 24px;">{{ $counts['confirmed_payments'] }}</h2>
                                    </td>
                                </tr>
                            </table>

                            <!-- Resumo por Universidade -->
                            <h3 style="color: #1e40af; font-size: 18px; margin: 30px 0 15px 0; padding-bottom: 10px; border-bottom: 2px solid #2563eb;">Resumo por Universidade</h3>
                            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 30px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <tr style="background-color: #2563eb;">
                                    <th style="padding: 12px; color: #ffffff; text-align: left; font-size: 14px;">Universidade</th>
                                    <th style="padding: 12px; color: #ffffff; text-align: center; font-size: 14px;">Total</th>
                                    <th style="padding: 12px; color: #ffffff; text-align: center; font-size: 14px;">Pendentes</th>
                                    <th style="padding: 12px; color: #ffffff; text-align: center; font-size: 14px;">Confirmados</th>
                                </tr>
                                @foreach($counts['university_summary'] as $universityName => $stats)
                                <tr>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; font-size: 14px;">{{ strtoupper($universityName) }}</td>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 14px;">{{ $stats['total'] }}</td>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 14px;">
                                        <span style="background-color: #fef3c7; color: #d97706; padding: 4px 8px; border-radius: 4px;">{{ $stats['pending'] }}</span>
                                    </td>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 14px;">
                                        <span style="background-color: #d1fae5; color: #059669; padding: 4px 8px; border-radius: 4px;">{{ $stats['confirmed'] }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </table>

                            <!-- Inscrições por Regime -->
                            <h3 style="color: #1e40af; font-size: 18px; margin: 30px 0 15px 0; padding-bottom: 10px; border-bottom: 2px solid #2563eb;">Inscrições por Regime</h3>
                            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 30px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <tr style="background-color: #2563eb;">
                                    <th style="padding: 12px; color: #ffffff; text-align: left; font-size: 14px;">Regime</th>
                                    <th style="padding: 12px; color: #ffffff; text-align: center; font-size: 14px;">Total</th>
                                    <th style="padding: 12px; color: #ffffff; text-align: center; font-size: 14px;">Pendentes</th>
                                    <th style="padding: 12px; color: #ffffff; text-align: center; font-size: 14px;">Confirmados</th>
                                </tr>
                                @foreach($counts['regime_summary'] as $regimeName => $stats)
                                <tr>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; font-size: 14px;">{{ strtoupper($regimeName) }}</td>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 14px;">{{ $stats['total'] }}</td>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 14px;">
                                        <span style="background-color: #fef3c7; color: #d97706; padding: 4px 8px; border-radius: 4px;">{{ $stats['pending'] }}</span>
                                    </td>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 14px;">
                                        <span style="background-color: #d1fae5; color: #059669; padding: 4px 8px; border-radius: 4px;">{{ $stats['confirmed'] }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </table>

                            <!-- Estatísticas Detalhadas -->
                            <h3 style="color: #1e40af; font-size: 18px; margin: 30px 0 15px 0; padding-bottom: 10px; border-bottom: 2px solid #2563eb;">Estatísticas Detalhadas</h3>
                            @foreach($counts['detailed_statistics'] as $groupStats)
                            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-bottom: 30px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <tr>
                                    <td colspan="4" style="padding: 12px; background-color: #1e40af; color: #ffffff; font-weight: bold; border-radius: 8px 8px 0 0;">
                                        UNIVERSIDADE: {{ strtoupper($groupStats['university']) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="padding: 12px; background-color: #2563eb; color: #ffffff; font-weight: bold;">
                                        REGIME: {{ strtoupper($groupStats['regime']) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="padding: 12px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 14px; text-align: left;">Curso</th>
                                    <th style="padding: 12px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 14px; text-align: center;">Total</th>
                                    <th style="padding: 12px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 14px; text-align: center;">Pendentes</th>
                                    <th style="padding: 12px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 14px; text-align: center;">Confirmados</th>
                                </tr>
                                @foreach($groupStats['courses'] as $course)
                                <tr>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; font-size: 14px;">{{ strtoupper($course['course']) }}</td>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 14px;">{{ $course['total'] }}</td>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 14px;">
                                        <span style="background-color: #fef3c7; color: #d97706; padding: 4px 8px; border-radius: 4px;">{{ $course['pending'] }}</span>
                                    </td>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 14px;">
                                        <span style="background-color: #d1fae5; color: #059669; padding: 4px 8px; border-radius: 4px;">{{ $course['confirmed'] }}</span>
                                    </td>
                                </tr>
                                @endforeach
                                <tr style="background-color: #f8fafc; font-weight: bold;">
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; font-size: 14px;">TOTAL {{ strtoupper($groupStats['regime']) }}</td>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 14px;">{{ $groupStats['total'] }}</td>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 14px;">
                                        <span style="background-color: #fef3c7; color: #d97706; padding: 4px 8px; border-radius: 4px;">{{ $groupStats['pending'] }}</span>
                                    </td>
                                    <td style="padding: 12px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 14px;">
                                        <span style="background-color: #d1fae5; color: #059669; padding: 4px 8px; border-radius: 4px;">{{ $groupStats['confirmed'] }}</span>
                                    </td>
                                </tr>
                            </table>
                            @endforeach
                        </td>
                    </tr>

                    <!-- Rodapé -->
                    <tr>
                        <td style="padding: 20px; text-align: center; color: #64748b; font-size: 12px; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0;">Este é um e-mail automático. Por favor, não responda.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>