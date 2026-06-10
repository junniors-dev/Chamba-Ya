<?php

    require_once __DIR__ . '/database.php';

    if (!isset($_GET['id'])) {
        echo json_encode([]);
        exit();
    }

    $db = new Database();
    $conn = $db->getConnection();

    $idProvincia = (int) $_GET['id'];

    $sql = "SELECT idDistrito, nombre
            FROM distrito
            WHERE idProvincia = :idProvincia";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idProvincia', $idProvincia, PDO::PARAM_INT);
    $stmt->execute();

    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($datos);
?>