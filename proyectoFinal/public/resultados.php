<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Torneos</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php
    include '../app/db.php'; // Conectar a la base de datos

    // Consultar los torneos con los resultados
    $consulta_torneos = "SELECT t.id, t.nombre, r.ganador_dni 
                         FROM torneos t 
                         LEFT JOIN resultados r ON t.id = r.torneo_id 
                         WHERE t.estado = 'cerrado'"; // Mostrar solo torneos cerrados

    $resultado_torneos = $conexion->query($consulta_torneos);

    if ($resultado_torneos->num_rows > 0) {
        echo "<h2>Resultados de Torneos</h2>";
        while ($torneo = $resultado_torneos->fetch_assoc()) {
            $torneo_id = $torneo['id'];

            echo "<div class='torneo'>"; 

            echo "<h3>Torneo: " . $torneo['nombre'] . "</h3>";

            // Si el torneo tiene un ganador
            if ($torneo['ganador_dni']) {
                $consulta_usuario = $conexion->prepare("SELECT nombre FROM usuarios WHERE dni = ?");
                $consulta_usuario->bind_param("s", $torneo['ganador_dni']);
                $consulta_usuario->execute();
                $resultado_usuario = $consulta_usuario->get_result();
                $usuario = $resultado_usuario->fetch_assoc();
                echo "<p>Ganador: " . $usuario['nombre'] . "</p>";
            } else {
                echo "<p>Este torneo no tiene un ganador aún.</p>";
            }

            // Votaciones (Ranking)
            $consulta_votaciones = $conexion->prepare("SELECT v.candidato_dni, u.nombre AS candidato_nombre, COUNT(*) AS votos
                                                       FROM votaciones v
                                                       JOIN usuarios u ON v.candidato_dni = u.dni
                                                       WHERE v.torneo_id = ?
                                                       GROUP BY v.candidato_dni
                                                       ORDER BY votos DESC");
            $consulta_votaciones->bind_param("i", $torneo_id);
            $consulta_votaciones->execute();
            $resultado_votaciones = $consulta_votaciones->get_result();

            if ($resultado_votaciones->num_rows > 0) {
                $max_votos = 0;
                $ganadores = [];

                // Obtener máximos votos
                while ($voto = $resultado_votaciones->fetch_assoc()) {
                    if ($voto['votos'] > $max_votos) {
                        $max_votos = $voto['votos'];
                        $ganadores = [$voto];
                    } elseif ($voto['votos'] == $max_votos) {
                        $ganadores[] = $voto;
                    }
                }

                echo "<h4>Votaciones:</h4><ul>";
                $nombres_ganadores = [];
                foreach ($ganadores as $ganador) {
                    $nombres_ganadores[] = $ganador['candidato_nombre'];
                    echo "<li>" . $ganador['candidato_nombre'] . " - Votos: " . $ganador['votos'] . "</li>";
                }

                // Actualizar la tabla resultados
                if (!empty($ganadores)) {
                    $nombres_ganadores_str = implode(", ", $nombres_ganadores);
                    $consulta_ganadores = $conexion->prepare("UPDATE resultados SET ganador_dni = ? WHERE torneo_id = ?");
                    $consulta_ganadores->bind_param("si", $nombres_ganadores_str, $torneo_id);
                    $consulta_ganadores->execute();
                    echo "<p>El/Los ganador/es del torneo son: " . $nombres_ganadores_str . "</p>";
                }

                echo "</ul>";
            } else {
                echo "<p>No hubo votos en este torneo.</p>";
            }

            echo "</div>"; 
        }
    } else {
        echo "<p class='no-torneos'>No hay torneos cerrados con resultados disponibles.</p>";
    }

    $conexion->close();
    ?>

    <!-- Botón para redirigir a la página principal -->
    <a href="http://localhost/proyectoFinal/proyectoFinal/public/index.html" class="button">Volver a la Página Principal</a>

    <footer>
        <p>&copy; 2025 Página de Torneos de La Comisión de 2025</p>
    </footer>

</body>

</html>
