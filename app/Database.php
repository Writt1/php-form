<?php

namespace app;

use PDO;
use PDOException;


class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $host = 'mysql4';
        $db   = 'form_db';
        $user = 'root';
        $pass = 'root';
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die("Ошибка подключения к базе: " . $e->getMessage());
        }
    }

    public function insert(string $table, array $data): int
    {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_map(fn($k) => ":$k", array_keys($data)));

        $stmt = $this->pdo->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
        $stmt->execute($data);

        return (int)$this->pdo->lastInsertId();
    }

    public function findOne(string $table, array $conditions): ?array
    {
        $where = implode(' AND ', array_map(fn($k) => "$k = :$k", array_keys($conditions)));
        $stmt = $this->pdo->prepare("SELECT * FROM $table WHERE $where LIMIT 1");
        $stmt->execute($conditions);
        return $stmt->fetch() ?: null;
    }

    public function update(string $table, array $data, array $conditions): int
    {
        $set = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($data)));
        $where = implode(' AND ', array_map(fn($k) => "$k = :cond_$k", array_keys($conditions)));

        $params = $data;
        foreach ($conditions as $k => $v) {
            $params["cond_$k"] = $v;
        }

        $stmt = $this->pdo->prepare("UPDATE $table SET $set WHERE $where");
        $stmt->execute($params);
        return $stmt->rowCount();
    }

}