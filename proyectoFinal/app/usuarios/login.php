<?php
session_start();
include '../db.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST["dni"];
    $contrasena = $_POST["password"]; // Obtener la contraseña ingresada

    // Consultar el usuario en la base de datos
    $consulta = $conexion->prepare("SELECT contrasena, nombre, rol FROM usuarios WHERE dni = ?");
    $consulta->bind_param("s", $dni);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        echo "Contraseña almacenada en la BD: " . $usuario['contrasena'] . "<br>";
        echo "Contraseña ingresada: " . $contrasena . "<br>";

        // Verificar que la contraseña sea correcta
        if (password_verify($contrasena, $usuario['contrasena'])) {
            echo "Contraseña correcta!"; // Mensaje de depuración

            $_SESSION["dni"] = $dni;
            $_SESSION["nombre"] = $usuario["nombre"];
            $_SESSION["rol"] = $usuario["rol"];

            // Redirigir según el rol
            if ($usuario["rol"] == "admin") {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: usuario_dashboard.php");
            }
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "DNI incorrecto.";
    }
}
?>
