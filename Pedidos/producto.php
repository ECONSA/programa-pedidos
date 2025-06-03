<?php
define ('DB_USER', "admin");
define ('DB_PASSWORD', "");
define ('DB_DATABASE', "facturacion");
define ('DB_HOST', "localhost");
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
$sql = "SELECT *,nombre, CONCAT(codigo, '-',nombre) as wea FROM Producto
        WHERE codigo LIKE '%".$_GET['q']."%'";
$result = $mysqli->query($sql);
$json = [];
while($row = $result->fetch_assoc()){
     $json[] = ['id'=>$row['idProducto'],'value'=>$row['nombre'] , 'text'=>$row['wea']];
}
echo json_encode($json);