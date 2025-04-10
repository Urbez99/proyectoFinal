<?php
// Definir el DNI y la nueva contraseña para el administrador
$admin_dni = '73167516p';
$admin_contrasena = 'comision2025';

// Cifrar la contraseña
$hashed_contrasena = password_hash($admin_contrasena, PASSWORD_DEFAULT);
echo "Hash generado: " . $hashed_contrasena . "<br>";

// Incluir la conexión a la base de datos
include($_SERVER['DOCUMENT_ROOT'] . "/proyectoFinal/proyectoFinal/app/db.php");

// Verificar si la conexión es válida
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Preparar la consulta segura
$query = $conexion->prepare("UPDATE usuarios SET contrasena = ?, rol = 'admin' WHERE dni = ?");
$query->bind_param("ss", $hashed_contrasena, $admin_dni);

if ($query->execute()) {
    echo "Contraseña del administrador actualizada exitosamente.";
} else {
    echo "Error al actualizar la contraseña: " . $conexion->error;
}

// Cerrar la conexión
$query->close();
$conexion->close();
?>
