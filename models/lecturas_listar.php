<?php
// Lista el historial de lecturas de códigos QR (la más reciente primero).
// Filtra, si se indican, por ID de equipo y por rango de fechas (desde / hasta).
require_once "sesion.php";
include "conexion.php";

$id = isset($_POST["id_equipo"]) ? (int) $_POST["id_equipo"] : 0;
$desde = isset($_POST["desde"]) ? trim($_POST["desde"]) : "";
$hasta = isset($_POST["hasta"]) ? trim($_POST["hasta"]) : "";

// Revisa que la fecha venga como AAAA-MM-DD y que exista (vacía = sin filtro)
function fechaValida($fecha) {
    if ($fecha == "") {
        return true;
    }
    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $fecha, $partes)) {
        return false;
    }
    return checkdate((int) $partes[2], (int) $partes[3], (int) $partes[1]);
}

if ($id < 0) {
    responder(["error" => "Ingrese un ID de equipo válido"]);
}
if (!fechaValida($desde) || !fechaValida($hasta)) {
    responder(["error" => "Las fechas no son válidas"]);
}
if ($desde != "" && $hasta != "" && $desde > $hasta) {
    responder(["error" => "La fecha Desde no puede ser mayor que la fecha Hasta"]);
}

// "hasta" incluye todo ese día: se compara con el día siguiente
$sql = "SELECT l.id_lectura, l.fecha_lectura, e.id_equipo, e.nombre, e.tipo, e.ubicacion, e.estado
        FROM lectura_qr l
        INNER JOIN equipo e ON e.id_equipo = l.id_equipo
        WHERE (? = 0 OR l.id_equipo = ?)
          AND (? = '' OR l.fecha_lectura >= ?)
          AND (? = '' OR l.fecha_lectura < DATE_ADD(?, INTERVAL 1 DAY))
        ORDER BY l.fecha_lectura DESC, l.id_lectura DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iissss", $id, $id, $desde, $desde, $hasta, $hasta);
$stmt->execute();

responder(filas($stmt));
?>
