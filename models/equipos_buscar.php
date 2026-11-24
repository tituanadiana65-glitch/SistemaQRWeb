<?php
// Busca un equipo por su ID (el número que va dentro del QR)
// y cuenta cuántas veces se ha leído su código
require_once "sesion.php";
include "conexion.php";

$id = isset($_POST["id_equipo"]) ? (int) $_POST["id_equipo"] : 0;
if ($id <= 0) {
    responder(["error" => "Ingrese un ID de equipo válido"]);
}

$stmt = $conn->prepare("SELECT * FROM equipo WHERE id_equipo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$lista = filas($stmt);

if (count($lista) == 0) {
    responder(["error" => "No existe un equipo con ID " . $id]);
}

$stmt = $conn->prepare("SELECT COUNT(*) AS cantidad FROM lectura_qr WHERE id_equipo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$conteo = filas($stmt);

$equipo = $lista[0];
$equipo["veces_leido"] = (int) $conteo[0]["cantidad"];
responder($equipo);
?>
