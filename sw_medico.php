<?php
require __DIR__."/models/Medico.php";

// Recogida de parámetros con POST (AJAX Fetch ECMAScript 6)
$data = json_decode(file_get_contents('php://input'), true);
//echo "<pre>";
//var_dump($data);
//echo "</pre>";

// Recogida de parámetros con POST (AJAX JQuery)
$action = isset($_POST["action"])? $_POST["action"]:"";
$filters = isset($_POST["filters"])? $_POST["filters"]:[];
$arrMedico = isset($_POST["medico"])? $_POST["medico"]:[];
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

// Inicialización de variables
$success = true;
$data = [];
$msg = "";

// Selección de la acción elegida
try {
    switch ($action){
        case "get":
            $data = Medico::find($filters);
            $msg = "Listado de médicos";
            break;

        case "insert":
            $medico = new Medico($arrMedico["sip"], $arrMedico["nombre"]);
            if ($medico->insert()) {
                $msg = "Médico insertado correctamente.";
            } else {
                $success = false;
                $msg = "Error al insertar el médico.";
            }
            break;

        default:
            $success = false;
            $data = [];
            $msg = "Opción no soportada.";
        }
} catch (Exception $e) {
    $success = false;
    $msg = $e->getMessage();
}

$salida = array(
	"data" => $data,
	"msg" => $msg,
	"success" => $success
);

// Verifica que no falle la codificación en el JSON
if ($salida= json_encode($salida)){
    echo $salida;

} else {
    $salida = array(
        "data" => [],
        "msg" => "Error al parsear el JSON",
        "success" => false
    );

    echo json_encode($salida);
}

