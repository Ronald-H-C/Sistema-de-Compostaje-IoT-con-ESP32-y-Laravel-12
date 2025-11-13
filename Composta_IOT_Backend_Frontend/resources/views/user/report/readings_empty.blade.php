<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte vacío</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 150px;
        }

        h1 {
            color: #666;
        }

        p {
            color: #999;
        }
    </style>
</head>

<body>
    <h1>⚠️ No se encontraron datos</h1>
    <p>{{ $mensaje ?? 'No hay lecturas registradas en este rango de fechas.' }}</p>
</body>

</html>