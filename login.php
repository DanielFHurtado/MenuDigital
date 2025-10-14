<?php
session_start();
include_once("conexion.php");

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $mensaje = "Por favor completa usuario y contraseña.";
    } else {
        $sql = "SELECT id, usuario, correo, contraseña, rol FROM usuarios WHERE usuario = ? OR correo = ? LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $usuario, $usuario);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['contraseña'])) {
                $_SESSION['userid'] = $row['id'];
                $_SESSION['usuario'] = $row['usuario'];
                $_SESSION['rol'] = $row['rol'];

                header("Location: index.php");
                exit;
            } else {
                $mensaje = "Contraseña incorrecta.";
            }
        } else {
            $mensaje = "Usuario no encontrado.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Iniciar sesión - Pancho Rápidas</title>
<style>
    body {
        margin: 0;
        height: 100vh;
        font-family: "Poppins", sans-serif;
        background: radial-gradient(circle at center, #ffcc00 0%, #c0392b 90%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
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

    input::placeholder {
        color: #555;
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

    .msg {
        margin-top: 10px;
        color: yellow;
        font-weight: bold;
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
    <h2>Iniciar sesión</h2>

    <?php if ($mensaje): ?>
        <div class="msg"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="text" name="usuario" placeholder="Usuario o correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
    <a href="registro.php">¿No tienes cuenta? Regístrate aquí</a>
</div>
</body>
</html>
