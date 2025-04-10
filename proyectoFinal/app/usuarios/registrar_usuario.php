<?php
include '../db.php'; // Ajusta la ruta si es necesario

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $dni = $_POST["dni"];
    $nombre = $_POST["nombre"];
    $pena = $_POST["peña"]; // Cambiar 'pena' a 'peña'
    $password = $_POST["password"];

    // Verificar que todos los campos están completos
    if (empty($dni) || empty($nombre) || empty($pena) || empty($password)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Consultar si el DNI ya existe en la base de datos
    $query = "SELECT * FROM usuarios WHERE dni = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $dni);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "El DNI ya está registrado.";
    } else {
        // Insertar el nuevo usuario con la contraseña cifrada
        $query_insert = "INSERT INTO usuarios (dni, nombre, peña, contrasena) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conexion->prepare($query_insert);
        $stmt_insert->bind_param("ssss", $dni, $nombre, $pena, $hashed_password); // Cambiar 'pena' a 'peña'

        if ($stmt_insert->execute()) {
            echo "Usuario registrado correctamente.";
            exit();
        } else {
            echo "Error al registrar el usuario: " . $conexion->error;
        }

        $stmt_insert->close();
    }

    $stmt->close();
    $conexion->close();
}
?>
