<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['dni'])) {
    // Si no ha iniciado sesión, redirigir al formulario de inicio de sesión
    header("Location: formulario_login.html");
    exit();
}

// Obtener el nombre y el rol del usuario desde la sesión
$nombre_usuario = $_SESSION['nombre'];
$rol_usuario = $_SESSION['rol'];

// Si el usuario no es un "usuario" (es decir, un admin), redirigir al panel de administración
if ($rol_usuario != "usuario") {
    header("Location: admin_dashboard.php"); // O redirigir a otro panel si es un admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<link rel="stylesheet" href="style.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario - Torneos</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?>!</h1>
    </header>

    <nav>
        <ul>
            <li><a href="http://localhost/proyectoFinal/proyectoFinal/public/formulario_inscripcion.html">Inscribirse en Torneo</a></li>
            <li><a href="http://localhost/proyectoFinal/proyectoFinal/public/resultados.php">Ver Resultados</a></li>
            <li><a href="http://localhost/proyectoFinal/proyectoFinal/public/formulario_votacion.html">Votar en Concursos</a></li>
            <!-- <li><a href="ver_clasificacion.php">Ver Clasificación</a></li> -->
        </ul>
    </nav>

    <section>
        <h2>Torneos Abiertos</h2>
        <p>Aquí están los Torneos Abiertos.</p>
        <ul>
            <?php
            include '../db.php'; // Incluir la conexión a la base de datos

            // Obtener los torneos abiertos
            $query = "SELECT id, nombre FROM torneos WHERE estado = 'abierto'";
            $result = $conexion->query($query);

            if ($result->num_rows > 0) {
                while ($torneo = $result->fetch_assoc()) {
                    echo '<li>ID del Torneo: ' . htmlspecialchars($torneo['id']) . ' -- Nombre: ' . htmlspecialchars($torneo['nombre']) . '</li>';
                }
            } else {
                echo '<li>No hay torneos abiertos actualmente.</li>';
            }

            // Cerrar la conexión
            $conexion->close();
            ?>
        </ul>
    </section>


    <section>
        <h2>Clasificación General</h2>
        <p>Aquí puedes ver la clasificación de los torneos anteriores.</p>
        <ul>
            <?php
            // Consultar las clasificaciones de torneos anteriores
            include '../db.php';

            $query = "SELECT t.nombre, r.ganador_dni, u.nombre as ganador_nombre FROM resultados r
                      JOIN torneos t ON r.torneo_id = t.id
                      JOIN usuarios u ON r.ganador_dni = u.dni
                      ORDER BY t.id DESC"; // Mostrar los torneos y los ganadores más recientes
            $result = $conexion->query($query);

            if ($result->num_rows > 0) {
                while ($clasificacion = $result->fetch_assoc()) {
                    echo '<li>Torneo: ' . htmlspecialchars($clasificacion['nombre']) . ' - Ganador: ' . htmlspecialchars($clasificacion['ganador_nombre']) . '</li>';
                }
            } else {
                echo '<li>No hay clasificaciones disponibles.</li>';
            }

            $conexion->close();
            ?>
        </ul>
    </section>

    <footer>
        <p>&copy; 2025 Página de Torneos de La Comisión de 2025</p>
    </footer>
</body>

</html>