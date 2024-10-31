<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
$con = require $_SERVER['DOCUMENT_ROOT'].'/config/connection.php';

try {
    $dsn = 'mysql:host='.$con['host'].';dbname='.$con['dbname'];
    $usuari = $con['username'];
    $password = $con['password'];
    $pdo = new PDO($dsn, $usuari, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexio Establerta";
} catch (PDOException $e) {
    echo 'Falló la conexión: ' . $e->getMessage();
    exit();
}

?>
