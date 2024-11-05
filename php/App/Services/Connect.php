<?php

namespace Joc4enRatlla\Services;

/**
 * Clase Connect para gestionar la conexión a la base de datos.
 */
class Connect {
    /**
     * Conexión a la base de datos.
     *
     * @var \PDO
     */
    private \PDO $connection;

    /**
     * Constructor de la clase Connect.
     *
     * Establece una conexión a la base de datos utilizando la configuración proporcionada.
     * Si ya existe una conexión en la sesión, la deserializa y la utiliza.
     *
     * @param array $dbConfig Configuración de la base de datos que incluye 'host', 'dbname', 'username' y 'password'.
     */
    public function __construct($dbConfig)
    {
        if (isset($_SESSION['connection'])) {
            $this->connection = unserialize($_SESSION['connection'], [Connect::class]);
            return;
        }

        try {
            $dsn = "mysql:host=" . $dbConfig['host'] . ";dbname=" . $dbConfig['dbname'];
            $db = new \PDO($dsn, $dbConfig['username'], $dbConfig['password']);
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Error de connexió: " . $e->getMessage());
        }

        $this->connection = $db; 
    }

    /**
     * Obtiene la conexión a la base de datos.
     *
     * @return \PDO Objeto de conexión a la base de datos.
     */
    public function getConnection(): \PDO {
        return $this->connection;
    }
}
