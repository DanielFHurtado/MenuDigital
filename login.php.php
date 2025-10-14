<?php
// login.php (debug + formulario)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include_once("conexion.php");

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $mensaje = "Por favor completa usuario y contraseña.";
    } else {
        $sql = "SELECT id, usuario, password, rol FROM usuarios WHERE usuario = ? OR correo = ? LIMIT 1";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $usuario, $usuario);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows === 1) {
                $row = $res->fetch_assoc();
                // si tu contraseña fue guardada con password_hash:
                if (password_verify($password, $row['password'])) {
                    $_SESSION['userid'] = $row['id'];
                    $_SESSION['usuario'] = $row['usuario'];
                    $_SESSION['rol'] = $row['rol'];
                    // redirige según rol
                    if ($row['rol'] === 'admin') {
                        header("Location: panel_admin.php");
                        exit;
                    } else {
                        header("Location: index.php");
                        exit;
                    }
                } else {
                    $mensaje = "Usuario o contraseña incorrectos.";
                }
            } else {
                $mensaje = "Usuario no encontrado.";
            }
            $stmt->close();
        } else {
            $mensaje = "Error interno (preparar consulta).";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Login - Pancho Rápidas</title>
  <style>
    body{font-family:Arial;padding:30px;background:#fff}
    .box{max-width:420px;margin:30px auto;padding:18px;border:1px solid #eee;border-radius:8px}
    label{display:block;margin-top:10px}
    input{width:100%;padding:8px;margin-top:6px;border-radius:6px;border:1px solid #ccc}
    button{margin-top:12px;padding:10px 14px;background:#c0392b;color:#fff;border:none;border-radius:6px;cursor:pointer}
    .msg{color:#c0392b;margin-top:10px}
  </style>
</head>
<body>
  <div class="box">
    <h2>Iniciar sesión</h2>
    <?php if($mensaje): ?><div class="msg"><?=htmlspecialchars($mensaje)?></div><?php endif; ?>
    <form method="post" action="">
      <label for="usuario">Usuario o correo</label>
      <input id="usuario" name="usuario" type="text" required>
      <label for="password">Contraseña</label>
      <input id="password" name="password" type="password" required>
      <button type="submit">Entrar</button>
    </form>
    <p style="margin-top:12px">¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
  </div>
</body>
</html>
