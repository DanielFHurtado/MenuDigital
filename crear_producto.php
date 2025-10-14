<?php
session_start();
if ($_SESSION['rol'] !== "admin") {
    header("Location: login.php");
    exit;
}
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    $sql = "INSERT INTO productos (nombre, precio, disponible) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdi", $nombre, $precio, $disponible);

    if ($stmt->execute()) {
        header("Location: panel_admin.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Crear Producto</title>
</head>
<body>
<h1>Crear Nuevo Producto</h1>
<form method="post">
    Nombre: <input type="text" name="nombre" required><br><br>
    Precio: <input type="number" step="0.01" name="precio" required><br><br>
    Disponible: <input type="checkbox" name="disponible" checked><br><br>
    <button type="submit">Guardar</button>
</form>
<a href="panel_admin.php">Volver</a>
</body>
</html>
