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
    p.idPedidoUnhesa AS 'ID_Pedido',
    pr.codigo AS 'Codigo_Producto',
    pr.nombre AS 'Nombre_Producto',
    dp.cantidad AS 'Cantidad',
    p.fecha_despacho AS 'Fecha_Despacho',
    dp.precio AS 'Precio',
    'QTZ' AS 'Moneda',
    6 AS 'Bodega',
    1 AS 'SlpCode',
    dp.total AS 'Total',
    (ROW_NUMBER() OVER (Partition By p.idPedidoUnhesa ORDER BY p.idPedidoUnhesa)-1) as contador
FROM
    detallepedidounhesa dp
INNER JOIN pedidounhesa p ON
    dp.idPedidoUnhesa = p.idPedidoUnhesa
INNER JOIN producto pr ON
    dp.idProducto = pr.idProducto
WHERE p.idVendedor = 16 AND p.total <= 700 AND p.idEstado = 1";
// 

$fecha = $_GET["fecha"];
if ($_GET["antes"] == 'si') {
    $mysql .= " AND DATE(fecha_emision) = '".$fecha."' AND TIME(fecha_emision) <= '12:00:00' ORDER BY p.idPedidoUnhesa";
}else{
    $mysql .= " AND DATE(fecha_emision) = '".$fecha."' AND TIME(fecha_emision) > '12:00:00' ORDER BY p.idPedidoUnhesa";
}

// echo $mysql;

$result = mysqli_query($con, $mysql);
// ----------------------------------------------- //

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
                <th>ParentKey</th>
                <th>LineNum</th>
                <th>ItemCode</th>
                <th>ItemDescription</th>
                <th>Quantity</th>
                <th>ShipDate</th>
                <th>Price</th>
                <th>PriceAfterVAT</th>
                <th>Currency</th>
                <th>WarehouseCode</th>
                <th>SalesPersonCode</th>
                <th>TaxTotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>DocNum</td>
                <td>LineNum</td>
                <td>ItemCode</td>
                <td>Dscription</td>
                <td>Quantity</td>
                <td>ShipDate</td>
                <td>Price</td>
                <td>PriceAfVAT</td>
                <td>Currency</td>
                <td>WhsCode</td>
                <td>SlpCode</td>
                <td>VatSum</td>
            </tr>
            <?php 
                $correlativo = 0;
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        //fechas
                        $fechas = strtotime($row["Fecha_Despacho"]);
                        $anio_fecha = date("Y", $fechas);
                        $mes_fecha = date("m", $fechas);
                        $dia_fecha = date("d", $fechas);
                        // IVA
                        $total_iva = ($row["Total"] * 0.12);
                        echo '<tr>';
                        echo '<td>'.$row["ID_Pedido"].'</td>';
                        echo '<td>'.$row["contador"].'</td>';
                        echo '<td>'.$row["Codigo_Producto"].'</td>';
                        echo '<td>'.$row["Nombre_Producto"].'</td>';
                        echo '<td>'.$row["Cantidad"].'</td>';
                        echo '<td>'.$anio_fecha.$mes_fecha.$dia_fecha.'</td>';
                        echo '<td>'.$row["Precio"].'</td>';
                        echo '<td>'.$row["Precio"].'</td>';
                        echo '<td>'.$row["Moneda"].'</td>';
                        echo '<td style="mso-number-format:\'@\'">06</td>';
                        echo '<td>'.$row["SlpCode"].'</td>';
                        echo '<td>'.$total_iva.'</td>';
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
// header("Location: ../index.html");
?>