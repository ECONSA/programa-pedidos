<?php 
	$con = mysqli_connect("localhost", "admin", "");
	if (!$con) {
		die('Could not connect: '. mysqli_connect_error());
	}
	mysqli_select_db($con, "facturacion");
?>