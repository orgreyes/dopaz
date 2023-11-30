<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado De Personal</title>
<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            text-align: center;
        }

        header span {
            font-size: 22px;
            font-weight: bold;
            color: #000;
        }

        .report-header {
            text-align: center;
            margin: 20px 0;
        }

        .report-title {
            font-size: 24px;
            color: #000;
            margin: 20px 0;
            text-align: center;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9em;
            margin-bottom: 30px;
            background-color: #fff;
            border: 2px solid #000;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
            border: 1px solid #000;
            text-align: left;
        }

        .styled-table thead th {
            background-color: #f2f2f2;
            color: #000;
        }

        .styled-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .fecha {
            font-size: 0.9em;
            color: #333;
            margin-bottom: 20px;
        }

        header {
            border-bottom: 2px solid #000;
            bottom: 25px;
        }

        .report-footer {
            border-top: 2px solid #000;
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
        }
</style>
</head>

<body>
    <header>
        <div style="text-align: center;">
            <div style="position: absolute; top: -50px; left: 50%; transform: translateX(-50%);">
                <img src="./images/LOGO_ONU.png" alt="Descripción de la imagen" style="width: 80px; height: auto;">
            </div>
        </div>
        <br><br>
    </header>

    <div class="report">
        <h1 class="report-title text-align: center; ">Listado de Personal que Viaja A Contingente</h1>
        <span>DOPAZ</span>
        <div class="fecha">
            <span class="right">Fecha de Impresion: <?= date("d/m/Y") ?></span>
        </div>
    </div>

    <table class="styled-table">
        <thead>
            <tr>
                <th>NO.</th>
                <th>NOMBRE DEL ASPIRANTE</th>
                <th>PUESTO A DESEMPEÑAR</th>
                <th>CONTINGENTE</th>
                <th>FECHA DE INICIO</th>
            </tr>
        </thead>
        <tbody>
    <?php $contador = 1; ?>
    <?php foreach($solicitud as $aprobados) : ?>
        <tr>
            <td><?= $contador++ ?></td>
            <td><?= ($aprobados['nombre_aspirante']) ?></td>
            <td><?= ($aprobados['nombre_puesto']) ?></td>
            <td><?= ($aprobados['nombre_contingente']) ?></td>
            <td><?= ($aprobados['cont_fecha_inicio']) ?></td>

        </tr>
    <?php endforeach; ?>
</tbody>
    </table>

</body>

</html>