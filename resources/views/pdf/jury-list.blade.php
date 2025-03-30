<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Candidatos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            text-align: center;
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 12px;
        }
        th {
            background-color: #f2f2f2;
        }
        td {
            text-align: left;
        }
        .signature {
            height: 80px;
        }
        .header {
            width: 100%;
            margin-top: 20px;
            text-align: left;
        }
        .header-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .header-row span:first-child {
            font-weight: bold;
        }
        .header-row span:last-child {
            margin-left: auto;
            padding-left: 50px;
        }
        footer {
            text-align: center;
            background-color: #f2f2f2;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3 style="color: #2c3e50;">UNIVERSIDADE SAVE</h3>
        <h3 style="color: #34495e;">EXAMES DE ADMISSÃO - 2025</h3>
        <h3 style="color: #7f8c8d;">LISTA DE CANDIDATOS POR SALAS DE EXAME</h3>

        <div class="header">
            <div class="header-row">
                <span>DISCIPLINA:</span>
                <span>Biologia</span>
            </div>
            <div class="header-row">
                <span>PROVINCIA:</span>
                <span>Inhambane - sede-chongoene</span>
            </div>
            <div class="header-row">
                <span>DATA:</span>
                <span>23/01/2025</span>
            </div>
            <div class="header-row">
                <span>HORA:</span>
                <span>08:00 AM</span>
            </div>
            <div class="header-row">
                <span>SALA:</span>
                <span>sala 1</span>
            </div>
            <div class="header-row