<?php
session_start();

// Destruir todas las sesiones
session_unset();
session_destroy();

// Redirigir al index principal
header("Location: http://localhost/proyectoFinal/proyectoFinal/public/index.html");
exit();
?>