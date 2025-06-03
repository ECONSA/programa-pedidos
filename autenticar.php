<?PHP
include("./bd/inicia_conexion.php");
	
	session_start();

	$usuario_valido = 0;
	$sql="select idUsuario, nombre, idEmpresa from usuario";
	$sql=$sql." where usuario = '". $_POST["usuario"] ."' and contrasena = '".$_POST["contrasena"]."'";
	$resultado = mysqli_query($con,$sql);
	while($fila = mysqli_fetch_array($resultado))
	{
        $idUsuario = $fila["idUsuario"];
		if($idUsuario == '25' || $idUsuario == '27') {
			$usuario_valido = 0;
		} else {
			$usuario_valido = 1;
		}
        $nombreUsuario = $fila["nombre"];
        $idEmpresaUsuario = $fila["idEmpresa"];
	}

	if($usuario_valido == 1){
		$_SESSION["nombreUsuario"] = $nombreUsuario;
        $_SESSION["idEmpresaUsuario"] = $idEmpresaUsuario;
        $_SESSION["idUsuario"] = $idUsuario;
		header('Location: index.php');
	} 
	else {
		?> 
		<script>
			
		</script>
		<?php
		header('Location: login.php?error=1');
	}
	

include("./bd/fin_conexion.php");
?>