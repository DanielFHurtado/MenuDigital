<?php
session_start();
if ($_SESSION['rol'] !== "admin") {
    header("Location: login.php");
    exit;
}
include("conexion.php");

$id = $_GET['id'];

$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $disponible = isset($_POST['disponible']) ? 1 : 0;

    $sql = "UPDATE productos SET nombre=?, precio=?, disponible=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdii", $nombre, $precio, $disponible, $id);

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
<title>Editar Producto</title>
</head>
<body>
<h1>Editar Producto</h1>
<form method="post">
    Nombre: <input type="text" name="nombre" value="<?= $producto['nombre'] ?>" required><br><br>
    Precio: <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required><br><br>
    Disponible: <input type="checkbox" name="disponible" <?= $producto['disponible'] ? "checked" : "" ?>><br><br>
    <button type="submit">Actualizar</button>
</form>
<a href="panel_admin.php">Volver</a>
</body>
</html>
