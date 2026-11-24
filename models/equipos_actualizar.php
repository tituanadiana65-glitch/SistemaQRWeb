<?php
// Edita un equipo. Antes de cambiarlo guarda sus datos ANTERIORES en
// historial_configuracion junto con la lista de campos que cambiaron.
// El ID no se cambia nunca porque ya está impreso en la etiqueta QR.
require_once "sesion.php";
include "conexion.php";
include "equipos_validar.php";

$id = isset($_POST["id_equipo"]) ? (int) $_POST["id_equipo"] : 0;
$nuevo = leerEquipoDelFormulario();

// 1. Datos actuales (antes del cambio)
$stmt = $conn->prepare("SELECT * FROM equipo WHERE id_equipo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$lista = filas($stmt);
if (count($lista) == 0) {
    responder(["error" => "No existe un equipo con ID " . $id]);
}
$anterior = $lista[0];

// 2. Qué campos cambiaron
$cambiados = [];
foreach ($nuevo as $campo => $valor) {
    if ((string) $anterior[$campo] !== $valor) {
        $cambiados[] = $campo;
    }
}
if (count($cambiados) == 0) {
    responder(["success" => "No hubo cambios en el equipo"]);
}
$camposCambiados = implode(", ", $cambiados);

// 3. Se guarda el historial y se actualiza el equipo juntos (transacción):
//    si una de las dos cosas falla, no se guarda ninguna
$conn->begin_transaction();

$sql = "INSERT INTO historial_configuracion (id_equipo, campos_cambiados, nombre, tipo, marca, modelo,
            numero_serie, ubicacion, responsable, direccion_ip, direccion_mac, sistema_operativo, configuracion)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssssssssss", $id, $camposCambiados, $anterior["nombre"], $anterior["tipo"],
    $anterior["marca"], $anterior["modelo"], $anterior["numero_serie"], $anterior["ubicacion"],
    $anterior["responsable"], $anterior["direccion_ip"], $anterior["direccion_mac"],
    $anterior["sistema_operativo"], $anterior["configuracion"]);
$okHistorial = $stmt->execute();

$sql = "UPDATE equipo SET nombre = ?, tipo = ?, marca = ?, modelo = ?, numero_serie = ?, ubicacion = ?,
            responsable = ?, direccion_ip = ?, direccion_mac = ?, sistema_operativo = ?, configuracion = ?
        WHERE id_equipo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssssi", $nuevo["nombre"], $nuevo["tipo"], $nuevo["marca"], $nuevo["modelo"],
    $nuevo["numero_serie"], $nuevo["ubicacion"], $nuevo["responsable"], $nuevo["direccion_ip"],
    $nuevo["direccion_mac"], $nuevo["sistema_operativo"], $nuevo["configuracion"], $id);
$okEquipo = $stmt->execute();

if ($okHistorial && $okEquipo) {
    $conn->commit();
    responder(["success" => "Se actualizó el equipo con ID " . $id . " (cambió: " . $camposCambiados . ")"]);
} else {
    $conn->rollback();
    responder(["error" => "No se pudo actualizar el equipo"]);
}
?>
