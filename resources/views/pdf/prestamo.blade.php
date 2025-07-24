<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GRUPO SAT - Reporte de Préstamo</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            color: #000;
            margin: 40px;
        }

        .header, .footer, .content {
            width: 100%;
            text-align: left;
        }

        .header {
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
        }

        .header h2 {
            font-size: 14px;
            font-weight: normal;
            margin: 4px 0;
        }

        .date-time {
            font-size: 11px;
            margin-bottom: 15px;
            text-align: left;
        }

        h3 {
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            font-weight: bold;
            background-color: #f5f5f5;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
        }

        .footer p {
            line-height: 1.5;
        }

        .signatures {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>ENVIAR A: {{ $prestamo->almacenDestino->nombreAlmacen ?? 'N/A' }}</h2>
        <h2>PRÉSTAMO DEMO - ATN: {{ $prestamo->empleado->nombreUsuario ?? 'N/A' }} {{ $prestamo->empleado->primerApellido ?? '' }} </h2>
    </div>

    <div class="date-time">
        Fecha de creación: {{ \Carbon\Carbon::parse($prestamo->created_at)->format('d/m/Y') }} <br>
        Hora: {{ \Carbon\Carbon::parse($prestamo->created_at)->format('H:i:s') }}
    </div>

    <div class="content">
        <h3>Materiales del Préstamo</h3>
        <table>
            <thead>
                <tr>
                    
                    <th>Nombre del Material</th>
                    <th>Marca</th>
                    <th>Número de Serie</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prestamo->detallesPrestamo as $detalle)
                    <tr>
                        
                        <td>{{ $detalle->material->nombreMaterial ?? 'N/A' }}</td>
                        <td>{{ $detalle->material->marcaMaterial ?? 'N/A' }}</td>
                        <td>{{ $detalle->material->numeroSerie ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">No hay materiales en este préstamo.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>
            Pedido solicitado por: <strong>{{ $prestamo->empleado->nombreUsuario ?? 'N/A' }} {{ $prestamo->empleado->primerApellido ?? '' }}</strong><br>
            Realizado por: <strong>{{ $prestamo->administrador->nombreUsuario ?? 'N/A' }} {{ $prestamo->administrador->primerApellido ?? '' }}</strong><br>
            Autorizado por: <strong>{{ $prestamo->administrador->nombreUsuario ?? 'N/A' }} {{ $prestamo->administrador->primerApellido ?? '' }}</strong><br><br>
            DESTINO: {{ $prestamo->almacenDestino->nombreAlmacen ?? 'N/A' }}<br>
            RECIBE: {{ $prestamo->empleado->nombreUsuario ?? 'N/A' }} {{ $prestamo->empleado->primerApellido ?? '' }}
        </p>
    </div>

    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line">
                {{ $prestamo->empleado->nombreUsuario ?? 'N/A' }} {{ $prestamo->empleado->primerApellido ?? '' }}
            </div>
            <p>Firma del Solicitante</p>
        </div>
        <div class="signature-box">
            <div class="signature-line">
                {{ $prestamo->administrador->nombreUsuario ?? 'N/A' }} {{ $prestamo->administrador->primerApellido ?? '' }}
            </div>
            <p>Firma de Autorización</p>
        </div>
    </div>

</body>
</html>
