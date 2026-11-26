<?php
// Genera el reporte PDF de un equipo por su ID con PHPJasper (diseño reportes_jasper/reporte_equipo.jasper).
// Uso: models/reporte_pdf.php?id=N  (se ve en el navegador)
//      models/reporte_pdf.php?id=N&descargar=1  (se descarga como reporte_equipo_N.pdf)
// Es el único modelo que no responde JSON cuando todo sale bien: devuelve el PDF.
require_once "sesion.php";
include "conexion.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$descargar = (isset($_GET["descargar"]) && $_GET["descargar"] == "1");

if ($id <= 0) {
    responder(["error" => "Ingrese un ID de equipo válido"]);
}

// 1. El equipo debe existir
$stmt = $conn->prepare("SELECT id_equipo FROM equipo WHERE id_equipo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
if (count(filas($stmt)) == 0) {
    responder(["error" => "No existe un equipo con ID " . $id]);
}

// 2. Rutas absolutas: PHPJasper cambia la carpeta de trabajo (chdir) al ejecutar
$carpetaJasper = __DIR__ . "/../phpjasper";
$reporte = __DIR__ . "/../reportes_jasper/reporte_equipo.jasper";
$salida = __DIR__ . "/../reportes_jasper/salida";

if (!is_file($carpetaJasper . "/src/PHPJasper.php")) {
    responder(["error" => "No se encontró la carpeta phpjasper. Cópiela del proyecto de clase como indica el README"]);
}
if (!is_file($reporte)) {
    responder(["error" => "No se encontró el diseño del reporte (reportes_jasper/reporte_equipo.jasper)"]);
}
if (!is_dir($salida) && !mkdir($salida, 0777, true)) {
    responder(["error" => "No se pudo crear la carpeta reportes_jasper/salida"]);
}

// PHPJasper no trae autoload: se cargan sus archivos a mano
require_once $carpetaJasper . "/src/Exception/ErrorCommandExecutable.php";
require_once $carpetaJasper . "/src/Exception/InvalidCommandExecutable.php";
require_once $carpetaJasper . "/src/Exception/InvalidFormat.php";
require_once $carpetaJasper . "/src/Exception/InvalidInputFile.php";
require_once $carpetaJasper . "/src/Exception/InvalidResourceDirectory.php";
require_once $carpetaJasper . "/src/PHPJasper.php";

// 3. Nombre único para que dos personas no pisen el mismo archivo (JasperStarter le agrega .pdf)
$nombre = "equipo_" . $id . "_" . uniqid();
$pdf = $salida . "/" . $nombre . ".pdf";

// Los mismos datos de conexion.php. La clave solo se envía si no está vacía
// (si se envía vacía, JasperStarter toma la siguiente opción como si fuera la clave)
$conexion = [
    "driver" => "mysql",
    "host" => $servername,
    "username" => $username,
    "database" => $dbname,
    "port" => "3306"
];
if ($password != "") {
    $conexion["password"] = $password;
}

// 4. Se genera el PDF. Se usa el jasperstarter.bat de la carpeta win (el adaptado en clase para Java nuevo)
try {
        
    $binJasper = (strtoupper(substr(PHP_OS, 0, 3)) === "WIN") ? "/bin/jasperstarter/win" : "/bin/jasperstarter/bin";
    $jasper = new PHPJasper\PHPJasper($carpetaJasper . $binJasper);
    $jasper->process($reporte, $salida . "/" . $nombre, [
        "format" => ["pdf"],
        "params" => ["id_equipo" => $id],
        "db_connection" => $conexion
    ])->execute();
} catch (Exception $e) {
    if (is_file($pdf)) {
        unlink($pdf);
    }
    responder(["error" => "No se pudo generar el reporte. Revise que Java esté instalado. Detalle: " . trim($e->getMessage())]);
}

// 5. El PDF debe existir y no estar vacío
clearstatcache();
if (!is_file($pdf) || filesize($pdf) == 0) {
    if (is_file($pdf)) {
        unlink($pdf);
    }
    responder(["error" => "El reporte se generó vacío. Intente de nuevo"]);
}

// 6. Se envía el PDF (en el navegador o como descarga) y se borra
header("Content-Type: application/pdf");
header("Content-Disposition: " . ($descargar ? "attachment" : "inline") . "; filename=\"reporte_equipo_" . $id . ".pdf\"");
header("Content-Length: " . filesize($pdf));
readfile($pdf);
unlink($pdf);
exit;
?>
