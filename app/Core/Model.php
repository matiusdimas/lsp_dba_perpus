<?php

require_once __DIR__ . '/../Config/Database.php';

abstract class Model {
    protected $db;

    public function __construct(PDO $db = null) {
        $this->db = $db ?? Database::getInstance();
    }

    public function getDb(): PDO {
        return $this->db;
    }
}
