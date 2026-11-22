<?php
// Conexión a la base de datos bd_equipos (MySQL de XAMPP)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd_equipos";

// Los errores se revisan a mano (como en clase) en lugar de lanzar excepciones
mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("No se conectó a la base de datos: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
