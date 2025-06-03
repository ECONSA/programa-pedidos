<?php
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header('Content-Disposition: attachment; filename=test.xls');

date_default_timezone_set('UTC');
date_default_timezone_set("America/Guatemala");
$hoy = date("Y") . "-" . date("m") . "-" . date("d");

// ---------------- MYSQL -------------------- //
$con = mysqli_connect("localhost", "admin", "");
if (!$con) {
    die('Could not connect: ' . mysqli_connect_error());
}
mysqli_select_db($con, "facturacion");
// ---------------- Consulta ----------------- //
$mysql = "SELECT
    pu.idPedidoUnhesa AS 'ID',
    'I' AS 'Variable_I',
    pu.fecha_emision AS 'Fecha_Emision_FECHA',
    pu.fecha_despacho AS 'Fecha_Despacho_FECHA',
    c.codigo AS 'Codigo_Cliente',
    c.nombre AS 'Nombre_Cliente',
    'QTZ' AS 'Moneda',
    pu.observacion_A AS 'Comentarios',
    7 AS 'Nombre_Grupo',
    pu.hora AS 'Hora_minuto',
    1 AS 'Codigo_Persona',
    '06' AS 'Series',
    pu.fecha_emision AS 'Fecha_Emision_FECHA_TAX',
    pu.fecha_emision AS 'Fecha_Emision_FECHA_U',
    pu.observacion AS 'Observacion_NIT',
    pu.observacion AS 'Observacion_Nombre_Cliente',
	pu.direccion AS 'Direccion'
FROM
    pedidounhesa pu
INNER JOIN cliente c ON
    pu.idCliente = c.idCliente
WHERE pu.idVendedor = 16 AND pu.total <= 700 AND pu.idEstado = 1";
//  

$fecha = $_GET["fecha"];
if ($_GET["antes"] == 'si') {
    $mysql .= " AND DATE(fecha_emision) = '".$fecha."' AND TIME(fecha_emision) <= '12:00:00' ORDER BY pu.idPedidoUnhesa";
}else{
    $mysql .= " AND DATE(fecha_emision) = '".$fecha."' AND TIME(fecha_emision) > '12:00:00' ORDER BY pu.idPedidoUnhesa";
}

$result = mysqli_query($con, $mysql);
// ----------------------------------------------- //
// $comentario = "NOMBRE:Jonathan Guerra - NIT:123-5";
// $nombre_inicio = strpos($comentario, ':');
// $guion = strpos($comentario, '-');
// // echo $nombre_completo = substr($comentario, $nombre_inicio + 1, ($guion - $nombre_inicio - 1));
// $cadena_completa = strlen($comentario);nit_completo

// $nit_subtring = substr($comentario, $guion + 1, ($cadena_completa - $guion - 1));
// $nit_inicio = strpos($nit_subtring, ':');
// $cadena_completa = strlen($nit_subtring);
// $nombre_completo = substr($nit_subtring, $nit_inicio + 1, ($cadena_completa - $nit_inicio - 1));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta content="charset=utf-8" />
</head>
<body style="border: 0.1pt solid #ccc; text-align:left; font-size:11pt;">
    <table>
        <thead>
            <tr>
                <th>DocNum</th>
                <th>DocType</th>
                <th>DocDate</th>
                <th>DocDueDate</th>
                <th>CardCode</th>
                <th>CardName</th>
                <th>DocCurrency</th>
                <th>Comments</th>
                <th>PaymentGroupCode</th>
                <th>SalesPersonCode</th>
                <th>Series</th>
                <th>TaxDate</th>
                <th>U_fecha</th>
                <th>U_FacNit</th>
                <th>U_FacNom</th>
                <th>U_Direccion</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>DocNum</td>
                <td>DocType</td>
                <td>DocDate</td>
                <td>DocDueDate</td>
                <td>CardCode</td>
                <td>CardName</td>
                <td>DocCur</td>
                <td>Comments</td>
                <td>GroupNum</td>
                <td>SlpCode</td>
                <td>Series</td>
                <td>TaxDate</td>
                <td>U_fecha</td>
                <td>U_FacNit</td>
                <td>U_FacNom</td>
                <td>U_Direccion</td>
            </tr>
            <?php 
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        //Fecha de emision
                        $fecha_emision = strtotime($row["Fecha_Emision_FECHA"]);
                        $anio_emision = date("Y", $fecha_emision);
                        $mes_emision = date("m", $fecha_emision);
                        $dia_emision = date("d", $fecha_emision);
                        // Fecha de despacho
                        $fecha_despacho = strtotime($row["Fecha_Despacho_FECHA"]);
                        $anio_despacho = date("Y", $fecha_despacho);
                        $mes_despacho = date("m", $fecha_despacho);
                        $dia_despacho = date("d", $fecha_despacho);
                        // Horario
                        $horario = strtotime($row["Hora_minuto"]);
                        $hora = date("H", $horario);
                        $minuto = date("i", $horario);
                        // Fecha tax
                        $fecha_tax = strtotime($row["Fecha_Emision_FECHA_TAX"]);
                        $anio_tax = date("Y", $fecha_tax);
                        $mes_tax = date("m", $fecha_tax);
                        $dia_tax = date("d", $fecha_tax);
                        // Fecha_U
                        $fecha_u = strtotime($row["Fecha_Emision_FECHA_U"]);
                        $anio_u = date("Y", $fecha_u);
                        $mes_u = date("m", $fecha_u);
                        $dia_u = date("d", $fecha_u);
                        //Nombre de observaciones
                        try {
                            if (strtoupper($row["Observacion_Nombre_Cliente"]) != "CF") {
                                $nombre_observacion = $row["Observacion_Nombre_Cliente"];
                                $guion = strpos($nombre_observacion, '-');
                                $nombre_completo = substr($nombre_observacion, 0, ($guion - 1));
                                $cadena_completa = strlen($nombre_observacion);
                                
                                //Nit de observaciones
                                $nit_subtring = substr($nombre_observacion, $guion + 1, ($cadena_completa - $guion - 1));
                                $cadena_completa = strlen($nit_subtring);
                                $nit_completo = substr($nit_subtring, 1, ($cadena_completa - 1));
                            }else{
                                $nit_completo = "CF";
                                $nombre_completo = "CF";
                            }
                        } catch (\Throwable $th) {

                        }

                        echo '<tr>';
                        echo '<td>'.$row["ID"].'</td>';
                        echo '<td>'.$row["Variable_I"].'</td>';
                        echo '<td>'.$anio_emision.$mes_emision.$dia_emision.'</td>';
                        echo '<td>'.$anio_despacho.$mes_despacho.$dia_despacho.'</td>';
                        echo '<td>'.$row["Codigo_Cliente"].'</td>';
                        echo '<td>'.$row["Nombre_Cliente"].'</td>';
                        echo '<td>'.$row["Moneda"].'</td>';
                        echo '<td>'.$row["Comentarios"].'</td>';
                        echo '<td>'.$row["Nombre_Grupo"].'</td>';
                        echo '<td>'.$row["Codigo_Persona"].'</td>';
                        echo '<td style="mso-number-format:\'@\'">6</td>';
                        echo '<td>'.$anio_tax.$mes_tax.$dia_tax.'</td>';
                        echo '<td>'.$anio_u.$mes_u.$dia_u.'</td>';
                        echo '<td>'.$nit_completo.'</td>';
                        echo '<td>'.$nombre_completo.'</td>';
                        echo '<td>Ciudad</td>';
                        echo '</tr>';
                    }
                } else {
                    echo 'No hay registros';
                }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php 
// header("Location: ../index.html")
?>