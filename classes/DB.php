<?php

namespace classes;

use PDO;

class DB {

    private $conn;
    private $host, $database, $login, $password;

    public function __construct() {
        $settings = require $_SERVER['DOCUMENT_ROOT'].'/settings.php';
        $this->host = $settings['host'];
        $this->database =  $settings['database'];
        $this->login =  $settings['login'];
        $this->password =  $settings['password'];
        $this->openConnect();

    }

    public function openConnect(): void
    {
        $this->conn = new PDO(
            "mysql:host=$this->host;dbname=$this->database",
            $this->login,
            $this->password
        );
    }

    public function exec($query, $queryParams=[], $option=PDO::FETCH_ASSOC) {
        $db = $this->conn->prepare($query);
        $db->execute($queryParams);
        return $db->fetch($option);
    }

    public function fetchAll($query, $queryParams=[], $option=PDO::FETCH_ASSOC) {
        $db = $this->conn->prepare($query);
        $db->execute($queryParams);
        return $db->fetchAll($option);
    }

}
