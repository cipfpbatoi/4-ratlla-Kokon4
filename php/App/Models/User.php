<?php

namespace Joc4enRatlla\Models;

use PDO;
use Exception;

class User
{
    private $nom_usuari;
    private $contrasenya;

    public function __construct($nom_usuari, $contrasenya)
    {
        $this->nom_usuari = $nom_usuari;
        $this->contrasenya = password_hash($contrasenya, PASSWORD_DEFAULT);
    }
    private static function getDB()
    {
        static $pdo = null;

        if ($pdo === null) {
            $con = require $_SERVER['DOCUMENT_ROOT'].'/config/connection.php';
            try {
                $dsn = 'mysql:host='.$con['host'].';dbname='.$con['dbname'];
                $usuari = $con['username'];
                $password = $con['password'];
                $pdo = new PDO($dsn, $usuari, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                error_log('Falló la conexió: ' . $e->getMessage());
                throw new Exception("Error de connexió a la base de dades");
            }
        }

        return $pdo;
    }
    public static function getByNomUsuari($nom_usuari)
    {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM usuaris WHERE nom_usuari = :nom_usuari");
        $stmt->bindParam(':nom_usuari', $nom_usuari);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $userObj = new self($user['nom_usuari'], '');
            $userObj->contrasenya = $user['contrasenya'];
            return $userObj;
        }

        return null;
    }

    public function save()
    {
        try {
            $db = self::getDB();
            $stmt = $db->prepare("INSERT INTO usuaris (nom_usuari, contrasenya) VALUES (:nom_usuari, :contrasenya)");
            $stmt->bindParam(':nom_usuari', $this->nom_usuari);
            $stmt->bindParam(':contrasenya', $this->contrasenya);
            $stmt->execute();
            
            return true;
        } catch (\PDOException $e) {
 
            error_log("Error al guardar l'usuari: " . $e->getMessage());
            return false;
        }
    }
    public function getNomUsuari()
    {
        return $this->nom_usuari;
    }

    public function getContrasenya()
    {
        return $this->contrasenya;
    }
}
