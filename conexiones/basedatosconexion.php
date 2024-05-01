<?php

/**
 * Clase de conexión a la base de datos.
 */
class Connection
{
    /**
     * Establece la conexión a la base de datos MySQL.
     *
     * @return mysqli|false Devuelve la conexión establecida o false si falla la conexión.
     */
    static public function dbConnection()
    {
        $servername = "localhost";
        $database = "quibixpc";
        $username = "root";
        $password = "";

        $conn = mysqli_connect($servername, $username, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        return $conn;
    }

    /**
     * Establece la conexión PDO a la base de datos MySQL.
     *
     * @return PDO|false Devuelve la conexión establecida o false si falla la conexión.
     */
    static public function conectado()
    {
        try {
            $link = new PDO(
                "mysql:host=localhost;dbname=" . connection::dbConnection()['quibixpc'],
                connection::dbConnection()['root'],
                connection::dbConnection()['']
            );

            $link->exec("set names utf8");
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }

        return $link;
    }
}

?>
