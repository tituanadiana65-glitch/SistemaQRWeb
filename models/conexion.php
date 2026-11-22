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
    responder(["error" => "No se conectó a la base de datos: " . $conn->connect_error]);
}
$conn->set_charset("utf8mb4");

// Devuelve la respuesta en formato JSON (la página la lee con jQuery) y termina
function responder($datos) {
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($datos);
    exit;
}

// Devuelve todas las filas de una consulta preparada ya ejecutada
function filas($stmt) {
    $resultado = $stmt->get_result();
    $lista = [];
    while ($fila = $resultado->fetch_assoc()) {
        $lista[] = $fila;
    }
    return $lista;
}
?>
