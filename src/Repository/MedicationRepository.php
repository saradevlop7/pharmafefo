<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Entity/Medication.php';

class MedicationRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM medications ORDER BY name');
        $medications = [];
        while ($row = $stmt->fetch()) {
            $medications[] = $this->hydrate($row);
        }
        return $medications;
    }

    public function findById(int $id): ?Medication
    {
        $stmt = $this->db->prepare('SELECT * FROM medications WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function create(Medication $medication): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO medications (name, description, unit_price) VALUES (:name, :description, :unit_price)'
        );
        return $stmt->execute([
            'name' => $medication->getName(),
            'description' => $medication->getDescription(),
            'unit_price' => $medication->getUnitPrice(),
        ]);
    }

    public function update(Medication $medication): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE medications SET name = :name, description = :description, unit_price = :unit_price WHERE id = :id'
        );
        return $stmt->execute([
            'name' => $medication->getName(),
            'description' => $medication->getDescription(),
            'unit_price' => $medication->getUnitPrice(),
            'id' => $medication->getId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM medications WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    private function hydrate(array $row): Medication
    {
        return new Medication(
            id: (int) $row['id'],
            name: $row['name'],
            description: $row['description'],
            unitPrice: (float) $row['unit_price'],
            createdAt: $row['created_at']
        );
    }
}
