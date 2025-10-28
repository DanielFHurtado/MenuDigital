<?php
ob_start();
session_start();
include_once("conexion.php");

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = trim($_POST['cedula'] ?? '');

    if ($cedula === '') {
        $mensaje = "Por favor ingresa tu cédula.";
    } else {
        if (!isset($conn)) {
            die("Error: no se encontró la conexión a la base de datos.");
        }

        $sql = "SELECT * FROM usuarios WHERE cedula = ? LIMIT 1";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error en la consulta SQL: " . $conn->error);
        }

        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $row = $res->fetch_assoc();

            $_SESSION['userid'] = $row['id'];
            $_SESSION['nombre'] = $row['nombre_apellido'];
            $_SESSION['cedula'] = $row['cedula'];
            $_SESSION['rol'] = strtolower(trim($row['rol']));

            if ($_SESSION['rol'] === 'admin') {
                header("Location: panel_admin.php");
                exit;
            } elseif ($_SESSION['rol'] === 'usuario') {
                header("Location: panel_usuario.php");
                exit;
            } else {
                $mensaje = "Rol desconocido.";
            }
        } else {
            $mensaje = "Cédula no encontrada.";
        }

        $stmt->close();
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Iniciar sesión - Pancho Rápidas</title>
<style>
    body {
        margin:0;
        height:100vh;
        font-family:"Poppins",sans-serif;
        background: url('fondo.jpeg') no-repeat center center fixed;
        background-size: cover;
        display:flex;
        align-items:center;
        justify-content:center;
    }
    .blur-bg {
        position:absolute;
        width:100%;
        height:100%;
        backdrop-filter:blur(10px);
        background:rgba(0,0,0,0.4);
    }
    .form-box {
        position:relative;
        z-index:10;
        width:380px;
        padding:40px;
        border-radius:20px;
        background:rgba(255,255,255,0.2);
        box-shadow:0 8px 30px rgba(0,0,0,0.3);
        text-align:center;
    }
    .form-box img {width:130px;margin-bottom:20px;}
    h2 {color:#fff;margin-bottom:10px;font-weight:600;}
    input {
        width:100%;
        padding:12px;
        margin:10px 0;
        border-radius:10px;
        border:none;
        font-size:15px;
        outline:none;
    }
    button {
        width:100%;
        padding:12px;
        margin-top:10px;
        border:none;
        border-radius:10px;
        background:#c0392b;
        color:white;
        font-size:16px;
        cursor:pointer;
        transition:all 0.3s;
    }
    button:hover {background:#a93226;transform:scale(1.03);}
    .msg {margin-top:10px;color:yellow;font-weight:bold;}
    a {color:#fff;text-decoration:none;display:block;margin-top:15px;}
    a:hover {color:#ffcc00;}
</style>
</head>
<body>
<div class="blur-bg"></div>
<div class="form-box">
    <img src="logo.png" alt="Pancho Rápidas">
    <h2>Iniciar sesión</h2>

    <?php if ($mensaje): ?><div class="msg"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

    <form method="post" action="">
        <input type="text" name="cedula" placeholder="Cédula" required>
        <button type="submit">Entrar</button>
    </form>
    <a href="registro.php">¿No tienes cuenta? Regístrate aquí</a>
</div>
</body>
</html>
