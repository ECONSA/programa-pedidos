<?php
/*********************************************
 *  recibo.php                               *
 *  Conexión a SQL Server + MySQL            *
 *********************************************/

 date_default_timezone_set('UTC');
 date_default_timezone_set("America/Guatemala");
 $hoy = date("Y-m-d");
 
 /* ---------- SQL SERVER (ODBC) ---------- */
 $dsn      = "Driver={ODBC Driver 17 for SQL Server};Server=122.8.179.122,1433;Database=UNHESA;Encrypt=no;TrustServerCertificate=yes";
 $user     = 'sa';
 $password = 'r4nD8q8GLaMk$';
 
 $conn = odbc_connect($dsn, $user, $password);
 if (!$conn) {
     if (phpversion() < '4.0') {
         exit("Connection Failed: . $php_errormsg");
     } else {
         exit("Connection Failed:" . odbc_errormsg());
     }
 }
 
 /* ----------  MySQL / MariaDB  ---------- */
 $con = mysqli_connect("localhost", "admin", "");
 if (!$con) {
     die('Could not connect: ' . mysqli_connect_error());
 }
 mysqli_select_db($con, "facturacion");

 //$con = $conexion_mysql;


// ---------------- CONSULTAS -------------------- //

if (isset($_GET)) {
    if (isset($_GET["quest"])) {
        // NOMBRE USUARIO
        if ($_GET["quest"] == 'usuario') {
            session_start();
            echo $_SESSION["idUsuario"];
        }

        // DATOS RECIBO
        if ($_GET["quest"] == 'datos_recibo') {
            $sql = "SELECT r.id AS 'ID', u.nombre AS 'Usuario', c.nombre AS 'Cliente', c.codigo as Codigo, r.fecha AS 'Fecha', r.monto AS 'Monto', r.numero_recibo_fisico AS 'Recibo', r.banco AS 'Banco', r.numero_de_boleta AS 'Boleta', r.fecha_deposito AS 'Fecha Deposito', r.monto_del_deposito AS 'Monto Depositado' FROM recibo r INNER JOIN usuario u ON r.idUsuario = u.idUsuario INNER JOIN cliente c ON r.idCliente = c.idCliente WHERE r.id = " . $_GET["id_recibo"];
            // echo $sql;
            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id_recibo' => $row["ID"],
                        'usuario' => $row["Usuario"],
                        'cliente' => $row["Cliente"],
                        'codigo_cliente' => $row["Codigo"],
                        'fecha' => $row["Fecha"],
                        'monto' => $row["Monto"],
                        'recibo' => $row["Recibo"],
                        'banco' => $row["Banco"],
                        'boleta' => $row["Boleta"],
                        'fecha_deposito' => $row["Fecha Deposito"],
                        'monto_depositado' => $row["Monto Depositado"],
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        // DETALLES DE RECIBO
        if ($_GET["quest"] == 'detalle_recibo') {
            $sql = "SELECT * FROM `detallerecibo` WHERE idRecibo = " . $_GET["id_recibo"];
            // echo $sql;
            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id' => $row["id"],
                        'id_recibo' => $row["idRecibo"],
                        'factura' => $row["factura"],
                        'saldo_a_cobrar' => $row["saldo_a_cobrar"],
                        'abono' => $row["abono"],
                        'saldo' => $row["saldo"]
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        // DETALLE DEL RECIBO
        if ($_GET["quest"] == 'detalle_del_recibo') {
            $sql = "SELECT r.id, u.nombre as Usuario, c.nombre as Cliente, DATE(r.fecha) as Fecha, r.monto, r.numero_recibo_fisico, r.banco, r.numero_de_boleta, DATE(r.fecha_deposito) FechaDeposito, r.monto_del_deposito, r.estado, r.justificacion
             FROM `recibo` r INNER JOIN usuario u ON r.idUsuario = u.idUsuario INNER JOIN cliente c ON r.idCliente = c.idCliente WHERE r.id =" . $_GET["id_recibo"];

            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id' => $row["id"],
                        'id_usuario' => $row["Usuario"],
                        'id_cliente' => $row["Cliente"],
                        'fecha' => $row["Fecha"],
                        'monto' => $row["monto"],
                        'recibo' => $row["numero_recibo_fisico"],
                        'banco' => $row["banco"],
                        'numero_boleta' => $row["numero_de_boleta"],
                        'fecha_deposito' => $row["FechaDeposito"],
                        'monto_deposito' => $row["monto_del_deposito"],
                        'estado' => $row["estado"],
                        'justificacion' => $row["justificacion"]
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        // TRAER IMAGEN
        if ($_GET["quest"] == 'mostrar_img') {
            $mysqli = "SELECT imagen_del_deposito FROM recibo WHERE id = " . $_GET["id_imagen"];
            // echo $mysqli;
            $result = mysqli_query($con, $mysqli);

            if ($result->num_rows > 0) {
                $imgDatos = $result->fetch_assoc();

                //Mostrar Imagen
                header("Content-type: image/jpg");
                echo $imgDatos['imagen_del_deposito'];
            } else {
                echo 'Imagen no existe...';
            }
        }
    }
}

if (isset($_POST)) {
    if (isset($_POST['quest'])) {
        // ACTUALIZAR DATOS DEL BANCO EN RECIBOS
        if ($_POST["quest"] == 'cambiar_datos_banco') {
            $mysqli = "UPDATE recibo SET banco = '" . $_POST["banco_ingresado"] . "', numero_de_boleta = '" . $_POST["numero_de_boleta"] . "', fecha_deposito = '" . $_POST["fecha_de_deposito"] . "', monto_del_deposito = " . $_POST["monto_del_banco"] . ", estado = 'recibido en contabilidad' WHERE id = " . $_POST["id_recibo"] . "";
            $result = mysqli_query($con, $mysqli);
            // echo $mysqli;
            if (!$result) {
                echo 'No funciono, query: ' + $mysqli;
            }

            echo 'Successfuly';
        }
    }
}
