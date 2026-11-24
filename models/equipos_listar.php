<?php
// Lista los equipos. Filtra por texto (ID, nombre, tipo o ubicación)
// y, si no se marca "incluir bajas", solo muestra los activos.
require_once "sesion.php";
include "conexion.php";

$buscar = isset($_POST["buscar"]) ? trim($_POST["buscar"]) : "";
$incluirBajas = (isset($_POST["incluir_bajas"]) && $_POST["incluir_bajas"] == "1") ? 1 : 0;
$patron = "%" . $buscar . "%";

$sql = "SELECT * FROM equipo
        WHERE (? = 1 OR estado = 'ACTIVO')
          AND (? = '' OR CAST(id_equipo AS CHAR) = ? OR nombre LIKE ? OR tipo LIKE ? OR ubicacion LIKE ?)
        ORDER BY id_equipo";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssss", $incluirBajas, $buscar, $buscar, $patron, $patron, $patron);
$stmt->execute();

responder(filas($stmt));
?>
