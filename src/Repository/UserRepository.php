<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Entity/User.php';

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByUsername(string $username): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM users ORDER BY username');
        $users = [];
        while ($row = $stmt->fetch()) {
            $users[] = $this->hydrate($row);
        }
        return $users;
    }

    public function create(User $user): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (username, password, role) VALUES (:username, :password, :role)'
        );
        return $stmt->execute([
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
        ]);
    }

    public function update(User $user): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET username = :username, role = :role WHERE id = :id'
        );
        return $stmt->execute([
            'username' => $user->getUsername(),
            'role' => $user->getRole(),
            'id' => $user->getId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    private function hydrate(array $row): User
    {
        return new User(
            id: (int) $row['id'],
            username: $row['username'],
            password: $row['password'],
            role: $row['role'],
            createdAt: $row['created_at']
        );
    }
}
