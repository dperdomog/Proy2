<?php

class Conexion {
// Database connection parameters
private $host = "localhost";
private $username = "root";
private $password = "";
private $db_name = "proyecto2";
public $conn;
// Create connection

public function obtenerConexion(){

        $this->conn = null;
        try{
        $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
        $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
        echo "Error de conexion a base de datos: " . $exception->getMessage();
        }
        return $this->conn;
        }
    }

?>