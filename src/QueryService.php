<?php

require_once 'Database.php';

class QueryService
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->pdo->query("
            SELECT u.name as user_name, q.*
            FROM users u
            LEFT JOIN queries q ON q.user_id = u.id
            ORDER BY u.name
        ")->fetchAll();
    }

    /**
     * @return array
     */
    public function getUsers(): array
    {
        return $this->pdo->query("SELECT * FROM users")->fetchAll();
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function getById(string $id): mixed
    {
        $stmt = $this->pdo->prepare("SELECT * FROM queries WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * @param $data
     * @return bool
     */
    public function save($data): bool
    {
        if (!empty($data['id'])) {
            $stmt = $this->pdo->prepare("
                UPDATE queries SET title=?, sql_text=?, user_id=?
                WHERE id=?
            ");
            return $stmt->execute([
                $data['title'],
                $data['sql_text'],
                $data['user_id'],
                $data['id']
            ]);
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO queries (title, sql_text, user_id)
                VALUES (?, ?, ?)
            ");
            return $stmt->execute([
                $data['title'],
                $data['sql_text'],
                $data['user_id']
            ]);
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM queries WHERE id=?");
        return $stmt->execute([$id]);
    }

    /**
     * @throws Exception
     */
    public function executeQuery($sql): array
    {
        if (!preg_match('/^\s*select/i', $sql)) {
            throw new Exception("Разрешены только SELECT запросы");
        }

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}
