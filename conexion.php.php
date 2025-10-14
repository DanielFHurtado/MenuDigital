<?php
// conexion.php (debug)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";   // si tienes contraseña, ponla aquí
$dbname   = "menu_digital";

$conn = @new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_errno) {
    // mostrar error de conexión para depuración
    echo "<h2>Error de conexión a la base de datos</h2>";
    echo "<p>({$conn->connect_errno}) {$conn->connect_error}</p>";
    error_log("DB connect error: " . $conn->connect_error);
    exit;
}
?>
