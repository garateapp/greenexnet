<!DOCTYPE html>
<html>

<head>
    <style>
        /* Agrega estilos CSS personalizados aquí */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #header {
            text-align: center;
            background-color: #f0f0f0;
            padding: 20px;
        }

        #header img {
            max-width: 200px;
        }

        #contenido {

        }
        .table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}
.table td,
.table th {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}
.table tbody + tbody {
    border-top: 2px solid #dee2e6;
}
.table-sm td,
.table-sm th {
    padding: 0.3rem;
}
.table-bordered,
.table-bordered td,
.table-bordered th {
    border: 1px solid #dee2e6;
}
.table-bordered thead td,
.table-bordered thead th {
    border-bottom-width: 2px;
}
.table-borderless tbody + tbody,
.table-borderless td,
.table-borderless th,
.table-borderless thead th {
    border: 0;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}
.table-hover tbody tr:hover {
    color: #212529;
    background-color: rgba(0, 0, 0, 0.075);
}
.table-primary,
.table-primary > td,
.table-primary > th {
    background-color: #c6e0f5;
}
.table-primary tbody + tbody,
.table-primary td,
.table-primary th,
.table-primary thead th {
    border-color: #95c5ed;
}
.table-hover .table-primary:hover,
.table-hover .table-primary:hover > td,
.table-hover .table-primary:hover > th {
    background-color: #b0d4f1;
}
        /* Agrega un pequeño margen en tablets y escritorio */
        @media screen and (min-width: 768px) {
            #contenido {
                margin: 2px 30px;
                max-width: 600px;
                /* Ancho máximo del contenido */
            }
        }
    </style>
</head>

<body>
    <div id="header">
        <img src="https://appgreenex.cl/image/logogreenex.png" alt="Logo de Greenex">
    </div>
    <div id="contenido">
        <p>Estimados,</p>
        <p>les envíamos el seguimiento de los embarques a la fecha {{ date('d-m-Y') }}</p>

        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Embarque"
        id="datatable-Embarque">
        <thead>
            <tr>
                <th colspan="10"></th>
                <th colspan="2">Conexiones</th>
                <th colspan="4"></th>
            <tr>


                <th>
                N° Embarque
                </th>

                <th>
                Cliente
                </th>
                <th>
                    AWB
                </th>
                <th>
                    Variedad
                </th>
                <th>
                    Cajas
                </th>
                <th>
                    N° Pallets
                </th>

                <th>
                    Aerop. Destino
                </th>
                <th>
                    ETD Estimado
                </th>
                <th>
                    ETA Estimado
                </th>
                <th>
                    ETD Real
                </th>
                <th>
                    País Conexión
                </th>
                <th>
                    Fecha Hora
                </th>
                <th>
                    ETA Real
                </th>
                <th>
                    Status Aéreo
                </th>

                <th>
                   Observaciones
                </th>


            </tr>
            </thead>
            <tbody>
                @foreach ($embarques as $embarque)
                    <tr>
                        <td>{{ $embarque->num_embarque }}</td>
                        <td>{{ $embarque->n_cliente }}</td>
                        <td>{{ $embarque->numero_reserva_agente_naviero }}</td>
                        <td>{{ $embarque->variedad }}</td>
                        <td>{{ $embarque->cajas }}</td>
                        <td>{{ $embarque->cant_pallets }}</td>
                        <td>{{ $embarque->puerto_destino }}</td>
                        <td>{{ $embarque->etd_estimado }}</td>
                        <td>{{ $embarque->eta_estimado }}</td>
                        <td>{{ $embarque->fecha_zarpe_real }}</td>
                        <td>{{ $embarque->pais_conexion }}</td>
                        <td>{{ $embarque->conexion }}</td>
                        <td>{{ $embarque->fecha_arribo_real }}</td>
                        <td>{{ $embarque->status_aereo }}</td>
                        <td>{{ $embarque->notas }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>Para mayor información ingresar a nuestra intranet <a
                href="http://net.greenex.cl">net.greenex.cl</a> menú COMEX opción embarques</p>
        <p>Saludos,</p>
        <p>Equipo Greenex</p>
    </div>
</body>
