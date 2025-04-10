<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni_participante = $_POST["dni_participante"];
    $id_torneo = $_POST["torneo_id"];

    // Verificar si el participante está inscrito en el torneo
    $consulta = $conexion->prepare("SELECT * FROM inscripciones WHERE dni = ? AND torneo_id = ?");
    $consulta->bind_param("si", $dni_participante, $id_torneo);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        // Eliminar la inscripción
        $eliminar = $conexion->prepare("DELETE FROM inscripciones WHERE dni = ? AND torneo_id = ?");
        $eliminar->bind_param("si", $dni_participante, $id_torneo);

        if ($eliminar->execute()) {
            echo "Participante eliminado correctamente.";
        } else {
            echo "Error al eliminar el participante.";
        }
    } else {
        echo "El participante no está inscrito en este torneo.";
    }
} else {
    echo "Método no permitido.";
}
?>
