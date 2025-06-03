<?php
include("../bd/inicia_conexion.php");
include("../includes/header.php");
?>
<?php

$sql = "select COALESCE(MAX(correlativo), 0) as id ";
$sql.= " from pedidoproquima";
$resultado = mysqli_query($con, $sql);
while ($fila = mysqli_fetch_array($resultado)) {
    $correlativo = $fila["id"] + 1;
}

$sql = "insert into pedidoproquima (correlativo, fecha_emision, fecha_despacho, direccion_factura, direccion_entrega, dias, horario, observaciones, observaciones_especiales, contacto_cliente, telefono_cliente, contacto_bodega, telefono_bodega, correo, credito, total, idVendedor, idTransporte, idClienteProquima, idEstado) values (".$correlativo.", NOW()";
$sql = $sql . ", '" . $_POST["fecha_despacho"] . "'";
$sql = $sql . ", '" . $_POST["direccion_factura"] . "'";
$sql = $sql . ", '" . $_POST["direccion_entrega"] . "'";
$sql = $sql . ", '" . $_POST["dias"] . "'";
$sql = $sql . ", '" . $_POST["horario"] . "'";
$sql = $sql . ", '" . $_POST["observacion"] . "'";
$sql = $sql . ", '" . $_POST["observacion_especial"] . "'";
$sql = $sql . ", '" . $_POST["contacto_cliente"] . "'";
$sql = $sql . ", " . $_POST["telefono_cliente"] . "";
$sql = $sql . ", '" . $_POST["contacto_bodega"] . "'";
$sql = $sql . ", " . $_POST["telefono_bodega"] . "";

$sql = $sql . ", '" . $_POST["correo"] . "'";
$sql = $sql . ", '" . $_POST["credito"] . "'";
$sql = $sql . ", " . $_POST["total"] . "";

$sql = $sql . ", " . $_POST["idVendedor"] . "";
$sql = $sql . ", " . $_POST["idTransporte"] . "";
$sql = $sql . ", " . $_POST["idCliente"] . "";
$sql = $sql . ", 1)";

$resultado = mysqli_query($con, $sql);
if ($resultado) {

    $sql = "select MAX(idPedidoProquima) as id ";
    $sql.= " from pedidoproquima";
    $resultado = mysqli_query($con, $sql);
    while ($fila = mysqli_fetch_array($resultado)) {
        $pedido = $fila["id"];
    }

    $valor = $_POST["tablita"];
    $array = json_decode($valor, true);
    for ($i=0; $i < Count($array); $i++) { 
        $sql2 = "insert into detallepedidoproquima (cantidad, precio, total, observaciones, moneda, idPedidoProquima, idProductoProquima) values (";
        $sql2 = $sql2 . "" . $array[$i]["cantidad"] . "";
        $sql2 = $sql2 . ", " . $array[$i]["precio"] . "";
        $sql2 = $sql2 . ", " . $array[$i]["total"] . "";
        $sql2 = $sql2 . ", '" . $array[$i]["observacionesProducto"] . "'";
        $sql2 = $sql2 . ", '" . $array[$i]["moneda"] . "'";
        $sql2 = $sql2 . ", " .   $pedido . "";
        $sql2 = $sql2 . ", " . $array[$i]["idProducto"] . ")";
        $resultado2 = mysqli_query($con, $sql2);
    }
    echo "funciono";
} else {
    echo "no funciono";
}
?>
<?php
include("../bd/fin_conexion.php");
?>