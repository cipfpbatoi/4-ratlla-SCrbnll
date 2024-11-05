<?php

namespace Joc4enRatlla\Models;

use \PDO;

/**
 * Clase User para la gestión de usuarios en la base de datos.
 */
class User {
    /**
     * Conexión a la base de datos.
     *
     * @var PDO
     */
    private $db;

    /**
     * Constructor de la clase User.
     *
     * @param PDO $db Conexión a la base de datos.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Encuentra un usuario en la base de datos por su nombre de usuario.
     *
     * @param string $nom_usuari Nombre de usuario.
     * @return array|null Datos del usuario como array asociativo o null si no existe.
     */
    public function findUserByUsername($nom_usuari) {
        $query = "SELECT * FROM usuaris WHERE nom_usuari = :nom_usuari";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom_usuari', $nom_usuari);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * @param string $nom_usuari Nombre de usuario.
     * @param string $contrasenya Contraseña del usuario, que será encriptada.
     * @return bool True si el usuario fue creado exitosamente, false en caso contrario.
     */
    public function createUser($nom_usuari, $contrasenya) {
        $query = "INSERT INTO usuaris (nom_usuari, contrasenya) VALUES (:nom_usuari, :contrasenya)";
        $stmt = $this->db->prepare($query);
        $hashedPassword = password_hash($contrasenya, PASSWORD_DEFAULT);
        $stmt->bindParam(':nom_usuari', $nom_usuari);
        $stmt->bindParam(':contrasenya', $hashedPassword);
        return $stmt->execute(); 
    }
}
