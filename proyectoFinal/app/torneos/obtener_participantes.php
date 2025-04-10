<?php
include '../db.php';

if (isset($_GET['torneo_id'])) {
    $id_torneo = intval($_GET['torneo_id']);
    $resultado = $conexion->prepare("
        SELECT u.dni, u.nombre 
        FROM inscripciones i
        JOIN usuarios u ON i.dni = u.dni
        WHERE i.torneo_id = ?
    ");
    $resultado->bind_param("i", $id_torneo);
    $resultado->execute();
    $datos = $resultado->get_result();

    $participantes = [];
    while ($fila = $datos->fetch_assoc()) {
        $participantes[] = $fila;
    }

    header('Content-Type: application/json');
    echo json_encode($participantes);
}
?>
