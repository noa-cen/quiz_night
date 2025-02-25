<?php

class DatabaseConnection {
    protected $pdo;

    // ($host = "localhost", $dbname = "noa-cengarle_quiz_night", $user = "quiz_night", $password = "Utilisateur123")

    public function __construct($host = "localhost", $dbname = "quiz_night", $user = "root", $password = "")
    {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function getPdo() {
        return $this->pdo;
    }
}