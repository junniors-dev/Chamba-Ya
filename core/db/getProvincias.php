<?php

    require_once __DIR__ . '/../config/autoload.php';

    if (!isset($_GET['id'])) {
        echo json_encode([]);
        exit();
    }

    $db = new Database();
    $conn = $db->getConnection();

    $idDepartamento = (int) $_GET['id'];

    $sql = "SELECT idProvincia, nombre
            FROM provincia
            WHERE idDepartamento = :idDepartamento";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idDepartamento', $idDepartamento, PDO::PARAM_INT);
    $stmt->execute();

    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($datos);
?>