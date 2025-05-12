<?php
include '../db.php'; // Conectar a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $torneo_id = intval($_POST["torneo_id"]);

    // Verificar si el torneo existe
    $consulta = $conexion->prepare("SELECT id FROM torneos WHERE id = ?");
    $consulta->bind_param("i", $torneo_id);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        // Eliminar el torneo
        $eliminar = $conexion->prepare("DELETE FROM torneos WHERE id = ?");
        $eliminar->bind_param("i", $torneo_id);

        if ($eliminar->execute()) {
            echo "Torneo eliminado correctamente.";
        } else {
            echo "Error al eliminar el torneo: " . $conexion->error;
        }
    } else {
        echo "El torneo no existe.";
    }

    $consulta->close();
    $eliminar->close();
} else {
    echo "Método no permitido.";
}

$conexion->close();
?>