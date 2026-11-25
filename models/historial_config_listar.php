<?php
// Lista el historial de cambios de configuración de los equipos (el más reciente primero).
// Cada fila se guardó al editar un equipo (Gestión de equipos o Lectura con cámara web).
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

// El nombre se toma de la tabla equipo (el nombre actual del equipo).
// "hasta" incluye todo ese día: se compara con el día siguiente
$sql = "SELECT h.id_historial, h.fecha_cambio, h.campos_cambiados, e.id_equipo, e.nombre
        FROM historial_configuracion h
        INNER JOIN equipo e ON e.id_equipo = h.id_equipo
        WHERE (? = 0 OR h.id_equipo = ?)
          AND (? = '' OR h.fecha_cambio >= ?)
          AND (? = '' OR h.fecha_cambio < DATE_ADD(?, INTERVAL 1 DAY))
        ORDER BY h.fecha_cambio DESC, h.id_historial DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iissss", $id, $id, $desde, $desde, $hasta, $hasta);
$stmt->execute();

responder(filas($stmt));
?>
