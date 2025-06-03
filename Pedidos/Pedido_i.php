<?php
include("../bd/inicia_conexion.php");
include("../includes/header.php");
?>
<?php
date_default_timezone_set('UTC');
date_default_timezone_set("America/Guatemala");
$hoy = date("Y") . "-" . date("m") . "-" . date("d");
$hoy_hora_mas = date("H") . ":" . date("i") . ":" . (date("s") + 5);
$hoy_hora_menos = date("H") . ":" . date("i") . ":" . (date("s") - 5);

$fecha_repetido = "SELECT DATE(fecha_emision) AS 'fecha', HOUR(fecha_emision) AS 'hora', MINUTE(fecha_emision) AS 'minuto', SECOND(fecha_emision) AS 'segundo' FROM pedidounhesa WHERE idPedidoUnhesa =( SELECT MAX(idPedidoUnhesa) FROM pedidounhesa )";
$resultado_fecha = mysqli_query($con, $fecha_repetido);

while ($fila = mysqli_fetch_array($resultado_fecha)) {
    if (
        $fila["fecha"] == $hoy && $fila["hora"] == date("H") && $fila["minuto"] == date("i")
            && ($fila["segundo"] >= (date("s") - 10) && $fila["segundo"] <= (date("s") + 10))
    ) {
        
    } else {
        $sql = "select COALESCE(MAX(correlativo), 0) as id ";
        $sql .= " from pedidounhesa";
        $resultado = mysqli_query($con, $sql);
        while ($fila = mysqli_fetch_array($resultado)) {
            $correlativo = $fila["id"] + 1;
        }

        $sql = "insert into pedidounhesa (correlativo, fecha_emision, fecha_despacho, direccion, observacion, telefono, hora, observacion_A, total, idVendedor, idtipoentrega, idCliente, idEstado) values (" . $correlativo . ", NOW()";
        $sql = $sql . ", '" . $_POST["fechaDespacho"] . "'";
        $sql = $sql . ", '" . $_POST["direccion"] . "'";
        $sql = $sql . ", '" . $_POST["observacion"] . "'";
        $sql = $sql . ", '" . $_POST["telefono"] . "'";
        $sql = $sql . ", '" . $_POST["hora"] . "'";
        $sql = $sql . ", '" . $_POST["observacionesA"] . "'";
        $sql = $sql . ", " . $_POST["total"] . "";
        $sql = $sql . ", " . $_POST["idVendedor"] . "";
        $sql = $sql . ", " . $_POST["idTipoEntrega"] . "";
        $sql = $sql . ", " . $_POST["idCliente"] . "";
        $sql = $sql . ", 1)";

        $resultado = mysqli_query($con, $sql);
        if ($resultado) {

            $sql = "select MAX(idPedidoUnhesa) as id ";
            $sql .= " from pedidounhesa";
            $resultado = mysqli_query($con, $sql);
            while ($fila = mysqli_fetch_array($resultado)) {
                $pedido = $fila["id"];
            }

            $valor = $_POST["tablita"];
            $array = json_decode($valor, true);
            for ($i = 0; $i < Count($array); $i++) {
                $sql2 = "insert into detallepedidounhesa (cantidad, precio, total, observaciones, idPedidoUnhesa, idProducto) values (";
                $sql2 = $sql2 . "" . $array[$i]["cantidad"] . "";
                $sql2 = $sql2 . ", " . $array[$i]["precio"] . "";
                $sql2 = $sql2 . ", " . $array[$i]["total"] . "";
                $sql2 = $sql2 . ", '" . $array[$i]["observacionesProducto"] . "'";
                $sql2 = $sql2 . ", " .   $pedido . "";
                $sql2 = $sql2 . ", " . $array[$i]["idProducto"] . ")";
                $resultado2 = mysqli_query($con, $sql2);
            }
            echo "funciono";
        } else {
            echo "no funciono";
        }
    }
}
?>
<?php
include("../bd/fin_conexion.php");
?>