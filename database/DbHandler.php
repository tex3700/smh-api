<?php

namespace database;
use Exception;
use PDO;
use PDOException;

class DbHandler
{
    private $pdo;

    public function __construct($connection, $host, $dbname, $user, $password, $charset)
    {
        $dsn = "$connection:host=$host;dbname=$dbname;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $password, $options);
        } catch (PDOException $e) {
            throw new Exception("DB connection failed: " . $e->getMessage());
        }
    }

    public function insert($table, $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders) ON DUPLICATE KEY UPDATE updated_at = NOW()";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    //Добавил оптимизированный метод сохранения
    public function batchInsert($table, array $data): bool
    {
        if (empty($data)) return false;

        $columns = implode(', ', array_keys($data[0]));
        $placeholders = '(' . implode(', ', array_fill(0, count($data[0]), '?')) . ')';
        $values = [];

        foreach ($data as $item) {
            $values = array_merge($values, array_values($item));
        }

        $sql = "INSERT INTO $table ($columns) VALUES " .
            implode(', ', array_fill(0, count($data), $placeholders)) . "ON DUPLICATE KEY UPDATE updated_at = NOW()";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values);
    }

    public function exists($table, $conditions): bool
    {
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "$key = :$key";
        }

        $sql = "SELECT COUNT(*) FROM $table WHERE " . implode(' AND ', $where);
        $stmt = $this->pdo->prepare($sql);

        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function migrate($migrateSql)
    {
        $sql = file_get_contents($migrateSql);
        $this->pdo->exec($sql);
    }

}