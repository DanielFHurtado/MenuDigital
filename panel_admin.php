<?php
session_start();
if ($_SESSION['rol'] !== "admin") {
    header("Location: login.php");
    exit;
}
include("conexion.php");

$result = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Administrador</title>
</head>
<body>
<h1>Gestión de Productos</h1>
<a href="logout.php">Cerrar sesión</a>

<table border="1">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Precio</th>
    <th>Disponible</th>
    <th>Acciones</th>
</tr>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['nombre'] ?></td>
    <td><?= $row['precio'] ?></td>
    <td><?= $row['disponible'] ? "Sí" : "No" ?></td>
    <td>
        <a href="editar_producto.php?id=<?= $row['id'] ?>">Editar</a> |
        <a href="eliminar_producto.php?id=<?= $row['id'] ?>">Eliminar</a>
    </td>
</tr>
<?php } ?>
</table>

<a href="crear_producto.php">Añadir nuevo producto</a>

</body>
</html>
