<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscribir Torneo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include '../db.php'; // Conectar a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni_usuario = trim($_POST["dni"]);
    $id_torneo = intval($_POST["torneo_id"]);

    // Validar el formato del DNI (ejemplo para España: 8 números + 1 letra)
    if (!preg_match("/^\d{8}[A-Z]$/i", $dni_usuario)) {
        die("Error: DNI no válido.");
    }

    // Verificar que el torneo está abierto
    $consulta_torneo = $conexion->prepare("SELECT tipo FROM torneos WHERE id = ? AND estado = 'abierto'");
    $consulta_torneo->bind_param("i", $id_torneo);
    $consulta_torneo->execute();
    $resultado_torneo = $consulta_torneo->get_result();

    if ($resultado_torneo->num_rows === 0) {
        die("Error: El torneo no está disponible para inscripción.");
    }

    // Verificar si el usuario ya está inscrito
    $consulta_inscripcion = $conexion->prepare("SELECT * FROM inscripciones WHERE dni = ? AND torneo_id = ?");
    $consulta_inscripcion->bind_param("si", $dni_usuario, $id_torneo);
    $consulta_inscripcion->execute();
    if ($consulta_inscripcion->get_result()->num_rows > 0) {
        die("Error: El usuario ya está inscrito en este torneo.");
    }

    // Insertar la inscripción
    $insertar = $conexion->prepare("INSERT INTO inscripciones (dni, torneo_id) VALUES (?, ?)");
    $insertar->bind_param("si", $dni_usuario, $id_torneo);
    if ($insertar->execute()) {
        echo "Inscripción exitosa.";
    } else {
        echo "Error al inscribir: " . $conexion->error;
    }
} else {
    echo "Método no permitido.";
}
?>
</body>
</html>
