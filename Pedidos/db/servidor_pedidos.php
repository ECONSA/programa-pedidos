<?php
/*********************************************
 *  servidor_pedidos.php                     *
 *  Conexión a SQL Server + MySQL            *
 *********************************************/

/* ---------- ZONA HORARIA ---------- */
date_default_timezone_set('UTC');
date_default_timezone_set('America/Guatemala');
$hoy = date('Y-m-d');

/* ---------- ERRORES ---------- */
ini_set('display_errors', 0);          // nada de HTML
ini_set('log_errors', 1);
error_reporting(E_ALL);

/* ---------- SQL SERVER (ODBC) ---------- */
$dsn      = 'Driver={ODBC Driver 17 for SQL Server};' .
            'Server=122.8.179.122,1433;' .
            'Database=UNHESA;' .
            'Encrypt=no;' .
            'TrustServerCertificate=yes';
$user     = 'sa';
$password = 'r4nD8q8GLaMk$';

$conexion_sqlsrv = @odbc_connect($dsn, $user, $password, SQL_CUR_USE_ODBC);
if ($conexion_sqlsrv === false) {
    $mensaje = 'SQL Server: ' . odbc_errormsg();
    error_log($mensaje);
    exit("Connection Failed: $mensaje");
}

/*  Alias histórico */
$conn = $conexion_sqlsrv;

/* ----------  MySQL / MariaDB  ---------- */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion_mysql = new mysqli('localhost', 'admin', '', 'facturacion');
    $conexion_mysql->set_charset('utf8mb4');
} catch (mysqli_sql_exception $ex) {
    $mensaje = 'MySQL: ' . $ex->getMessage();
    error_log($mensaje);
    exit("Connection Failed: $mensaje");
}

/*  Alias histórico */
$con = $conexion_mysql;


// ---------------- CONSULTAS ------------------- //

if (isset($_GET)) {
    if (isset($_GET["quest"])) {

        // VALIDAR USUARIO LOGUEADO
        if ($_GET["quest"] == 'usuario') {
            session_start();
            echo $_SESSION["idUsuario"];
        }

        // OBTENER NOMBRE USUARIO
        if ($_GET["quest"] == 'nombre_usuario') {
            session_start();
            echo $_SESSION["nombreUsuario"];
        }

        // OBTENER EMPRESA USUARIO
        if ($_GET["quest"] == 'empresa_usuario') {
            $sql = "SELECT * FROM usuario WHERE idUsuario = '" . $_GET["id_usuario"] . "'";
            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id_empresa' => $row["idEmpresa"]
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        if ($_GET["quest"] == 'cliente_unhesa') {
            $sql = "SELECT * FROM cliente WHERE idCliente = " . $_GET["id_cliente"];
            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'nombre' => $row["nombre"],
                        'codigo' => $row["codigo"],
                        'direccion' => $row["direccion"],
                        'id_cliente' => $row["idCliente"],
                        'id_vendedor' => $row['id_vendedor']
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        if ($_GET["quest"] == 'tipo_entrega') {
            $sql = "SELECT * FROM tipoentrega";
            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'nombre' => $row["nombre"],
                        'id_tipo' => $row["idTipoEntrega"]
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        if ($_GET["quest"] == 'obtener_correlativo') {
            $mysql_query = "SELECT MAX(correlativo)+1 as correlativo FROM pedidounhesa";
            $resultado = mysqli_query($con, $mysql_query);

            if (!$resultado) {
                die('el Query falló:' . mysqli_error($con));
            }

            if (mysqli_num_rows($resultado) > 0) {
                $json = array();
                while ($fila = mysqli_fetch_array($resultado)) {
                    $json[] = array(
                        'correlativo' => $fila["correlativo"]
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'no hay registros';
            }
        }

        if ($_GET["quest"] == 'obtener_producto') {
            $mysql_query = "SELECT * FROM producto WHERE idProducto=" . $_GET['id'];
            $resultado = mysqli_query($con, $mysql_query);

            if (!$resultado) {
                die('el Query falló:' . mysqli_error($con));
            }

            if (mysqli_num_rows($resultado) > 0) {
                $json = array();
                while ($fila = mysqli_fetch_array($resultado)) {
                    $json[] = array(
                        'idProducto' => $fila["idProducto"],
                        'nombre' => $fila["nombre"],
                        'codigo' => $fila["codigo"]
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'no hay registros';
            }
        }
    }
}

if (isset($_POST)) {
    if (isset($_POST["submit"])) {
        session_start();
        try {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            $id_pedido_unhesa = $_SESSION["ultimo_id_pedido_unhesa"];
            if ($check !== false) {
                $image = $_FILES['image']['tmp_name'];
                $imgContent = addslashes(file_get_contents($image));
                $mysqli = "UPDATE pedidounhesa SET img = '$imgContent' WHERE idPedidoUnhesa = ".$id_pedido_unhesa;
                $result = mysqli_query($con, $mysqli);

                if (!$result) {
                    die('Query Falló ' . mysqli_error($con));
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        header("Location: ../Pedido_busqueda.php");
    }
    if (isset($_POST['quest'])) {
        if ($_POST['quest'] == 'ingresar_pedido_unhesa') {
            session_start();
            $id_usuario = $_SESSION["idUsuario"];
            $direccion_entrega = $con->real_escape_string($_POST['direccion_entrega']);
            $observacion_pedido = $con->real_escape_string($_POST['observacion_pedido']);
            $observacion_adicional = $con->real_escape_string($_POST['observacion_adicional']);
            $mysql_query = "INSERT INTO pedidounhesa (correlativo, fecha_emision, fecha_despacho, direccion, observacion, telefono, hora, observacion_A, total, idVendedor, idtipoentrega, idCliente, idEstado)
            values (" . $_POST['correlativo'] . ",NOW(),'" . $_POST['fecha_despacho'] . "','" . $direccion_entrega . "','" . $observacion_pedido . "'," . $_POST['telefono_contacto'] . ",'" . $_POST['hora_entrega'] . "','" . $observacion_adicional . "'," . $_POST['total_pedido'] . "," . $id_usuario . "," . $_POST['tipo_entrega'] . "," . $_POST['id_cliente'] . ",1)";

            $result = mysqli_query($con, $mysql_query);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            } else {
                $ultimo_id = mysqli_insert_id($con);
                $_SESSION["ultimo_id_pedido_unhesa"] = $ultimo_id;
                echo 'Successfully';
            }
        }

        if ($_POST['quest'] == 'ingresar_detalle_pedido_unhesa') {
            session_start();
            $cantidad = $_POST["cantidad"];
            $precio = $_POST["precio"];
            $id_producto = $_POST["id_producto"];
            $total = ($cantidad * $precio);
            $id_pedido_unhesa = $_SESSION["ultimo_id_pedido_unhesa"];

            $mysql_query = "INSERT INTO `detallepedidounhesa`(`cantidad`, `precio`, `total`, `idPedidoUnhesa`, `idProducto`) VALUES ($cantidad, $precio, $total, $id_pedido_unhesa, $id_producto);";

            $result = mysqli_query($con, $mysql_query);

            if (!$result) {
                die('Query Falló ' . $mysql_query);
            } else {
                echo 'Successfully';
            }
        }

        if ($_POST['quest'] == 'agregar_pedido_frioso') {

            $observacion = $con -> real_escape_string($_POST['observacion']); 
            $observacion_adicional = $con -> real_escape_string($_POST['observacion_A']); 

            session_start();
            $mysql_query = "INSERT INTO pedidounhesa (correlativo, fecha_emision, fecha_despacho, direccion, observacion, telefono, hora, observacion_A, total, idVendedor, idtipoentrega, idCliente, idEstado)
            values (" . $_POST['correlativo'] . ",NOW(),'" . $_POST['fecha_despacho'] . "','" . $_POST['direccion'] . "','" . $observacion . "'," . $_POST['telefono'] . ",'" . $_POST['hora'] . "','" . $observacion_adicional . "'," . $_POST['total'] . "," . $_POST['idVendedor'] . "," . $_POST['idTipoEntrega'] . "," . $_POST['idCliente'] . ",1)";

            $result = mysqli_query($con, $mysql_query);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            } else {
                $ultimo_id = mysqli_insert_id($con);
                $_SESSION["ultimo_id_pedido_frioso"] = $ultimo_id;
                echo 'Successfully';
            }
        }

        if ($_POST['quest'] == 'agregar_detalle_pedido_frioso') {
            session_start();

            $cantidad = $_POST["cantidad"];
            $precio = $_POST["precio"];
            $id_producto = $_POST["id_producto"];

            $total = ($cantidad * $precio);
            $id_pedido_unhesa = $_SESSION["ultimo_id_pedido_frioso"];

            $mysql_query = "INSERT INTO `detallepedidounhesa`(`cantidad`, `precio`, `total`, `idPedidoUnhesa`, `idProducto`) VALUES ($cantidad, $precio, $total, $id_pedido_unhesa, $id_producto);";

            $result = mysqli_query($con, $mysql_query);

            if (!$result) {
                die('Query Falló ' . $mysql_query);
            } else {
                echo 'Successfully';
            }
        }
    }
}

 // dentro de servidor_pedidos.php, en el bloque GET:
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $quest = isset($_GET["quest"]) ? $_GET["quest"] : null;

    // Trae los montos de SAP para algunos productos en específico.
    if ($quest === 'obtener_precio_sap_casillas') {

        // 1) Leer el id de producto que viene desde JS
        $idProducto = isset($_GET['codigo']) 
            ? intval($_GET['codigo']) 
            : 0;

        // 2) Recuperar el "codigo" real desde MySQL
        $sqlProd = "
            SELECT codigo
              FROM producto
             WHERE idProducto = {$idProducto}
        ";
        $resProd = mysqli_query($con, $sqlProd);
        if (!$resProd || mysqli_num_rows($resProd) === 0) {
            // si no existe, devolvemos arreglo vacío y salimos
            echo json_encode([]);
            exit;
        }
        $rowProd = mysqli_fetch_assoc($resProd);
        $codigo  = $rowProd['codigo'];

        // 3) Armar la consulta a SQL Server sin usar OPENQUERY
        $sql = "
            SELECT
                   a.ItemCode AS 'Codigo',
                   CASE
                        WHEN (SELECT x.AvgPrice
                              FROM unhesa.dbo.OITW x
                             WHERE x.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS =
                                   a.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS
                               AND x.WhsCode = '06') = 0
                             THEN (SELECT x.AvgPrice
                                     FROM unhesa.dbo.OITW x
                                    WHERE x.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS =
                                          a.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS
                                      AND x.WhsCode = '14')

                        WHEN (SELECT x.AvgPrice
                              FROM unhesa.dbo.OITW x
                             WHERE x.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS =
                                   a.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS
                               AND x.WhsCode = '06') IS NULL
                             THEN (SELECT x.AvgPrice
                                     FROM unhesa.dbo.OITW x
                                    WHERE x.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS =
                                          a.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS
                                      AND x.WhsCode = '14')

                        WHEN (SELECT x.AvgPrice
                              FROM unhesa.dbo.OITW x
                             WHERE x.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS =
                                   a.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS
                               AND x.WhsCode = '06')
                             <
                             (SELECT x.AvgPrice
                              FROM unhesa.dbo.OITW x
                             WHERE x.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS =
                                   a.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS
                               AND x.WhsCode = '14')
                             THEN (SELECT x.AvgPrice
                                     FROM unhesa.dbo.OITW x
                                    WHERE x.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS =
                                          a.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS
                                      AND x.WhsCode = '14')

                        ELSE (SELECT x.AvgPrice
                                FROM unhesa.dbo.OITW x
                               WHERE x.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS =
                                     a.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS
                                 AND x.WhsCode = '06')
                   END AS 'CostoBodega'
              FROM unhesa.dbo.OITM a
             WHERE a.ItemCode COLLATE SQL_Latin1_General_CP1_CI_AS = '{$codigo}'
        ";

        // 4) Ejecutar en SQL Server vía ODBC
        $result = odbc_exec($conn, $sql);

        // 5) Formatear salida a JSON
        $data = [];
        if ($result) {
            while ($row = odbc_fetch_array($result)) {
                $data[] = $row;
            }
        }

        echo json_encode($data);    
        exit;
    





        $result = odbc_exec($conn, $sql);

        if ($result) {
            $data = array();
            while ($row = odbc_fetch_array($result)) {
                $data[] = $row;
            }
            echo json_encode($data);
        } else {
            header("HTTP/1.1 404 Not Found");
            echo json_encode(array("error" => "No se encontraron datos"));
        }
    }
}