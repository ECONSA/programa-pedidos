<?php
$departamentos = [
    "Alta Verapaz",
    "Baja Verapaz",
    "Chimaltenango",
    "Chiquimula",
    "El Progreso",
    "Escuintla",
    "Guatemala",
    "Huehuetenango",
    "Izabal",
    "Jalapa",
    "Jutiapa",
    "Petén",
    "Quetzaltenango",
    "Quiché",
    "Retalhuleu",
    "Sacatepéquez",
    "San Marcos",
    "Santa Rosa",
    "Sololá",
    "Suchitepéquez",
    "Totonicapán",
    "Zacapa"
];

$json = [];

if (isset($_GET['q'])) {
    $search = strtolower($_GET['q']);
    
    foreach ($departamentos as $departamento) {
        if (strpos(strtolower($departamento), $search) !== false) {
            $json[] = ['id' => $departamento, 'text' => $departamento];
        }
    }
} else {
    foreach ($departamentos as $departamento) {
        $json[] = ['id' => $departamento, 'text' => $departamento];
    }
}

echo json_encode($json);
?>
