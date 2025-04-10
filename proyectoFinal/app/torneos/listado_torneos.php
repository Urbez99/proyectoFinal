<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Torneos Activos</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>


    <?php
    // Incluir la conexión a la base de datos
    include($_SERVER['DOCUMENT_ROOT'] . "/proyectoFinal/proyectoFinal/app/db.php");

    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Consultar los torneos activos
    $consulta_torneos = "SELECT id, nombre, tipo, estado FROM torneos WHERE estado = 'abierto'";

    $resultado_torneos = $conexion->query($consulta_torneos);


    session_start();
    if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
        echo "Acceso denegado.";
        exit();
    }

    if ($resultado_torneos->num_rows > 0) {
        echo "<h2>Torneos Activos</h2>";
        while ($torneo = $resultado_torneos->fetch_assoc()) {
            echo "<div class='torneo'>";
            echo "<p><strong>Torneo:</strong> " . $torneo['nombre'] . " | <strong>Tipo:</strong> " . $torneo['tipo'] . " | <strong>Estado:</strong> " . $torneo['estado'] . "</p>";

            // Consultar los participantes del torneo
            $consulta_participantes = $conexion->prepare("
            SELECT u.nombre 
            FROM inscripciones i 
            JOIN usuarios u ON i.dni = u.dni 
            WHERE i.torneo_id = ?
        ");
            $consulta_participantes->bind_param("i", $torneo['id']);
            $consulta_participantes->execute();
            $resultado_participantes = $consulta_participantes->get_result();

            if ($resultado_participantes->num_rows > 0) {
                echo "<h4>Participantes:</h4><ul>";
                while ($participante = $resultado_participantes->fetch_assoc()) {
                    echo "<li>" . $participante['nombre'] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No hay participantes inscritos aún.</p>";
            }

            echo "</div>"; // Cierre de la clase torneo
        }
    } else {
        echo "<div class='no-torneos'>No hay torneos activos disponibles.</div>";
    }

    $conexion->close();
    ?>

    <!-- Botón para volver a la página principal -->
    <a href="http://localhost/proyectoFinal/proyectoFinal/public/index.html" class="button">Volver a la Página Principal</a>

    <footer>
    
        <p>&copy; 2025 Página de Torneos de La Comisión de 2025</p>
    </footer>
    
</body>

</html>