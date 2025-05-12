<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Torneo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
// Incluir el archivo de conexión a la base de datos con verificación
$dbPath = $_SERVER['DOCUMENT_ROOT'] . "/proyectoFinal/proyectoFinal/app/db.php";
if (file_exists($dbPath)) {
    include($dbPath);
} else {
    die("Error: No se encontró el archivo de conexión a la base de datos.");
}

// Verificar si la conexión es válida
if (!isset($conexion) || !$conexion) {
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario de manera segura
    $nombre_torneo = trim($_POST["nombre"] ?? '');
    $tipo_torneo = trim($_POST["tipo"] ?? '');

    // Verificar si los datos llegaron correctamente
    if (empty($nombre_torneo) || empty($tipo_torneo)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Validar que el tipo de torneo sea válido
    $tipos_permitidos = ['con_votacion', 'sin_votacion'];
    if (!in_array($tipo_torneo, $tipos_permitidos)) {
        die("Error: Tipo de torneo no válido.");
    }

    // Estado inicial del torneo
    $estado = "abierto";

    // Preparar la consulta para evitar inyección SQL
    $consulta = $conexion->prepare("INSERT INTO torneos (nombre, tipo, estado) VALUES (?, ?, ?)");
    if (!$consulta) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $consulta->bind_param("sss", $nombre_torneo, $tipo_torneo, $estado);

    // Ejecutar la consulta y comprobar si tuvo éxito
    if ($consulta->execute()) {
        // Redirigir antes de imprimir mensajes
        header("Location: listado_torneos.php");
        exit();
    } else {
        echo "Error al registrar el torneo: " . $conexion->error;
    }

    // Cerrar la consulta y la conexión
    $consulta->close();
    $conexion->close();
} else {
    echo "Método no permitido.";
}
?>
</body>
</html>
