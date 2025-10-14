<?php
session_start();
if ($_SESSION['rol'] !== "admin") {
    header("Location: login.php");
    exit;
}
include("conexion.php");

$id = $_GET['id'];

$sql = "DELETE FROM productos WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: panel_admin.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}
?>
