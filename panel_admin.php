<?php
session_start();
require 'conexion.php';

// Solo admins
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'admin') {
    header("Location: panel_usuario.php");
    exit;
}

// Cargar menú
$menu_json = file_get_contents("menu.json");
$menu = json_decode($menu_json, true);

// Guardar cambios del menú
if (isset($_POST['save_menu'])) {
    file_put_contents("menu.json", json_encode($_POST['menu'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "<script>alert('Cambios guardados correctamente');</script>";
}

// Cambiar rol
if (isset($_POST['update_role'])) {
    $id = $_POST['id'];
    $rol = $_POST['rol'];
    $conn->query("UPDATE usuarios SET rol='$rol' WHERE id=$id");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Administrador - Pancho Rápidas</title>
<style>
body { font-family: Arial; background:#fffaf2; color:#333; padding:20px; }
h1,h2 { color:#c0392b; }
table { border-collapse: collapse; width:100%; margin:20px 0; }
td,th { border:1px solid #ddd; padding:8px; }
th { background:#c0392b; color:white; }
input, select { width:100%; padding:4px; }
</style>
</head>
<body>
<h1>Panel de Administración</h1>
<a href="logout.php" style="
    display:inline-block;
    background:#c0392b;
    color:#fff;
    padding:8px 14px;
    border-radius:6px;
    text-decoration:none;
    margin-bottom:20px;
">Cerrar sesión</a>


<h2>Editar Menú</h2>
<form method="post">
<?php foreach($menu as $categoria => $productos): ?>
    <h3><?= $categoria ?></h3>
    <table>
        <tr><th>Nombre</th><th>Precio</th><th>Imagen</th></tr>
        <?php foreach($productos as $i => $p): ?>
        <tr>
            <td><input name="menu[<?= $categoria ?>][<?= $i ?>][name]" value="<?= htmlspecialchars($p['name']) ?>"></td>
            <td><input name="menu[<?= $categoria ?>][<?= $i ?>][price]" value="<?= $p['price'] ?>"></td>
            <td><input name="menu[<?= $categoria ?>][<?= $i ?>][img]" value="<?= htmlspecialchars($p['img']) ?>"></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endforeach; ?>
<button type="submit" name="save_menu">💾 Guardar Cambios</button>
</form>

<h2>Gestión de Usuarios</h2>
<table>
<tr><th>ID</th><th>Nombre</th><th>Cédula</th><th>Rol</th><th>Acción</th></tr>
<?php
$result = $conn->query("SELECT * FROM usuarios");
while ($u = $result->fetch_assoc()):
?>
<tr>
<form method="post">
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['nombre_apellido']) ?></td>
    <td><?= htmlspecialchars($u['cedula']) ?></td>
    <td>
        <select name="rol">
            <option value="usuario" <?= $u['rol']=='usuario'?'selected':'' ?>>Usuario</option>
            <option value="admin" <?= $u['rol']=='admin'?'selected':'' ?>>Administrador</option>
        </select>
    </td>
    <td>
        <input type="hidden" name="id" value="<?= $u['id'] ?>">
        <button name="update_role">Actualizar</button>
    </td>
</form>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
