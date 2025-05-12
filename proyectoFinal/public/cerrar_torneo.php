<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Participante</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include '../app/db.php'; // Conectar a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $torneo_id = $_POST['torneo_id'];

    // Actualizar el estado del torneo a 'cerrado'
    $consulta = $conexion->prepare("UPDATE torneos SET estado = 'cerrado' WHERE id = ?");
    $consulta->bind_param("i", $torneo_id);
    if ($consulta->execute()) {
        echo "Torneo cerrado correctamente.";
    } else {
        echo "Error al cerrar el torneo: " . $conexion->error;
    }
    $consulta->close();
} else {
    // Mostrar formulario para cerrar torneo
    $consulta = "SELECT id, nombre FROM torneos WHERE estado = 'abierto'";
    $resultado = $conexion->query($consulta);

    if ($resultado->num_rows > 0) {
        echo "<h2>Cerrar Torneo</h2>";
        echo "<form action='cerrar_torneo.php' method='POST'>";
        echo "<label for='torneo_id'>Seleccionar Torneo:</label>";
        echo "<select name='torneo_id' id='torneo_id' required>";
        
        while ($torneo = $resultado->fetch_assoc()) {
            echo "<option value='" . $torneo['id'] . "'>" . $torneo['nombre'] . "</option>";
        }
        
        echo "</select><br><br>";
        echo "<button type='submit'>Cerrar Torneo</button>";
        echo "</form>";
    } else {
        echo "<p>No hay torneos abiertos para cerrar.</p>";
    }
}

$conexion->close();
?>
</body>
</html>
