<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni_usuario = trim($_POST["dni"]);
    $id_torneo = intval($_POST["torneo_id"]);
    $candidato_dni = trim($_POST["voto"]);

    if (!preg_match("/^\d{8}[A-Z]$/i", $dni_usuario) || !preg_match("/^\d{8}[A-Z]$/i", $candidato_dni)) {
        header("Location: ../feedback.php?tipo=error&mensaje=DNI inválido.&redirect=formulario_votacion.html");
        exit();
    }

    $consulta_torneo = $conexion->prepare("SELECT tipo FROM torneos WHERE id = ? AND estado = 'abierto'");
    $consulta_torneo->bind_param("i", $id_torneo);
    $consulta_torneo->execute();
    $resultado_torneo = $consulta_torneo->get_result();

    if ($resultado_torneo->num_rows === 0) {
        header("Location: ../feedback.php?tipo=error&mensaje=El torneo no permite votaciones.&redirect=index.html");
        exit();
    }

    $consulta_voto = $conexion->prepare("SELECT * FROM votaciones WHERE dni_votante = ? AND torneo_id = ?");
    $consulta_voto->bind_param("si", $dni_usuario, $id_torneo);
    $consulta_voto->execute();
    
    if ($consulta_voto->get_result()->num_rows > 0) {
        header("Location: ../feedback.php?tipo=error&mensaje=Ya has votado.&redirect=index.html");
        exit();
    }

    $insertar = $conexion->prepare("INSERT INTO votaciones (dni_votante, torneo_id, candidato_dni) VALUES (?, ?, ?)");
    $insertar->bind_param("sis", $dni_usuario, $id_torneo, $candidato_dni);
    
    if ($insertar->execute()) {
        header("Location: ../feedback.php?tipo=exito&mensaje=Voto registrado correctamente.&redirect=index.html");
    } else {
        header("Location: ../feedback.php?tipo=error&mensaje=Error al votar.&redirect=index.html");
    }
} else {
    header("Location: ../feedback.php?tipo=error&mensaje=Método no permitido.&redirect=index.html");
}
?>
