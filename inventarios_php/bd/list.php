<?php
// header("Content-Type: application/ms-excel; charset=utf-8");
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header('Content-Disposition: attachment; filename=Lista_de_pedidos.xls');

include("./servidor.php");
?>
<!DOCTYPE html>

<head>
    <meta content="charset=utf-8" />
</head>

<body style="border: 0.1pt solid #ccc; text-align:left; font-size:11pt;">
    <form>
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size:12pt;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripcion SAP</th>
                    <th>Codigo SAP</th>
                    <th>Unidad de Medida</th>
                    <th>Codigo de Bodega</th>
                    <th>Nombre de Bodega</th>
                    <th>Existencia SAP</th>
                    <th>Costo Promedio</th>
                    <th>Conteo Fisico</th>
                    <th>Cantidad Diferencia</th>
                    <th>Costo Diferencia</th>
                    <th>Empresa</th>
                    <th>Usuario</th>
                    <th>Estado</th>
                    <th>Inventario</th>
                    <th>Fecha Actualizacion</th>
                    <th>Observaciones Gerencia</th>
                    <th>Observaciones Contador</th>
                    <th>Riesgo</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT c.idConteo AS 'ID', c.DescripcionSAP AS 'Descripcion SAP', c.CodigoSAP AS 'Codigo SAP', c.UnidadDeMedida AS 'Unidad de Medida', c.CodigoBodega AS 'Codigo Bodega', c.NombreBodega AS 'Nombre de Bodega', c.ExistenciaSAP AS 'Existencia SAP', c.CostoPromedio AS 'Costo Promedio', c.ConteoFisico AS 'Conteo Fisico', c.CantidadDiferencia AS 'Cantidad Diferencia', c.CostoDiferencia AS 'Costo Diferencia', e.Nombre AS 'Empresa', u.Nombre AS 'Usuario', es.Nombre AS 'Estado', i.nombre AS 'Inventario', c.FechaActualizacion AS 'Fecha Actualizacion', c.ObservacionesGerencia AS 'Observaciones Gerencia', c.ObservacionesContador AS 'Observaciones Contador', c.Riesgo AS 'Riesgo' FROM conteos c INNER JOIN empresa e ON c.idEmpresa = e.idEmpresa INNER JOIN usuario u ON c.idUsuario = u.idUsuario INNER JOIN estado es ON c.idEstado = es.idEstado INNER JOIN inventarios i ON c.idInventario = i.idInventario WHERE MONTH(c.FechaActualizacion) = 5 AND YEAR(c.FechaActualizacion) = 2021";
                $resultado = mysqli_query($con, $sql);
                //echo $sql;
                while ($fila = mysqli_fetch_array($resultado)) {
                    echo "<tr>";
                    echo "<td>" . $fila["ID"] . "</td>";
                    echo "<td>" . $fila["Descripcion SAP"] . "</td>";
                    echo "<td>" . $fila["Codigo SAP"] . "</td>";
                    echo "<td>" . $fila["Unidad de Medida"] . "</td>";
                    echo "<td>" . $fila["Codigo Bodega"] . "</td>";
                    echo "<td>" . $fila["Nombre de Bodega"] . "</td>";
                    echo "<td>" . $fila["Existencia SAP"] . "</td>";
                    echo "<td>" . $fila["Costo Promedio"] . "</td>";
                    echo "<td>" . $fila["Conteo Fisico"] .  "</td>";
                    echo "<td>" . $fila["Cantidad Diferencia"] . "</td>";
                    echo "<td>" . $fila["Costo Diferencia"] . "</td>";
                    echo "<td>" . $fila["Empresa"] . "</td>";
                    echo "<td>" . $fila["Usuario"] . "</td>";
                    echo "<td>" . $fila["Estado"] . "</td>";
                    echo "<td>" . $fila["Inventario"] . "</td>";
                    echo "<td>" . $fila["Fecha Actualizacion"] . "</td>";
                    echo "<td>" . $fila["Observaciones Gerencia"] . "</td>";
                    echo "<td>" . $fila["Observaciones Contador"] . "</td>";
                    echo "<td>" . $fila["Riesgo"] . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </form>
</body>

</html>
<?php exit; ?>