<?php
// Registra una lectura del código QR de un equipo y devuelve sus datos.
// Lo usan la lectura desde imagen y la lectura con cámara web.
require_once "sesion.php";
include "conexion.php";

$id = isset($_POST["id_equipo"]) ? (int) $_POST["id_equipo"] : 0;
if ($id <= 0) {
    responder(["error" => "El código QR no contiene un ID válido de equipo"]);
}

// 1. El equipo debe existir
$stmt = $conn->prepare("SELECT * FROM equipo WHERE id_equipo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$lista = filas($stmt);
if (count($lista) == 0) {
    responder(["error" => "No existe un equipo con ID " . $id]);
}

// 2. Se guarda la lectura (la fecha la pone MySQL con CURRENT_TIMESTAMP)
$stmt = $conn->prepare("INSERT INTO lectura_qr (id_equipo) VALUES (?)");
$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    responder(["error" => "No se pudo registrar la lectura: " . $stmt->error]);
}

// 3. Cuántas veces se ha leído en total
$stmt = $conn->prepare("SELECT COUNT(*) AS cantidad FROM lectura_qr WHERE id_equipo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$conteo = filas($stmt);

$equipo = $lista[0];
$equipo["veces_leido"] = (int) $conteo[0]["cantidad"];
responder(["success" => "Lectura registrada", "equipo" => $equipo]);
?>
