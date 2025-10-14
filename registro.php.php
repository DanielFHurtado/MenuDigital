<?php
// Mostrar errores en pantalla (útil para depuración)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once("conexion.php"); // Conexión a la BD

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);
    $rol = "usuario"; // por defecto todos los que se registren serán usuarios normales

    if (!empty($usuario) && !empty($correo) && !empty($password)) {
        // Hashear contraseña
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Insertar en la base de datos
        $sql = "INSERT INTO usuarios (usuario, correo, password, rol) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $usuario, $correo, $passwordHash, $rol);

        if ($stmt->execute()) {
            echo "<script>alert('Usuario registrado con éxito'); window.location='login.php';</script>";
            exit;
        } else {
            echo "⚠️ Error en el registro: " . $stmt->error;
        }
    } else {
        echo "⚠️ Todos los campos son obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Menú Digital</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        .form-container h2 {
            text-align: center;
        }
        .form-container input {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .form-container button:hover {
            background: #218838;
        }
        .form-container a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registro</h2>
        <form method="POST" action="registro.php">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="email" name="correo" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrarme</button>
        </form>
        <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
    </div>
</body>
</html>
