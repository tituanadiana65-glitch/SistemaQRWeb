<?php
// Lee los datos del equipo enviados por el formulario y los valida.
// Si algo está mal, responde el error y termina.
function leerEquipoDelFormulario() {
    $campos = ["nombre", "tipo", "marca", "modelo", "numero_serie", "ubicacion",
               "responsable", "direccion_ip", "direccion_mac", "sistema_operativo", "configuracion"];
    $equipo = [];
    foreach ($campos as $campo) {
        $equipo[$campo] = isset($_POST[$campo]) ? trim($_POST[$campo]) : "";
    }
    $equipo["direccion_mac"] = strtoupper($equipo["direccion_mac"]);

    if ($equipo["nombre"] == "" || $equipo["tipo"] == "") {
        responder(["error" => "El nombre y el tipo del equipo son obligatorios"]);
    }
    if (mb_strlen($equipo["nombre"]) > 100) {
        responder(["error" => "El nombre puede tener máximo 100 caracteres"]);
    }
    if ($equipo["direccion_ip"] != "" && !filter_var($equipo["direccion_ip"], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        responder(["error" => "La dirección IP no es válida (ejemplo: 192.168.10.11)"]);
    }
    if ($equipo["direccion_mac"] != "" && !preg_match('/^([0-9A-F]{2}[:-]){5}[0-9A-F]{2}$/', $equipo["direccion_mac"])) {
        responder(["error" => "La dirección MAC no es válida (ejemplo: A4:BB:6D:12:34:01)"]);
    }
    return $equipo;
}
?>
