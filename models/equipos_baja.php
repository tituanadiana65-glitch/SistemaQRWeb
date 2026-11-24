<?php
// Da de baja un equipo (baja lógica): no se borra, se marca como DADO_DE_BAJA
// con la fecha y el motivo. Así sus lecturas e historial no se pierden.
require_once "sesion.php";
include "conexion.php";

$id = isset($_POST["id_equipo"]) ? (int) $_POST["id_equipo"] : 0;
$motivo = isset($_POST["motivo"]) ? trim($_POST["motivo"]) : "";

if ($motivo == "") {
    responder(["error" => "Escriba el motivo de la baja"]);
}
if (mb_strlen($motivo) > 200) {
    responder(["error" => "El motivo puede tener máximo 200 caracteres"]);
}

$sql = "UPDATE equipo SET estado = 'DADO_DE_BAJA', fecha_baja = NOW(), motivo_baja = ?
        WHERE id_equipo = ? AND estado = 'ACTIVO'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $motivo, $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    responder(["success" => "El equipo con ID " . $id . " fue dado de baja"]);
} else {
    responder(["error" => "El equipo no existe o ya estaba dado de baja"]);
}
?>
