<?php
require_once  __DIR__ . '/conexion.php';

$idProvincia = $_GET['id'];

$sql = "SELECT idDistrito, nombre FROM distrito WHERE idProvincia = '$idProvincia'";
$resultado = $enlace->query($sql);

$datos = [];

while ($row = $resultado->fetch_assoc()) {
    $datos[] = $row;
}

echo json_encode($datos);
?>