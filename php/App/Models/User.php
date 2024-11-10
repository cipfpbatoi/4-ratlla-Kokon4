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

    // Funció per obtenir la connexió a la base de dades
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

    // Mètode per obtenir un usuari per nom d'usuari
    public static function getByNomUsuari($nom_usuari)
    {
        $db = self::getDB(); // Obtenim la connexió a la base de dades
        $stmt = $db->prepare("SELECT * FROM usuaris WHERE nom_usuari = :nom_usuari");
        $stmt->bindParam(':nom_usuari', $nom_usuari);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $userObj = new self($user['nom_usuari'], $user['contrasenya']);
            return $userObj;
        }

        return null; // Si no s'ha trobat l'usuari
    }

    // Mètode per guardar un nou usuari a la base de dades
    public function save()
    {
        try {
            $db = self::getDB(); // Obtenim la connexió amb la base de dades

            // Prepara la consulta SQL per inserir el nou usuari
            $stmt = $db->prepare("INSERT INTO usuaris (nom_usuari, contrasenya) VALUES (:nom_usuari, :contrasenya)");
            $stmt->bindParam(':nom_usuari', $this->nom_usuari);
            $stmt->bindParam(':contrasenya', $this->contrasenya);

            // Executa la consulta
            $stmt->execute();
            
            return true; // Retorna `true` si l'usuari s'ha guardat correctament
        } catch (\PDOException $e) {
            // Registra l'error o llança una excepció
            error_log("Error al guardar l'usuari: " . $e->getMessage());
            return false; // Retorna `false` si hi ha hagut un error
        }
    }

    // Getters per obtenir les dades de l'usuari
    public function getNomUsuari()
    {
        return $this->nom_usuari;
    }

    public function getContrasenya()
    {
        return $this->contrasenya;
    }
}
