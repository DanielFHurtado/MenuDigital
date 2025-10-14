<?php
include_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);
    $rol = "usuario";

    if (!empty($usuario) && !empty($correo) && !empty($password)) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (usuario, correo, contraseña, rol) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssss", $usuario, $correo, $passwordHash, $rol);

        if ($stmt->execute()) {
            echo "<script>alert('Usuario registrado con éxito'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('⚠️ El usuario o correo ya existe');</script>";
        }
    } else {
        echo "<script>alert('⚠️ Todos los campos son obligatorios.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro - Pancho Rápidas</title>
<style>
    body {
        margin: 0;
        height: 100vh;
        font-family: "Poppins", sans-serif;
        background: radial-gradient(circle at center, #ffcc00 0%, #c0392b 90%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .blur-bg {
        position: absolute;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.1);
    }

    .form-box {
        position: relative;
        z-index: 10;
        width: 380px;
        padding: 40px;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        text-align: center;
    }

    .form-box img {
        width: 130px;
        margin-bottom: 20px;
    }

    h2 {
        color: #fff;
        margin-bottom: 10px;
        font-weight: 600;
    }

    input {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border-radius: 10px;
        border: none;
        font-size: 15px;
        outline: none;
    }

    button {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        border: none;
        border-radius: 10px;
        background: #c0392b;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s;
    }

    button:hover {
        background: #a93226;
        transform: scale(1.03);
    }

    a {
        color: #fff;
        text-decoration: none;
        display: block;
        margin-top: 15px;
        transition: color 0.3s;
    }

    a:hover {
        color: #ffcc00;
    }
</style>
</head>
<body>
<div class="blur-bg"></div>

<div class="form-box">
    <img src="logo.png" alt="Pancho Rápidas">
    <h2>Crear cuenta</h2>

    <form method="POST" action="">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="email" name="correo" placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Registrarme</button>
    </form>
    <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
</div>
</body>
</html>
