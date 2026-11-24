<?php
// Registra un equipo nuevo y devuelve el ID que le dio la base (el que irá en el QR)
require_once "sesion.php";
include "conexion.php";
include "equipos_validar.php";

$e = leerEquipoDelFormulario();

$sql = "INSERT INTO equipo (nombre, tipo, marca, modelo, numero_serie, ubicacion,
            responsable, direccion_ip, direccion_mac, sistema_operativo, configuracion)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssss", $e["nombre"], $e["tipo"], $e["marca"], $e["modelo"],
    $e["numero_serie"], $e["ubicacion"], $e["responsable"], $e["direccion_ip"],
    $e["direccion_mac"], $e["sistema_operativo"], $e["configuracion"]);

if ($stmt->execute()) {
    responder(["success" => "Se registró el equipo con ID " . $conn->insert_id, "id_equipo" => $conn->insert_id]);
} else {
    responder(["error" => "No se pudo registrar el equipo: " . $stmt->error]);
}
?>
