<?php
$servidor = "localhost";
$usuario = "root";  // Si tienes una contraseña para tu usuario 'root', inclúyela aquí.
$contrasena = "";    // Si tienes contraseña, ponla entre las comillas.
$basededatos = "torneos_db";  // Cambiar el nombre de la base de datos a 'torneos_db'.

$conexion = new mysqli($servidor, $usuario, $contrasena, $basededatos);

// Comprobar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
