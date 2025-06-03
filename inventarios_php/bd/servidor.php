<?php
/*********************************************
 *    Iniciamos sesión AL TOPE DEL ARCHIVO   *
 *********************************************/
session_start();  // <<--- Importante

/*---------------- CONFIGURACIÓN GENERAL ----------------*/
date_default_timezone_set('UTC');
date_default_timezone_set('America/Guatemala');
$hoy = date('Y-m-d');

/*-------------------------------------------------------
 |                 CONEXIÓN A SQL SERVER                |
 -------------------------------------------------------*/
$driver   = '{ODBC Driver 17 for SQL Server}';      // Driver ODBC instalado
$servidor = '122.8.179.122,1433';                   // IP y puerto
$basedato = 'unhesa';                               // Base de datos por defecto
$usuario  = 'sa';
$clave    = 'r4nD8q8GLaMk$';

/*
 * DSN “dsn-less” con cifrado desactivado.
 * TrustServerCertificate=yes evita el fallo de confianza TLS.
 */
$dsn_sqlsrv = sprintf(
    'Driver=%s;Server=%s;Database=%s;Encrypt=no;TrustServerCertificate=yes',
    $driver,
    $servidor,
    $basedato
);

/* Intento de conexión */
$conexion_sqlsrv = @odbc_connect($dsn_sqlsrv, $usuario, $clave, SQL_CUR_USE_ODBC);

if ($conexion_sqlsrv === false) {
    $mensaje = 'SQL Server: ' . odbc_errormsg();
    error_log($mensaje);
    $_SESSION['db_error'] = $mensaje;
    header('Location: /error_db.php');
    die();
}

/* Alias histórico para el código existente */
$conn = $conexion_sqlsrv;

/*-------------------------------------------------------
 |                CONEXIÓN A MySQL/MariaDB              |
 -------------------------------------------------------*/
$mysql_host     = 'localhost';
$mysql_usuario  = 'admin';
$mysql_clave    = '';                               // Reemplaza con tu contraseña real
$mysql_database = 'facturacion';

/* Convierte warnings en excepciones */
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion_mysql = new mysqli($mysql_host, $mysql_usuario, $mysql_clave, $mysql_database);
    $conexion_mysql->set_charset('utf8mb4');
} catch (mysqli_sql_exception $ex) {
    $mensaje = 'MySQL: ' . $ex->getMessage();
    error_log($mensaje);
    $_SESSION['db_error'] = $mensaje;
    header('Location: /error_db.php');
    die();
}

/* Alias histórico para el resto del código */
$con = $conexion_mysql;

// ---------------- CONSULTAS (GET) -------------------- //
if (isset($_GET)) {
    if (isset($_GET["quest"])) {

        // Llenar Calendario Seguimientos
        if ($_GET["quest"] == 'generar_citas') {
            $mysql_query = "
                SELECT s.id_seguimiento AS id,
                       c.nombre AS cliente,
                       e.nombre AS estado,
                       DATE_ADD(s.proximoContacto, INTERVAL 1 DAY) AS fecha
                FROM seguimiento_unhesa s
                INNER JOIN cliente c ON s.id_cliente = c.idCliente
                INNER JOIN estados_llamadas e ON s.id_status = e.id_estado
                INNER JOIN usuario u ON s.id_vendedor = u.idUsuario
                WHERE s.proximoContacto IS NOT NULL
                  AND s.proximoContacto != '000-00-00'
                  AND s.id_vendedor = " . $_GET['idUsuario'];

            $resultado = mysqli_query($con, $mysql_query);
            if (!$resultado) {
                die('el Query falló:' . $mysql_query);
            }

            if (mysqli_num_rows($resultado) > 0) {
                $json = array();
                while ($fila = mysqli_fetch_array($resultado)) {
                    $json[] = array(
                        'id'      => $fila['id'],
                        'cliente' => $fila['cliente'],
                        'estado'  => $fila['estado'],
                        'fecha'   => $fila['fecha'],
                    );
                }
                echo json_encode($json);
            } else {
                echo 'no hay registros';
            }
        }

        // ESTADO SEGUIMIENTO
        if ($_GET["quest"] == 'estado_seguimiento') {
            $sql = "SELECT * FROM estados_llamadas";
            $result = mysqli_query($con, $sql);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id'     => $row["id_estado"],
                        'nombre' => $row["nombre"],
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // DETALLE CITA SEGUIMIENTO
        if ($_GET["quest"] == 'obtener_cita') {
            $mysql_query = "
                SELECT s.id_seguimiento AS id,
                       u.nombre AS vendedor,
                       s.observaciones AS descripcion,
                       c.nombre AS cliente,
                       e.nombre AS estado,
                       s.proximoContacto AS fecha,
                       s.fecha AS creacion
                FROM seguimiento_unhesa s
                INNER JOIN cliente c ON s.id_cliente = c.idCliente
                INNER JOIN estados_llamadas e ON s.id_status = e.id_estado
                INNER JOIN usuario u ON s.id_vendedor = u.idUsuario
                WHERE s.id_seguimiento = " . $_GET['idCita'];

            $resultado = mysqli_query($con, $mysql_query);
            if (!$resultado) {
                die('el Query falló:' . mysqli_error($con));
            }

            if (mysqli_num_rows($resultado) > 0) {
                $json = array();
                while ($fila = mysqli_fetch_array($resultado)) {
                    $json[] = array(
                        'id'          => $fila['id'],
                        'descripcion' => $fila['descripcion'],
                        'vendedor'    => $fila['vendedor'],
                        'cliente'     => $fila['cliente'],
                        'estado'      => $fila['estado'],
                        'fecha'       => $fila['fecha'],
                        'creacion'    => $fila['creacion']
                    );
                }
                echo json_encode($json);
            } else {
                echo 'no hay registros';
            }
        }

        // ULTIMO ID PEDIDO
        if ($_GET["quest"] == 'ultimo_id_pedido') {
            // Nota: aquí ya EXISTE session_start() arriba
            $vendedor = isset($_SESSION["idUsuario"]) ? $_SESSION["idUsuario"] : 0;
            $mysql_query = "
                SELECT p.idPedidoUnhesa as pedido,
                       c.nombre as cliente
                FROM pedidounhesa p
                INNER JOIN cliente c ON p.idCliente = c.idCliente
                WHERE p.idCliente = " . $_GET['cliente'] . "
                  AND p.idVendedor = " . $vendedor . "
                ORDER BY p.fecha_emision DESC
                LIMIT 1";
            $resultado = mysqli_query($con, $mysql_query);

            if (!$resultado) {
                die('el Query falló:' . mysqli_error($con));
            }

            if (mysqli_num_rows($resultado) > 0) {
                $json = array();
                while ($fila = mysqli_fetch_array($resultado)) {
                    $json[] = array(
                        'pedido'  => $fila['pedido'],
                        'cliente' => $fila['cliente'],
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // TEST SQL SERVER
        if ($_GET["quest"] == 'test') {
            $sql = "SELECT * FROM facturas";
            $result = odbc_exec($conn, $sql);
            if (!$result) {
                die('Query Falló TEST SQL SERVER ' . odbc_error($conn));
            }

            if (odbc_num_rows($result) > 0) {
                $json = array();
                while ($row = odbc_fetch_array($result)) {
                    $json[] = array(
                        'id'     => $row["id"],
                        'factura'=> $row["factura"],
                        'saldo'  => $row["saldo"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // NOMBRE CLIENTE SQL SERVER
        if ($_GET["quest"] == 'nombre_cliente') {
            $sql = "SELECT * FROM cliente WHERE idCliente = '" . $_GET["codigo"] . "'";
            $result = mysqli_query($con, $sql);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id'     => $row["idCliente"],
                        'nombre' => $row["nombre"],
                        'codigo' => $row["codigo"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // EMPRESA USUARIO
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
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // ÚLTIMO RECIBO GUARDADO
        if ($_GET["quest"] == 'ultimo_recibo') {
            $sql = "SELECT MAX(id) AS id FROM recibo";
            $result = mysqli_query($con, $sql);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id_recibo' => $row["id"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // DETALLES DE RECIBO
        if ($_GET["quest"] == 'detalle_recibo') {
            $sql = "SELECT * FROM detallerecibo WHERE idRecibo = " . $_GET["id_recibo"];
            $result = mysqli_query($con, $sql);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id'             => $row["id"],
                        'id_recibo'      => $row["idRecibo"],
                        'factura'        => $row["factura"],
                        'saldo_a_cobrar' => $row["saldo_a_cobrar"],
                        'abono'          => $row["abono"],
                        'saldo'          => $row["saldo"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // FACTURAS CLIENTE (SQL Server)
        if ($_GET['quest'] === 'facturas_cliente') {

            // ⚠️ Valida antes que 'codigo' llegue.
            $codigo = $_GET['codigo'] ?? '';
            if ($codigo === '') {
                exit('Falta el parámetro codigo');
            }
        
            $sql = "
                SELECT TOP 100
                       a.CardCode     AS CodigoCliente,
                       a.CardName     AS NomCliente,
                       a.DocNum       AS NumFactura,
                       CONVERT(date, a.DocDate)            AS FechaFactura,
                       a.DocTotal     AS TotalFactura,
                       (a.DocTotal - a.PaidToDate)         AS SaldoFactura,
                       CASE
                           WHEN DATEDIFF(day, a.DocDueDate, GETDATE()) <= 0
                                THEN CONCAT(-DATEDIFF(day, a.DocDueDate, GETDATE()), ' Días Por vencer')
                           ELSE CONCAT(DATEDIFF(day, a.DocDueDate, GETDATE()), ' Días Vencidos')
                       END                                   AS Antiguedad
                FROM unhesa.dbo.oinv a
                WHERE (a.DocTotal - a.PaidToDate) > 0
                  AND a.CardCode = ?";          // ← parámetro
        
            $stmt = odbc_prepare($conn, $sql);
            if (!$stmt) {
                exit('Error prepare: ' . odbc_errormsg($conn));
            }
        
            if (!odbc_execute($stmt, [$codigo])) {
                exit('Error execute: ' . odbc_errormsg($conn));
            }
        
            $json = [];
            while ($row = odbc_fetch_array($stmt)) {
                $json[] = [
                    'factura'       => $row['NumFactura'],
                    'monto'         => floatval($row['TotalFactura']),
                    'saldo'         => floatval($row['SaldoFactura']),
                    'codigo'        => $row['CodigoCliente'],
                    'fecha_factura' => $row['FechaFactura'],
                    'antiguedad'    => $row['Antiguedad']
                ];
            }
        
            echo json_encode($json, JSON_UNESCAPED_UNICODE);   // $json será [] si está vacío
        }
        

        // NOMBRE USUARIO
        if ($_GET["quest"] == 'usuario') {
            // Devolvemos el idUsuario que esté en $_SESSION
            if (isset($_SESSION["idUsuario"])) {
                echo $_SESSION["idUsuario"];
            } else {
                // si no existe, devolvemos vacío (o un string que cause isNaN en front)
                echo '';
            }
        }

        // NOMBRE USUARIO
        if ($_GET["quest"] == 'nombre_usuario') {
            if (isset($_SESSION["nombreUsuario"])) {
                echo $_SESSION["nombreUsuario"];
            } else {
                echo '';
            }
        }

        // LISTA DE RECIBOS
        if ($_GET["quest"] == 'recibos') {
            $sql = "
                SELECT r.id,
                       u.nombre as 'idUsuario',
                       c.nombre as 'idCliente',
                       DATE(r.fecha) fecha,
                       r.monto,
                       r.numero_recibo_fisico,
                       r.banco,
                       r.estado
                FROM recibo r
                INNER JOIN usuario u ON r.idUsuario = u.idUsuario
                INNER JOIN cliente c ON r.idCliente = c.idCliente
                WHERE r.idUsuario = " . $_GET["id_usuario"] . "
                ORDER BY r.id DESC";

            $result = mysqli_query($con, $sql);
            if (!$result) {
                echo $sql;
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id'         => $row["id"],
                        'id_usuario' => $row["idUsuario"],
                        'id_cliente' => $row["idCliente"],
                        'fecha'      => $row["fecha"],
                        'monto'      => $row["monto"],
                        'recibo'     => $row["numero_recibo_fisico"],
                        'banco'      => $row["banco"],
                        'estado'     => $row["estado"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // LISTA DE RECIBOS FILTRO
        if ($_GET["quest"] == 'recibos_filtro') {
            $sql = "
                SELECT r.id, u.nombre AS 'idUsuario', c.nombre AS 'idCliente',
                       DATE(r.fecha) AS fecha, r.monto, r.numero_recibo_fisico,
                       r.banco, r.estado
                FROM recibo r
                INNER JOIN usuario u ON r.idUsuario = u.idUsuario
                INNER JOIN cliente c ON r.idCliente = c.idCliente
                WHERE (r.id LIKE '%" . $_GET["palabra"] . "%'
                       OR u.nombre LIKE '%" . $_GET["palabra"] . "%'
                       OR c.nombre LIKE '%" . $_GET["palabra"] . "%'
                       OR DATE(r.fecha) LIKE '%" . $_GET["palabra"] . "%'
                       OR r.monto LIKE '%" . $_GET["palabra"] . "%'
                       OR r.numero_recibo_fisico LIKE '%" . $_GET["palabra"] . "%'
                       OR r.estado LIKE '%" . $_GET["palabra"] . "%')
                  AND r.idUsuario = " . $_GET["id_usuario"] . "
                ORDER BY r.id DESC";
            $result = mysqli_query($con, $sql);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id'         => $row["id"],
                        'id_usuario' => $row["idUsuario"],
                        'id_cliente' => $row["idCliente"],
                        'fecha'      => $row["fecha"],
                        'monto'      => $row["monto"],
                        'recibo'     => $row["numero_recibo_fisico"],
                        'banco'      => $row["banco"],
                        'estado'     => $row["estado"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // DATOS RECIBO
        if ($_GET["quest"] == 'datos_recibo') {
            $sql = "
            SELECT r.id AS 'ID',
                   u.nombre AS 'Usuario',
                   c.nombre AS 'Cliente',
                   c.codigo as Codigo,
                   r.fecha AS 'Fecha',
                   r.monto AS 'Monto',
                   r.numero_recibo_fisico AS 'Recibo',
                   r.banco AS 'Banco',
                   r.numero_de_boleta AS 'Boleta',
                   r.fecha_deposito AS 'Fecha Deposito',
                   r.monto_del_deposito AS 'Monto Depositado',
                   r.estado AS 'estado'
            FROM recibo r
            INNER JOIN usuario u ON r.idUsuario = u.idUsuario
            INNER JOIN cliente c ON r.idCliente = c.idCliente
            WHERE r.id = " . $_GET["id_recibo"];
            $result = mysqli_query($con, $sql);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id_recibo'         => $row["ID"],
                        'usuario'           => $row["Usuario"],
                        'cliente'           => $row["Cliente"],
                        'codigo_cliente'    => $row["Codigo"],
                        'fecha'             => $row["Fecha"],
                        'monto'             => $row["Monto"],
                        'recibo'            => $row["Recibo"],
                        'banco'             => $row["Banco"],
                        'boleta'            => $row["Boleta"],
                        'fecha_deposito'    => $row["Fecha Deposito"],
                        'monto_depositado'  => $row["Monto Depositado"],
                        'estado'            => $row["estado"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // DETALLE DEL RECIBO
        if ($_GET["quest"] == 'detalle_del_recibo') {
            $sql = "
                SELECT r.id,
                       u.nombre as Usuario,
                       c.nombre as Cliente,
                       DATE(r.fecha) as Fecha,
                       r.monto,
                       r.numero_recibo_fisico,
                       r.banco,
                       r.numero_de_boleta,
                       DATE(r.fecha_deposito) FechaDeposito,
                       r.monto_del_deposito,
                       r.estado,
                       r.justificacion
                FROM recibo r
                INNER JOIN usuario u ON r.idUsuario = u.idUsuario
                INNER JOIN cliente c ON r.idCliente = c.idCliente
                WHERE r.id = " . $_GET["id_recibo"];

            $result = mysqli_query($con, $sql);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id'             => $row["id"],
                        'id_usuario'     => $row["Usuario"],
                        'id_cliente'     => $row["Cliente"],
                        'fecha'          => $row["Fecha"],
                        'monto'          => $row["monto"],
                        'recibo'         => $row["numero_recibo_fisico"],
                        'banco'          => $row["banco"],
                        'numero_boleta'  => $row["numero_de_boleta"],
                        'fecha_deposito' => $row["FechaDeposito"],
                        'monto_deposito' => $row["monto_del_deposito"],
                        'estado'         => $row["estado"],
                        'justificacion'  => $row["justificacion"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // TRAER IMAGEN RECIBO
        if ($_GET["quest"] == 'mostrar_img') {
            $mysqli = "SELECT imagen_del_deposito FROM recibo WHERE id = " . $_GET["id_imagen"];
            $result = mysqli_query($con, $mysqli);
            if ($result && $result->num_rows > 0) {
                $imgDatos = $result->fetch_assoc();
                header("Content-type: image/jpg");
                echo $imgDatos['imagen_del_deposito'];
            } else {
                echo 'Imagen no existe...';
            }
        }

        // TRAER IMAGEN PEDIDO
        if ($_GET["quest"] == 'mostrar_img_pedido') {
            // Nota: la sesión ya está iniciada arriba
            if (!isset($_SESSION["id_del_pedido"])) {
                echo 'Imagen no existe...';
            } else {
                $mysqli = "SELECT img FROM pedidounhesa WHERE idPedidoUnhesa = " . $_SESSION["id_del_pedido"];
                $result = mysqli_query($con, $mysqli);
                if ($result && $result->num_rows > 0) {
                    $imgDatos = $result->fetch_assoc();
                    header("Content-type: image/jpg");
                    echo $imgDatos['img'];
                } else {
                    echo 'Imagen no existe...';
                }
            }
        }

        // OBTENER CLIENTES ONLINE
        if ($_GET["quest"] == 'ver_clientes_online') {
            // la sesión ya existe
            $mysqli = "SELECT * FROM clientes_online";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }
            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id'        => $row["id"],
                        'nombre'    => $row["nombre"],
                        'direccion' => $row["direccion"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // BUSCAR CLIENTES ONLINE
        if ($_GET["quest"] == 'buscar_clientes_online') {
            $mysqli = "SELECT * FROM clientes_online WHERE nombre = '" . $_GET["nombre"] . "'";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }
            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id'        => $row["id"],
                        'nombre'    => $row["nombre"],
                        'direccion' => $row["direccion"],
                        'telefono'  => $row["telefono"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }


        if ($_GET["quest"] == 'ver_productos_pedidos') {
    $id_pedido_online = $_GET["id_pedido_online"];
    $sql = "SELECT pp.*, p.nombre AS nombre_producto
            FROM productos_pedidos pp
            INNER JOIN producto p ON pp.id_producto = p.idProducto
            WHERE pp.id_pedido_online = $id_pedido_online";
    $result = mysqli_query($con, $sql);
    if (!$result) {
        die('Query Falló ' . mysqli_error($con));
    }
    if (mysqli_num_rows($result) > 0) {
        $json = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $json[] = array(
                'id'          => $row['id'],
                'id_producto' => $row['id_producto'],
                'nombre'      => $row['nombre_producto'],
                'precio'      => $row['precio'],
                'cantidad'    => $row['cantidad'],
                'total'       => $row['total']
            );
        }
        echo json_encode($json);
    } else {
        echo 'No';
    }
}


        // OBTENER LISTA PEDIDOS ONLINE
if ($_GET["quest"] == 'lista_online') {

    // 1) Recibimos fechas (o forzamos un rango amplio para incluir pedidos viejos)
    $fechaMin = isset($_GET["minimo"]) ? $_GET["minimo"] : '2000-01-01';
    $fechaMax = isset($_GET["maxima"]) ? $_GET["maxima"] : '2100-12-31';

    // 2) Construimos la consulta con JOIN a productos_pedidos y producto
    $sql = "
    SELECT
        p.id                       AS id_pedido,
        p.nombre                   AS nombre_pedido,
        p.direccion                AS direccion_pedido,
        p.departamento             AS departamento_pedido,
        p.municipio                AS municipio_pedido,
        p.telefono                 AS telefono_pedido,
        p.nombre_factura           AS nombre_factura,
        p.nit                      AS nit,
        p.stickers                 AS stickers,
        p.servicio                 AS servicio,
        p.fecha_generado           AS fecha_generado,
        p.fecha_entrega            AS fecha_entrega,
        c.nombre                   AS cliente_sap,
        co.nombre                  AS cliente_online,
        p.estado                   AS estado,
        COALESCE(p.fidelizacion,'')   AS fidelizacion,
        COALESCE(p.tipo_cliente,'')   AS tipo_cliente,
        COALESCE(p.como_se_entero,'') AS como_se_entero,

        -- Campos agrupados:
        GROUP_CONCAT(pr.nombre SEPARATOR ' --- ')   AS productos,
        GROUP_CONCAT(pp.precio SEPARATOR ' --- ')   AS precios,
        GROUP_CONCAT(pp.cantidad SEPARATOR ' --- ') AS cantidades,
        SUM(pp.precio * pp.cantidad)               AS total_pedido

    FROM pedido_online p
    LEFT JOIN productos_pedidos pp ON p.id = pp.id_pedido_online
    LEFT JOIN producto pr ON pp.id_producto = pr.idProducto

    LEFT JOIN cliente c            ON p.id_cliente_sap   = c.idCliente
    LEFT JOIN clientes_online co   ON p.id_cliente_online= co.id

    WHERE (p.fecha_generado >= '$fechaMin')
    AND (p.fecha_generado <= '$fechaMax')

    GROUP BY p.id
    ORDER BY p.fecha_generado DESC
    ";

    $result = mysqli_query($con, $sql);
    if (!$result) {
        die('Query Falló: ' . mysqli_error($con) . ' SQL: ' . $sql);
    }

    // 4) Retornamos JSON
    if (mysqli_num_rows($result) > 0) {
        $json = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $json[] = array(
                // Datos del pedido
                'id_pedido'         => $row['id_pedido'],
                'nombre_pedido'     => $row['nombre_pedido'],
                'direccion_pedido'  => $row['direccion_pedido'],
                'departamento_pedido'=> $row['departamento_pedido'],
                'municipio_pedido'  => $row['municipio_pedido'],
                'telefono_pedido'   => $row['telefono_pedido'],
                'nombre_factura'    => $row['nombre_factura'],
                'nit'               => $row['nit'],
                'stickers'          => $row['stickers'],
                'servicio'          => $row['servicio'],
                'fecha_generado'    => $row['fecha_generado'],
                'fecha_entrega'     => $row['fecha_entrega'],
                'cliente_sap'       => $row['cliente_sap'],
                'estado'            => $row['estado'],
                'fidelizacion'      => $row['fidelizacion'],
                'tipo_cliente'      => $row['tipo_cliente'],
                'como_se_entero'    => $row['como_se_entero'],

                // Agrupaciones:
                'productos'         => $row['productos'],   // "Fri-Oso..., Vitamina C..., etc."
                'precios'           => $row['precios'],     // "105.00 --- 270.00 --- 80.00..."
                'cantidades'        => $row['cantidades'],  // "1 --- 2 --- 1..."
                'total_pedido'      => $row['total_pedido'] // 1195, 250, etc.
            );
        }
        echo json_encode($json);
    } else {
        echo 'No';
    }
}




// ...

        // <--- Quitamos aquí la llave extra (ANTES estaba un '}' que cerraba todo)
        // OBTENER PEDIDO ONLINE
        if ($_GET["quest"] == 'ver_pedido_online') {
            $mysqli = "
            SELECT p.id AS 'ID_PEDIDO',
                   p.nombre AS 'NOMBRE_PEDIDO',
                   p.direccion AS 'DIRECCION_PEDIDO',
                   p.departamento AS 'DEPARTAMENTO_PEDIDO',
                   p.municipio AS 'MUNICIPIO_PEDIDO',
                   p.telefono AS 'TELEFONO_PEDIDO',
                   p.nombre_factura AS 'NOMBRE_FACTURA',
                   p.nit AS 'NIT',
                   p.stickers AS 'STICKERS',
                   p.servicio AS 'SERVICIO',
                   p.observaciones AS 'OBSERVACIONES',
                   DATE(p.fecha_entrega) AS 'FECHA_ENTREGA',
                   c.nombre AS 'CLIENTE_SAP',
                   co.nombre AS 'CLIENTE_ONLINE',
                   p.estado AS 'ESTADO'
            FROM pedido_online p
            INNER JOIN cliente c ON p.id_cliente_sap = c.idCliente
            INNER JOIN clientes_online co ON p.id_cliente_online = co.id
            WHERE p.id = " . $_GET["id_pedido"];

            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id'           => $row["ID_PEDIDO"],
                        'nombre'       => $row["NOMBRE_PEDIDO"],
                        'direccion'    => $row["DIRECCION_PEDIDO"],
                        'departamento' => $row["DEPARTAMENTO_PEDIDO"],
                        'municipio'    => $row["MUNICIPIO_PEDIDO"],
                        'telefono'     => $row["TELEFONO_PEDIDO"],
                        'nombre_factura'=> $row["NOMBRE_FACTURA"],
                        'nit'          => $row["NIT"],
                        'stickers'     => $row["STICKERS"],
                        'servicio'     => $row["SERVICIO"],
                        'observaciones'=> $row["OBSERVACIONES"],
                        'fecha_entrega'=> $row["FECHA_ENTREGA"],
                        'cliente_sap'  => $row["CLIENTE_SAP"],
                        'cliente_online'=> $row["CLIENTE_ONLINE"],
                        'estado'       => $row["ESTADO"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // OBTENER DETALLE PEDIDO ONLINE
        if ($_GET["quest"] == 'ver_detalle_online') {
            $mysqli = "
            SELECT d.id AS 'ID',
                   CONCAT(p.codigo, '-', p.nombre) AS 'Producto',
                   d.precio AS 'Precio',
                   d.cantidad as 'Canidad',
                   (d.precio * d.cantidad) AS 'Total'
            FROM detalle_pedido_online d
            INNER JOIN producto p ON d.id_producto = p.idProducto
            WHERE d.id_pedido_online = " . $_GET["id_pedido"];

            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id'       => $row["ID"],
                        'producto' => $row["Producto"],
                        'precio'   => $row["Precio"],
                        'cantidad' => $row["Canidad"],
                        'total'    => $row["Total"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // PEDIDOS DUPLICADOS
        if ($_GET["quest"] == 'pedidos_duplicados_jonathan') {
            $mysqli = "
            SELECT CONCAT(DATE(fecha), ' ', HOUR(fecha), ':', MINUTE(fecha)) AS 'fecha'
            FROM recibo
            WHERE monto = " . $_GET["saldo_a_cobrar"] . "
              AND idCliente = " . $_GET["codigo"] . "
              AND CONCAT(DATE(fecha), ' ', HOUR(fecha), ':', MINUTE(fecha)) = '" . $hoy . " " . date('H') . ":" . date('i') . "'";

            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id' => $row["ID"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // OBTENER PEDIDO ONLINE (datos_pedido_online)
        if ($_GET["quest"] == 'datos_pedido_online') {
            $id_pedido_online = $_GET["id_pedido_online"];
            $mysqli = "
            SELECT po.id,
                   po.nombre,
                   po.direccion,
                   po.departamento,
                   po.municipio,
                   po.telefono,
                   po.nombre_factura,
                   po.nit,
                   po.stickers,
                   po.servicio,
                   po.observaciones,
                   DATE(po.fecha_entrega) fecha_entrega,
                   po.id_cliente_online
            FROM pedido_online po
            WHERE po.id = $id_pedido_online";

            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id'                => $row["id"],
                        'nombre'            => $row["nombre"],
                        'direccion'         => $row["direccion"],
                        'departamento'      => $row["departamento"],
                        'municipio'         => $row["municipio"],
                        'telefono'          => $row["telefono"],
                        'nombre_factura'    => $row["nombre_factura"],
                        'nit'               => $row["nit"],
                        'stickers'          => $row["stickers"],
                        'servicio'          => $row["servicio"],
                        'observaciones'     => $row["observaciones"],
                        'fecha_entrega'     => $row["fecha_entrega"],
                        'id_cliente_online' => $row["id_cliente_online"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

        // OBTENER DETALLE PEDIDO ONLINE (datos_detalle_pedido_online)
        if ($_GET["quest"] == 'datos_detalle_pedido_online') {
            $id_pedido_online = $_GET["id_pedido_online"];
            $mysqli = "
            SELECT dp.id, pp.nombre producto, dp.precio, dp.cantidad, pp.idProducto id_producto
            FROM detalle_pedido_online dp
            INNER JOIN producto pp ON dp.id_producto = pp.idProducto
            WHERE dp.id_pedido_online = $id_pedido_online";

            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id'         => $row["id"],
                        'producto'   => $row["producto"],
                        'cantidad'   => $row["cantidad"],
                        'precio'     => $row["precio"],
                        'id_producto'=> $row["id_producto"]
                    );
                }
                echo json_encode($json);
            } else {
                echo 'No';
            }
        }

    } // <-- Cierra if (isset($_GET["quest"]))
} // <-- Cierra if (isset($_GET))


// ------------------------------ * POST * ---------------------------------- //
if (isset($_POST)) {
    if (isset($_POST["submit"])) {
        // (Subida de imágenes, etc.)
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $image = $_FILES['image']['tmp_name'];
            $imgContent = addslashes(file_get_contents($image));
            $mysqli = "UPDATE recibo SET imagen_del_deposito = '$imgContent' WHERE id = (SELECT MAX(id) FROM `recibo`)";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }
        }
        header("Location: ../recibo_digital.html");
    }

    if (isset($_POST["foto_pedido"])) {
        $check = getimagesize($_FILES["imagen"]["tmp_name"]);
        if ($check !== false) {
            $image = $_FILES['imagen']['tmp_name'];
            $imgContent = addslashes(file_get_contents($image));
            $mysqli = "UPDATE pedidounhesa SET img = '$imgContent' WHERE idPedidoUnhesa = (SELECT MAX(idPedidoUnhesa) FROM pedidounhesa)";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            } else {
                echo 'success';
            }
        }
        header("Location: ../../Pedidos/ListPedidos.php?variable=1");
    }

    if (isset($_POST["guardar_foto"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $image = $_FILES['image']['tmp_name'];
            $imgContent = addslashes(file_get_contents($image));
            $mysqli = "INSERT INTO imagen(img) VALUES('$imgContent')";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            } else {
                echo 'Éxito';
            }
        }
    }

    if (isset($_POST["update_img"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $image = $_FILES['image']['tmp_name'];
            $imgContent = addslashes(file_get_contents($image));
            $mysqli = "
            UPDATE imagenes
            SET imagen = '$imgContent',
                descripcion = '" . $_POST["descripcion"] . "'
            WHERE id = " . $_POST["id_imagen"];
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }
            echo 'Update';
        }
        header("Location: ../imagenes.html");
    }

    // ACTUALIZA LA FOTO DE EL DEPOSITO BANCARIO DE UN RECIBO
    if (isset($_POST["update_picture"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $image = $_FILES['image']['tmp_name'];
            $imgContent = addslashes(file_get_contents($image));
            $mysqli = "UPDATE recibo SET imagen_del_deposito = '$imgContent' WHERE id = " . $_POST["id_recibo_foto"];
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }
            echo 'Update';
        }
        header("Location: ../detalle_recibo.html");
    }

    if (isset($_POST["quest"])) {

        // RECONTEOS
        if ($_POST["quest"] == 'recontar') {
            $mysqli = "UPDATE conteos SET idEstado = 3 WHERE idConteo = " . $_POST["id"];
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo 'No';
            }
            echo 'Successfuly';
        }

        // INGRESAR RECIBO
        if ($_POST["quest"] == 'ingresar_recibo') {
            // la sesión ya está iniciada
            $id_usuario = isset($_POST["id_usuario"]) ? $_POST["id_usuario"] : 0;
            $id_cliente = isset($_POST["id_cliente"]) ? $_POST["id_cliente"] : 0;
            $monto      = isset($_POST["monto"]) ? $_POST["monto"] : 0;
            $mysqli = "
            INSERT INTO recibo (idUsuario, idCliente, fecha, monto,
                                numero_recibo_fisico, banco, numero_de_boleta,
                                fecha_deposito, monto_del_deposito, estado)
            VALUES(
               $id_usuario,
               $id_cliente,
               CURRENT_TIME(),
               $monto,
               '" . $_POST["recibo"] . "',
               '" . $_POST["banco"] . "',
               '" . $_POST["numero_de_boleta"] . "',
               '" . $_POST["fecha"] . "',
               $monto,
               '" . $_POST["estado"] . "'
            )";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo 'No funciono, query: ' . $mysqli;
            } else {
                $ultimo_id = mysqli_insert_id($con);
                $_SESSION["ultimo_id_recibo"] = $ultimo_id;
                echo 'Successfuly';
            }
        }

        // ACTUALIZAR DATOS DEL BANCO EN RECIBOS
        if ($_POST["quest"] == 'cambiar_datos_banco') {
            $mysqli = "
            UPDATE recibo
            SET banco = '" . $_POST["banco_ingresado"] . "',
                numero_de_boleta = '" . $_POST["numero_de_boleta"] . "',
                fecha_deposito = '" . $_POST["fecha_de_deposito"] . "',
                monto_del_deposito = " . $_POST["monto_del_banco"] . ",
                estado = 'recibido en contabilidad'
            WHERE id = " . $_POST["id_recibo"];
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo 'No funciono, query: ' . $mysqli;
            }
            echo 'Successfuly';
        }

        // INGRESAR DETALLE RECIBO
        if ($_POST["quest"] == 'ingresar_detalle_recibo') {
            if (!isset($_SESSION['ultimo_id_recibo'])) {
                echo 'No';
                exit;
            }
            $id_recibo = $_SESSION['ultimo_id_recibo'];
            $mysqli = "
            INSERT INTO detallerecibo(idRecibo, factura, saldo_a_cobrar, abono, saldo)
            VALUES(
              $id_recibo,
              '" . $_POST["factura"] . "',
               " . $_POST["saldo_a_cobrar"] . ",
               " . $_POST["abono"] . ",
               " . $_POST["saldo"] . "
            )";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo $mysqli;
            } else {
                echo $id_recibo;
            }
        }

        // ENVIAR MENSAJE POR CORREO
        if ($_POST['quest'] == 'Enviar') {
            if (mail('jguerra.grupoeconsa@gmail.com', 'Test', 'Hola Mundo')) {
                echo "<script language='javascript'>
                        alert('Mensaje enviado, muchas gracias por contactar con nosotros.');
                      </script>";
            } else {
                echo 'Falló el envio';
            }
        }

        // ACTUALIZAR ESTADO DEL RECIBO
        if ($_POST["quest"] == 'cambiar_estado') {
            $sql = "UPDATE recibo SET estado = '" . $_POST["estado"] . "' WHERE id = " . $_POST["id_recibo"];
            $result = mysqli_query($con, $sql);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            } else {
                echo 'Successfully';
            }
        }

        // INGRESAR CLIENTE ONLINE
        if ($_POST["quest"] == 'ingresar_cliente_online') {
            $mysqli = "
            INSERT INTO clientes_online(nombre, direccion, telefono, fecha_generado, fecha_actualizado, id_vendedor)
            VALUES(
              '" . $_POST["nombre"] . "',
              '" . $_POST["direccion"] . "',
              '" . $_POST["telefono"] . "',
               NOW(),
               NOW(),
               16
            )";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo $mysqli;
            } else {
                echo 'Successfuly';
            }
        }

        // ============== INSERTAR PEDIDO ONLINE (CON CAMPOS EXTRAS) ==============
        if ($_POST["quest"] == 'insertar_pedido_online') {
            // la sesión ya existe
            $departamento = mysqli_real_escape_string($con, $_POST["departamento"]);
            $municipio   = mysqli_real_escape_string($con, $_POST["municipio"]);

            // NUEVOS CAMPOS (fidelizacion, tipo_cliente, como_se_entero)
            $fidelizacion   = mysqli_real_escape_string($con, $_POST["fidelizacion"]);
            $tipo_cliente   = mysqli_real_escape_string($con, $_POST["tipo_cliente"]);
            $como_se_entero = mysqli_real_escape_string($con, $_POST["como_se_entero"]);

            $sql = "
            INSERT INTO pedido_online(
              estado,
              id_vendedor,
              nombre,
              direccion,
              departamento,
              municipio,
              telefono,
              nombre_factura,
              nit,
              stickers,
              servicio,
              observaciones,
              fecha_entrega,
              fecha_generado,
              id_cliente_sap,
              id_cliente_online,
              fidelizacion,
              tipo_cliente,
              como_se_entero
            ) VALUES (
              'solicitado',
              " . $_POST["idUsuario"] . ",
              '" . $_POST["nombre"] . "',
              '" . $_POST["direccion"] . "',
              '" . $departamento . "',
              '" . $municipio . "',
              '" . $_POST["telefono"] . "',
              '" . $_POST["nombre_factura"] . "',
              '" . $_POST["nit"] . "',
              '" . $_POST["sticker"] . "',
              '" . $_POST["servicio"] . "',
              '" . $_POST["observacion"] . "',
              '" . $_POST["fecha_entrega"] . "',
              NOW(),
              " . $_POST["id_cliente_sap"] . ", ";

            // Manejo de id_cliente
            if ($_POST["id_cliente"] == '') {
                $sql .= "(SELECT MAX(id) FROM clientes_online), ";
            } else {
                $sql .= $_POST["id_cliente"] . ", ";
            }

            // Campos nuevos
            $sql .= "
              '" . $fidelizacion . "',
              '" . $tipo_cliente . "',
              '" . $como_se_entero . "'
            )";

            $result = mysqli_query($con, $sql);
            if (!$result) {
                die('Query Falló ' . mysqli_error($con) . ' -- ' . $sql);
            } else {
                $ultimo_id = mysqli_insert_id($con);
                $_SESSION["ultimo_id_pedido_online"] = $ultimo_id;
                echo 'Successfully';
            }
        }

        //INGRESAR DETALLE PEDIDO ONLINE
            if ($_POST["quest"] == 'insertar_detalle_pedido_online') {
                if (!isset($_SESSION["ultimo_id_pedido_online"])) {
                echo 'No';
                exit;
            }
            $id_pedido_online = $_SESSION["ultimo_id_pedido_online"];
            $id_producto = $_POST["id_producto"];
            $precio = $_POST["precio"];
            $cantidad = $_POST["cantidad"];

            // 1) Insertar en la tabla "detalle_pedido_online"
            $mysqli1 = "
            INSERT INTO detalle_pedido_online(id_producto, precio, cantidad, id_pedido_online)
            VALUES($id_producto, $precio, $cantidad, $id_pedido_online)
            ";
            $res1 = mysqli_query($con, $mysqli1);

            if (!$res1) {
                echo $mysqli1;
                exit;
            }

            // 2) Calcular el total e insertar en la tabla "productos_pedidos"
            $total = $precio * $cantidad;
            $mysqli2 = "
            INSERT INTO productos_pedidos(id_pedido_online, id_producto, precio, cantidad, total)
            VALUES($id_pedido_online, $id_producto, $precio, $cantidad, $total)
            ";
            $res2 = mysqli_query($con, $mysqli2);

            if (!$res2) {
                echo $mysqli2;
            } else {
                echo 'Successfuly';
            }
        }



        //MODIFICAR TELÉFONO DEL CLIENTE ONLINE
        if ($_POST["quest"] == 'modificar_telefono_cliente') {
            $mysqli = "
            UPDATE clientes_online
            SET telefono = '" . $_POST["telefono"] . "', fecha_actualizado = NOW()
            WHERE id = " . $_POST["id_cliente"];
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo $mysqli;
            } else {
                echo 'Successfuly';
            }
        }

        //ELIMINAR DETALLE PEDIDO
        if ($_POST["quest"] == 'eliminar_detalle_pedido') {
            $mysqli = "DELETE FROM detalle_pedido_online WHERE id_pedido_online = " . $_POST["idPedido"];
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo 'No';
            }
            echo 'Successfuly';
        }

        if ($_POST["quest"] == 'eliminar_detalle_online') {
            $mysqli = "DELETE FROM detalle_pedido_online WHERE id = " . $_POST["id_detalle"];
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo 'No';
            }
            echo 'Successfully';
        }

        //ELIMINAR PEDIDO
        if ($_POST["quest"] == 'eliminar_pedido') {
            $mysqli = "DELETE FROM pedido_online WHERE id = " . $_POST["idPedido"];
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo 'No';
            }
            echo 'Successfuly';
        }
        // ...
    }
}

/***********************************************
 *     Función extra updateConteos() (opc)     *
 ***********************************************/
// function updateConteos()
// {
//     // ---------------- SQL SERVER -------------------- //
//     $dsn = "Driver={SQL Server};Server=localhost;Port=1433;Database=master";
//     $user = 'sa';
//     $password = 'grueconsa';
//     $conn = odbc_connect($dsn, $user, $password);
//     if (!$conn) {
//         if (phpversion() < '4.0') {
//             exit("Connection Failed: . $php_errormsg");
//         } else {
//             exit("Connection Failed:" . odbc_errormsg());
//         }
//     }
//     // ---------------- MYSQL -------------------- //
//     $con = mysqli_connect("localhost", "root", "");
//     if (!$con) {
//         die('Could not connect: ' . mysqli_connect_error());
//     }
//     mysqli_select_db($con, "dbinventarios");
// }
?>
