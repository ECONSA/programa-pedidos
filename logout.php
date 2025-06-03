<?php 
    session_start();

    unset($_SESSION["usuario"]);
    unset($_SESSION["idUsuario"]);
    unset($_SESSION["idRol"]);
    session_destroy();
    header('Location: login.php');

?>