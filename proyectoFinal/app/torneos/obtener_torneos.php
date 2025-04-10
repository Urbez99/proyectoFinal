<?php
include '../db.php';

$resultado = $conexion->query("SELECT id, nombre FROM torneos WHERE estado = 'abierto'");
$torneos = [];

while ($fila = $resultado->fetch_assoc()) {
    $torneos[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($torneos);
?>
