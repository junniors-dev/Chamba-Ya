<?php
require_once  __DIR__ . '/conexion.php';

$idDepartamento = $_GET['id'];

$sql = "SELECT idProvincia, nombre FROM provincia WHERE idDepartamento = '$idDepartamento'";
$resultado = $enlace->query($sql);

$datos = [];

while ($row = $resultado->fetch_assoc()) {
    $datos[] = $row;
}

echo json_encode($datos);
?>