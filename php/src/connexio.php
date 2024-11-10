<?php
function getDB() {
    static $pdo = null;

    if ($pdo === null) {
        $con = require $_SERVER['DOCUMENT_ROOT'].'/config/connection.php';
        try {
            $dsn = 'mysql:host='.$con['host'].';dbname='.$con['dbname'];
            $usuari = $con['username'];
            $password = $con['password'];
            $pdo = new PDO($dsn, $usuari, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo "Connexió establerta correctament"; // Missatge de prova
        } catch (PDOException $e) {
            error_log('Falló la conexión: ' . $e->getMessage());
            throw new Exception("Error de connexió a la base de dades");
        }
    }

    return $pdo;
}

getDB();

?>
