<?php
// Detalle de un cambio de configuración: devuelve los datos de ANTES y de DESPUÉS.
// En historial_configuracion solo se guardan los datos anteriores, por eso el "después" es:
//   - los datos anteriores del siguiente cambio del mismo equipo, o
//   - si no hubo otro cambio, los datos actuales del equipo.
require_once "sesion.php";
include "conexion.php";

// Campos que se guardan en el historial (los mismos que se pueden editar)
$campos = ["nombre", "tipo", "marca", "modelo", "numero_serie", "ubicacion", "responsable",
           "direccion_ip", "direccion_mac", "sistema_operativo", "configuracion"];

$id = isset($_POST["id_historial"]) ? (int) $_POST["id_historial"] : 0;
if ($id <= 0) {
    responder(["error" => "Seleccione un cambio de la tabla"]);
}

// 1. El cambio (datos de antes)
$stmt = $conn->prepare("SELECT * FROM historial_configuracion WHERE id_historial = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$lista = filas($stmt);
if (count($lista) == 0) {
    responder(["error" => "No existe el cambio con número " . $id]);
}
$cambio = $lista[0];
$idEquipo = (int) $cambio["id_equipo"];

// 2. El siguiente cambio del mismo equipo (sus datos anteriores = el "después" de este)
$stmt = $conn->prepare("SELECT * FROM historial_configuracion
                        WHERE id_equipo = ? AND id_historial > ?
                        ORDER BY id_historial LIMIT 1");
$stmt->bind_param("ii", $idEquipo, $id);
$stmt->execute();
$siguiente = filas($stmt);

if (count($siguiente) > 0) {
    $despues = $siguiente[0];
    $origen = "Después: datos guardados en el siguiente cambio (" . $despues["fecha_cambio"] . ")";
} else {
    // 3. Si no hubo otro cambio, el "después" son los datos actuales del equipo
    $stmt = $conn->prepare("SELECT * FROM equipo WHERE id_equipo = ?");
    $stmt->bind_param("i", $idEquipo);
    $stmt->execute();
    $equipo = filas($stmt);
    if (count($equipo) == 0) {
        responder(["error" => "No existe un equipo con ID " . $idEquipo]);
    }
    $despues = $equipo[0];
    $origen = "Después: datos actuales del equipo";
}

$antesCampos = [];
$despuesCampos = [];
foreach ($campos as $campo) {
    $antesCampos[$campo] = $cambio[$campo];
    $despuesCampos[$campo] = $despues[$campo];
}

// "direccion_ip, configuracion" -> ["direccion_ip", "configuracion"]
$cambiados = array_values(array_filter(array_map("trim", explode(",", (string) $cambio["campos_cambiados"]))));

responder([
    "id_historial" => (int) $cambio["id_historial"],
    "id_equipo" => $idEquipo,
    "fecha_cambio" => $cambio["fecha_cambio"],
    "campos_cambiados" => $cambiados,
    "antes" => $antesCampos,
    "despues" => $despuesCampos,
    "origen_despues" => $origen
]);
?>
