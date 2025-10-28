<?php
include_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_apellido = trim($_POST['nombre_apellido']);
    $cedula = trim($_POST['cedula']);
    $rol = $_POST['rol'] ?? 'usuario';

    if (!empty($nombre_apellido) && !empty($cedula)) {
        // Evita duplicados por cédula
        $check = $conn->prepare("SELECT id FROM usuarios WHERE cedula = ? LIMIT 1");
        $check->bind_param("s", $cedula);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('⚠️ Esta cédula ya está registrada.');</script>";
        } else {
            $sql = "INSERT INTO usuarios (nombre_apellido, cedula, rol) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nombre_apellido, $cedula, $rol);

            if ($stmt->execute()) {
                echo "<script>alert('✅ Usuario registrado con éxito'); window.location='login.php';</script>";
            } else {
                echo "<script>alert('⚠️ Error al registrar usuario');</script>";
            }
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
    input, select {
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
    a {color:#fff;text-decoration:none;display:block;margin-top:15px;}
    a:hover {color:#ffcc00;}
</style>
</head>
<body>
<div class="blur-bg"></div>

<div class="form-box">
    <img src="logo.png" alt="Pancho Rápidas">
    <h2>Crear cuenta</h2>

    <form method="POST" action="">
        <input type="text" name="nombre_apellido" placeholder="Nombre y Apellido" required>
        <input type="text" name="cedula" placeholder="Cédula" required>
        <select name="rol" required>
            <option value="usuario">Usuario</option>
            <option value="admin">Administrador</option>
        </select>
        <button type="submit">Registrarme</button>
    </form>
    <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
</div>
</body>
</html>
