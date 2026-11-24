<?php
// Vuelve a activar un equipo que estaba dado de baja
require_once "sesion.php";
include "conexion.php";

$id = isset($_POST["id_equipo"]) ? (int) $_POST["id_equipo"] : 0;

$sql = "UPDATE equipo SET estado = 'ACTIVO', fecha_baja = NULL, motivo_baja = NULL
        WHERE id_equipo = ? AND estado = 'DADO_DE_BAJA'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    responder(["success" => "El equipo con ID " . $id . " fue reactivado"]);
} else {
    responder(["error" => "El equipo no existe o ya estaba activo"]);
}
?>
