<?php
/*********************************************
 *  servidor_pepeline.php                    *
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
 $con = $conexion_mysql;


// ---------------- CONSULTAS -------------------- //

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

        if ($_GET["quest"] == 'ver_clientes_prospecto') {
            $mysqli = "SELECT * FROM prospectos_unhesa";
            $result = mysqli_query($con, $mysqli);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id' => $row["idProspecto"],
                        'nombre' => $row["nombre"],
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        if ($_GET["quest"] == 'buscar_clientes_prospecto') {
            $mysqli = "SELECT * FROM prospectos_unhesa WHERE nombre = '" . $_GET["nombre"] . "'";
            $result = mysqli_query($con, $mysqli);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id' => $row["idProspecto"],
                        'nombre' => $row["nombre"],
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        if ($_GET["quest"] == 'obtener_id_prospecto') {
            $sql = "SELECT idProspecto from prospectos_unhesa where nombre = '" . $_GET['nombre_prospecto'] . "'";
            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id' => $row["idProspecto"],
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        if ($_GET["quest"] == 'ver_productos_nuevos') {
            $mysqli = "SELECT * FROM productos_nuevos_unhesa";
            $result = mysqli_query($con, $mysqli);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id' => $row["idProductoNuevo"],
                        'nombre' => $row["nombre"],
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        if ($_GET["quest"] == 'tipo_pipeline') {
            $sql = "SELECT * FROM tipo_pipeline_unhesa";

            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id' => $row["idTipo"],
                        'nombre' => $row["nombre"]
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        if ($_GET["quest"] == 'estado') {
            $sql = "SELECT * FROM estado_pipeline_unhesa order by porcentaje";

            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id' => $row["idEstado"],
                        'nombre' => $row["nombre"]
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        if ($_GET["quest"] == 'cliente_prospecto') {
            $sql = "SELECT * FROM prospectos_unhesa WHERE idProspecto = " . $_GET["id_prospecto"];
            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'nombre' => $row["nombre"],
                        'id' => $row["idProspecto"]
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
                        'codigo' => $row["codigo"]
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        if ($_GET["quest"] == 'buscar_producto_nuevo') {
            $mysqli = "SELECT * FROM productos_nuevos_unhesa WHERE nombre = '" . $_GET["nombre"] . "'";
            $result = mysqli_query($con, $mysqli);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id' => $row["idProductoNuevo"],
                        'nombre' => $row["nombre"],
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        if ($_GET["quest"] == 'lista_pipeline') {
            $sql = "SELECT p.idPipeline AS idPipeline, 
            p.id_prospecto as prospecto, 
            c.nombre AS cliente,
            p.id_cliente_unhesa as id_cliente,
            tp.nombre AS tipo,
            e.nombre AS estado,
            e.porcentaje AS porcentaje, 
            u.nombre AS vendedor,
            (select max(Date(fecha)) from historial_pipeline_unhesa hp WHERE hp.id_pipeline = p.idPipeline) as ultimaActualizacion,
            (select TIMESTAMPDIFF(day, ultimaActualizacion, NOW())) as tiempoTranscurrido,
            coalesce(pr.nombre, prn.nombre, 'Sin Detalle') AS producto,
            dp.idProducto as id_producto,
            dp.idProductoNuevo as producto_nuevo,
            p.precio AS precio,
            p.oportunidad_anio AS oportunidad,
            p.potencial AS potencial,
            p.moneda AS moneda,
            DATE(p.inicio_oportunidad) AS iOportunidad,
            coalesce(DATE(p.inicio_facturacion), 'Sin Fecha Asignada') AS iFacturacion
            FROM pipeline_unhesa p 
            INNER JOIN usuario u ON p.id_vendedor = u.idUsuario 
            INNER JOIN historial_pipeline_unhesa hp1 ON hp1.id_pipeline = p.idPipeline
            INNER JOIN cliente c ON p.id_cliente_unhesa = c.idCliente 
            INNER JOIN estado_pipeline_unhesa e ON p.id_estado = e.idEstado
            LEFT JOIN detalle_pipeline_unhesa dp ON dp.idPipeline = p.idPipeline
            LEFT JOIN producto pr ON dp.idProducto = pr.idProducto
            LEFT JOIN productos_nuevos_unhesa prn ON dp.idProductoNuevo = prn.idProductoNuevo
            INNER JOIN tipo_pipeline_unhesa tp ON p.id_tipo_pipeline = tp.idTipo";

            if ($_GET["minimaFecha"] == "" && $_GET["maximaFecha"] == "") {
                if ($_GET["prospecto"] != "true") {
                    if ($_GET["id_cliente"] != "") {
                        $sql = $sql . " where p.id_cliente_unhesa = " . $_GET["id_cliente"];
                        $sql = $sql . " group by p.idPipeline";
                        $sql = $sql . " ORDER BY idPipeline desc";
                    } else {
                        $sql = $sql . " group by p.idPipeline";
                        $sql = $sql . " ORDER BY p.idPipeline desc";
                    }
                } else {
                    if ($_GET["id_prospecto"] != "") {
                        $sql = $sql . " where p.id_prospecto = " . $_GET["id_prospecto"];
                        $sql = $sql . " group by p.idPipeline";
                        $sql = $sql . " ORDER BY idPipeline desc";
                    } else {
                        $sql = $sql . " group by p.idPipeline";
                        $sql = $sql . " ORDER BY idPipeline desc";
                    }
                }
            } else {
                $sql = $sql . " where DATE(p.inicio_oportunidad) >= '" . $_GET["minimaFecha"] . "'";
                $sql = $sql . " and DATE(p.inicio_oportunidad) <= '" . $_GET["maximaFecha"] . "'";
                if ($_GET["prospecto"] != "true") {
                    if ($_GET["id_cliente"] != "") {
                        $sql = $sql . " and p.id_cliente_unhesa = " . $_GET["id_cliente"];
                    }
                } else {
                    if ($_GET["id_prospecto"] != "") {
                        $sql = $sql . " and p.id_prospecto = " . $_GET["id_prospecto"];
                    }
                }

                $sql = $sql . " group by p.idPipeline";
                $sql = $sql . " ORDER BY p.idPipeline desc";
            }

            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con) . ' Query: ' . $sql);
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'idPipeline' => $row["idPipeline"],
                        'tipo' => $row["tipo"],
                        'moneda' => $row["moneda"],
                        'tiempoTranscurrido' => $row["tiempoTranscurrido"],
                        'ultimaActualizacion' => $row["ultimaActualizacion"],
                        'producto' => $row["producto"],
                        'precio' => $row["precio"],
                        'oportunidad' => $row["oportunidad"],
                        'potencial' => $row["potencial"],
                        'porcentaje' => $row["porcentaje"],
                        'cliente' => $row["cliente"],
                        'vendedor' => $row["vendedor"],
                        'iOportunidad' => $row["iOportunidad"],
                        'iFacturacion' => $row["iFacturacion"],
                        'estado' => $row["estado"],
                        'prospecto' => $row["prospecto"],
                        'producto_nuevo' => $row["producto_nuevo"],
                        'id_cliente' => $row['id_cliente'],
                        'id_producto' => $row['id_producto']
                    );
                }
                echo $json_string = json_encode($json);
                //echo $sql;
            } else {
                echo $sql;
            }
        }

        if ($_GET["quest"] == 'detalle_pipeline') {
            $sql = "SELECT cl.codigo as codigo_cliente,
            cl.nombre as nombre_cliente,
            u.nombre as vendedor,
            p.moneda as tipo_moneda,
            pr.nombre as producto,
            pr.codigo as codigoProducto,
            p.id_tipo_pipeline as tipo_pipeline,
            p.precio as precio,
            p.oportunidad_anio as oportunidad_anio,
            p.potencial as potencial,
            ";

            if ($_GET['prospecto'] != 'false') {
                $sql = $sql . "pt.idProspecto as codigo_prospecto, 
                    pt.nombre as nombre_prospecto,";
            }

            $sql = $sql . "(select max(Date(fecha)) from historial_pipeline_unhesa hp WHERE hp.id_pipeline = " . $_GET["idPipeline"] . ") as ultimaActualizacion,
            (select TIMESTAMPDIFF(day, ultimaActualizacion, now())) as tiempoTranscurrido,
            p.id_estado as estado,
            ep.nombre as nombre_estado,
            tp.nombre as nombre_tipo,
            DATE(p.inicio_oportunidad) as inicioOportunidad,
            DATE(p.inicio_facturacion) as inicioFacturacion
            FROM pipeline_unhesa p
            INNER JOIN usuario u ON p.id_vendedor = u.idUsuario
            INNER JOIN historial_pipeline_unhesa hp1 ON hp1.id_pipeline = p.idPipeline 
            INNER JOIN cliente cl ON p.id_cliente_unhesa = cl.idCliente
            LEFT JOIN detalle_pipeline_unhesa dp ON p.idPipeline = dp.idPipeline
            LEFT JOIN producto pr ON dp.idProducto = pr.idProducto
            INNER JOIN tipo_pipeline_unhesa tp ON p.id_tipo_pipeline = tp.idTipo
            INNER JOIN historial_pipeline_unhesa h ON p.idPipeline = h.id_pipeline
            INNER JOIN estado_pipeline_unhesa ep ON p.id_estado = ep.idEstado";

            if ($_GET['prospecto'] != 'false') {
                $sql = $sql . " INNER JOIN prospectos_unhesa pt ON p.id_prospecto = pt.idProspecto";
            }

            $sql = $sql . " WHERE p.idPipeline = " . $_GET["idPipeline"] . " group by p.idPipeline";

            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló: ' . $sql);
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    if ($_GET['prospecto'] == 'true') {
                        $json['prospecto'] = array(
                            'codigo_prospecto' => $row['codigo_prospecto'],
                            'nombre_prospecto' => $row['nombre_prospecto']
                        );
                    }

                    $json['general'] = array(
                        'codigo_cliente' => $row["codigo_cliente"],
                        'nombre_cliente' => $row["nombre_cliente"],
                        'tiempoTranscurrido' => $row["tiempoTranscurrido"],
                        'tipo_moneda' => $row["tipo_moneda"],
                        'producto' => $row["producto"],
                        'codigoProducto' => $row["codigoProducto"],
                        'tipo_pipeline' => $row["tipo_pipeline"],
                        'precio' => $row["precio"],
                        'oportunidad_anio' => $row["oportunidad_anio"],
                        'potencial' => $row["potencial"],
                        'estado' => $row["estado"],
                        'vendedor' => $row["vendedor"],
                        'inicioOportunidad' => $row["inicioOportunidad"],
                        'inicioFacturacion' => $row["inicioFacturacion"],
                        'nombre_estado' => $row["nombre_estado"],
                        'nombre_tipo' => $row["nombre_tipo"],

                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
                echo $sql;
            }
        }

        if ($_GET["quest"] == 'historial_pipeline') {
            $sql = "SELECT h.id_historial as id, e.nombre as estado, h.fecha as fecha 
            from historial_pipeline_unhesa h
            inner join estado_pipeline_unhesa e on h.id_estado = e.idEstado
            inner join pipeline_unhesa p on h.id_pipeline = p.idPipeline";

            $sql = $sql . " WHERE h.id_pipeline = " . $_GET["idPipeline"];
            $sql = $sql . " order by h.fecha asc";

            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló: ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id' => $row["id"],
                        'estado' => $row["estado"],
                        'fecha' => $row["fecha"]
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }

        if ($_GET["quest"] == 'productos_detalle_pipeline') {
            $sql = "SELECT COALESCE(pr.nombre, pn.nombre) AS 'Producto', dp.observaciones AS Observaciones, COALESCE(pr.idProducto, pn.idProductoNuevo) AS idProducto, CASE WHEN pr.idProducto = dp.idProducto THEN 0 ELSE 1 END AS productoNuevo FROM detalle_pipeline_unhesa dp LEFT JOIN producto pr ON dp.idProducto = pr.idProducto LEFT JOIN productos_nuevos_unhesa pn ON dp.idProductoNuevo = pn.idProductoNuevo WHERE dp.idPipeline = " . $_GET['idPipeline'] . "";

            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló: ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'producto' => $row["Producto"],
                        'observaciones' => $row["Observaciones"],
                        'idProducto' => $row['idProducto'],
                        'productoNuevo' => $row['productoNuevo']
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }


        if ($_GET["quest"] == 'obtener_id_producto_nuevo') {
            $sql = "SELECT idProductoNuevo from productos_nuevos_unhesa where nombre = '" . $_GET['nombre_producto_nuevo'] . "'";
            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló ' . mysqli_error($con));
            }

            if (mysqli_num_rows($result) > 0) {
                $json = array();
                while ($row = mysqli_fetch_array($result)) {
                    $json[] = array(
                        'id' => $row["idProductoNuevo"],
                    );
                }
                $json_string = json_encode($json);
                echo $json_string;
            } else {
                echo 'No';
            }
        }
    }
}

// ---------------- PETICIONES -------------------- //

if (isset($_POST)) {
    if (isset($_POST["quest"])) {
        if ($_POST["quest"] == 'ingresar_prospecto') {
            $mysqli = "INSERT INTO `prospectos_unhesa`(`nombre`) VALUES ('" . $_POST['nombre_prospecto'] . "')";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo $mysqli;
            } else {
                echo 'Successfully';
            }
        }

        if ($_POST["quest"] == 'ingresar_pipeline_unhesa') { 
            session_start();
            $mysqli = "INSERT INTO `pipeline_unhesa`(
                `id_cliente_unhesa`, 
                `id_vendedor`, 
                `id_tipo_pipeline`,  
                `precio`, 
                `moneda`, 
                `oportunidad_anio`, 
                `potencial`, 
                `id_estado`, 
                `inicio_oportunidad`, 
                `inicio_facturacion`,
                `id_prospecto`) 
                VALUES(
                " . $_POST["codigo_cliente"] . ", 
                " . $_POST["id_vendedor"] . ", 
                " . $_POST["tipo_pipeline"] . ", 
                " . $_POST["precio"] . ", 
                '" . $_POST["tipo_moneda"] . "', 
                '" . $_POST["oportunidad_anio"] . "', 
                '" . $_POST["potencial"] . "',
                " . $_POST["estado"] . ", 
                '" . $_POST["inicio_oportunidad"] . "', 
                '" . $_POST["inicio_facturacion"] . "',
                " . $_POST["codigo_prospecto"] . ")";

            $result = mysqli_query($con, $mysqli);

            if (!$result) {
                echo $mysqli;
            } else {
                $ultimo_id = mysqli_insert_id($con);
                $_SESSION["ultimo_id_pipeline"] = $ultimo_id;
                echo 'Successfuly';
            }
        }

        if ($_POST["quest"] == 'ingresar_producto_nuevo') {
            $mysqli = "INSERT INTO `productos_nuevos_unhesa`(`nombre`) VALUES ('" . $_POST['nombre_producto_nuevo'] . "')";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo $mysqli;
            } else {
                echo 'Successfully';
            }
        }

        if ($_POST["quest"] == 'eliminar_producto_detalle_pipeline') {
            if ($_POST['productoNuevo'] == '0') {
                $mysqli = "DELETE FROM detalle_pipeline_unhesa where idProducto = " . $_POST['idProducto'] . " AND idPipeline = " . $_POST['idPipeline'] . "";
            } else {
                $mysqli = "DELETE FROM detalle_pipeline_unhesa where idProductoNuevo = " . $_POST['idProducto'] . " AND idPipeline = " . $_POST['idPipeline'] . "";
            }
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo mysqli_error($con);
                echo $mysqli;
            } else {
                echo 'Successfully';
            }
        }

        if ($_POST["quest"] == 'ingresar_historial_pipeline') {
            session_start();
            $id_pipeline = $_SESSION["ultimo_id_pipeline"];
            $mysqli = "INSERT INTO `historial_pipeline_unhesa`(
                `id_pipeline`,
                `id_estado`, 
                `fecha`) 
                values(".$id_pipeline.", ".$_POST['estado'].", current_date())";

            $result = mysqli_query($con, $mysqli);

            if (!$result) {
                echo $mysqli;
            } else {
                echo 'Successfuly';
            }
        }

        if ($_POST["quest"] == 'guardar_estado') {
            $mysqli = "UPDATE pipeline_unhesa SET id_estado = " . $_POST["id_estado"] . " WHERE idPipeline = " . $_POST["id"];
            $mysqli_q = "INSERT INTO historial_pipeline_unhesa(id_pipeline, id_estado, fecha) VALUES(" . $_POST["id"] . ", " . $_POST["id_estado"] . ", NOW())";

            $result = mysqli_query($con, $mysqli);
            $result_q = mysqli_query($con, $mysqli_q);

            if (!$result && !$result_q) {
                echo $mysqli;
                echo $mysqli_q;
            } else {
                echo 'Successfully';
            }
        }

        if ($_POST["quest"] == 'modificar_cliente_sap') {
            $mysqli = "UPDATE pipeline_unhesa set id_cliente_unhesa = " . $_POST['cliente_unhesa'] . " where idPipeline = " . $_POST['id_pipeline'] . "";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo $mysqli;
            } else {
                echo 'Successfully';
            }
        }

        if ($_POST["quest"] == 'modificar_tipo_pipeline') {
            $mysqli = "UPDATE pipeline_unhesa set id_tipo_pipeline = " . $_POST['id_tipo'] . " where idPipeline = " . $_POST['id_pipeline'] . "";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo $mysqli;
            } else {
                echo 'Successfully';
            }
        }

        if ($_POST["quest"] == 'modificar_facturacion_pipeline') {
            $mysqli = "UPDATE pipeline_unhesa set inicio_facturacion = '" . $_POST['fechaFacturacion'] . "' where idPipeline = " . $_POST['id_pipeline'] . "";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo $mysqli;
            } else {
                echo 'Successfully';
            }
        }

        if ($_POST["quest"] == 'modificar_cliente_nuevo') {
            $mysqli = "UPDATE pipeline_unhesa set id_cliente_unhesa = 2414, id_prospecto = " . $_POST['id_prospecto'] . " where idPipeline = " . $_POST['id_pipeline'] . "";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo $mysqli;
            } else {
                echo 'Successfully';
            }
        }

        if ($_POST["quest"] == 'modificar_producto_sap') {
            $mysqli = "UPDATE pipeline_unhesa set id_producto_unhesa = " . $_POST['producto_unhesa'] . " where idPipeline = " . $_POST['id_pipeline'] . "";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo $mysqli;
            } else {
                echo 'Successfully';
            }
        }

        if ($_POST["quest"] == 'modificar_producto_nuevo') {
            $mysqli = "UPDATE pipeline_unhesa set id_producto_unhesa = 1379, id_producto_nuevo = " . $_POST['id_producto_nuevo'] . " where idPipeline = " . $_POST['id_pipeline'] . "";
            $result = mysqli_query($con, $mysqli);
            if (!$result) {
                echo $mysqli;
            } else {
                echo 'Successfully';
            }
        }

        if ($_POST["quest"] == 'ingresar_producto_detalle_pipeline') {
            if($_POST['idProductoNuevo'] != '') {
                $sql = "INSERT INTO detalle_pipeline_unhesa(idPipeline, idProducto, idProductoNuevo, observaciones) VALUES(" . $_POST['idPipeline'] . ", NULL, " . $_POST['idProductoNuevo'] . ", '" . $_POST['observaciones'] . "')";
            } else {
                $sql = "INSERT INTO detalle_pipeline_unhesa(idPipeline, idProducto, idProductoNuevo, observaciones) VALUES(" . $_POST['idPipeline'] . ", " . $_POST['idProducto'] . ", NULL, '" . $_POST['observaciones'] . "')";
            }
            
            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló: ' . mysqli_error($con) . ' Query: ' . $sql);
            } else {
                echo 'Successfully';
            }
        } 

        if ($_POST["quest"] == 'ingresar_producto_detalle_pipeline_nuevo') {
            session_start();
            $id_pipeline = $_SESSION["ultimo_id_pipeline"];
            if($_POST['idProductoNuevo'] != '') {
                $sql = "INSERT INTO detalle_pipeline_unhesa(idPipeline, idProducto, idProductoNuevo, observaciones) VALUES(" . $id_pipeline . ", NULL, " . $_POST['idProductoNuevo'] . ", '" . $_POST['observaciones'] . "')";
            } else {
                $sql = "INSERT INTO detalle_pipeline_unhesa(idPipeline, idProducto, idProductoNuevo, observaciones) VALUES(" . $id_pipeline . ", " . $_POST['idProducto'] . ", NULL, '" . $_POST['observaciones'] . "')";
            }
            
            $result = mysqli_query($con, $sql);

            if (!$result) {
                die('Query Falló: ' . mysqli_error($con) . ' Query: ' . $sql);
            } else {
                echo 'Successfully';
            }
        } 
    }
}
