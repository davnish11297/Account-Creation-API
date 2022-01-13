<?php

class Database
{
    private $db_host = 'localhost';
    private $db_name = 'account_creation';
    private $db_username = 'root';
    private $db_password = '';

    public function dbConnection()
    {
        try {
            $connection = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name,$this->db_username,$this->db_password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        } catch (PDOException $ex) {
            echo "Connection error: ".$ex->getMessage(); 
            exit;
        }
    }
}
