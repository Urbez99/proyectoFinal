<?php
session_start();

// Verificar si el usuario es administrador
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "admin") {
    echo "Acceso denegado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Panel de Administración</h1>
        <h2>Bienvenido, Administrador</h2>
        <p>Desde este panel puedes gestionar los torneos, inscripciones y votaciones.</p>
    </header>


    <nav>
        <ul>
            <li><a href="http://localhost/proyectoFinal/proyectoFinal/public/formulario_registro_torneo.html">Registrar Torneo</a></li>
            <li><a href="http://localhost/proyectoFinal/proyectoFinal/public/formulario_eliminar_participante.html">Eliminar Participante</a></li>
            <li><a href="http://localhost/proyectoFinal/proyectoFinal/public/cerrar_torneo.php">Cerrar Torneo</a></li>
            <li><a href="http://localhost/proyectoFinal/proyectoFinal/public/resultados.php">Ver Resultados</a></li>
            <li><a href="http://localhost/proyectoFinal/proyectoFinal/app/torneos/listado_torneos.php">Ver Torneos Activos</a></li>
        </ul>
    </nav>

    <section>
        <a href="http://localhost/proyectoFinal/proyectoFinal/public/index.html" class="logout-btn">Cerrar Sesión</a>
    </section>


    <footer>
        <p>&copy; 2025 Página de Torneos de La Comisión de 2025</p>
    </footer>
</body>

</html>