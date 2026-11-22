<?php
// Valida el usuario y la contraseña con una consulta preparada
session_start();
include "conexion.php";

$usuario = isset($_POST["usuario"]) ? trim($_POST["usuario"]) : "";
$clave = isset($_POST["clave"]) ? $_POST["clave"] : "";

if ($usuario == "" || $clave == "") {
    responder(["error" => "Ingrese el usuario y la contraseña"]);
}

// Los ? se reemplazan con bind_param: así nadie puede "inyectar" SQL desde el formulario
$sql = "SELECT id_usuario, usuario, nombre FROM usuario WHERE usuario = ? AND clave = SHA2(?, 256)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usuario, $clave);
$stmt->execute();
$lista = filas($stmt);

if (count($lista) == 1) {
    session_regenerate_id(true);
    $_SESSION["usuario"] = $lista[0]["usuario"];
    $_SESSION["nombre"] = $lista[0]["nombre"];
    responder(["success" => "Bienvenido"]);
} else {
    responder(["error" => "Usuario o contraseña incorrectos"]);
}
?>