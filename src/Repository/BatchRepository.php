<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Entity/Batch.php';
require_once __DIR__ . '/../Enum/BatchStatus.php';

class BatchRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query(
            'SELECT b.*, m.name AS medication_name 
             FROM batches b 
             JOIN medications m ON b.medication_id = m.id 
             ORDER BY b.expiry_date ASC'
        );
        $batches = [];
        while ($row = $stmt->fetch()) {
            $batches[] = $this->hydrate($row);
        }
        return $batches;
    }

    public function findById(int $id): ?Batch
    {
        $stmt = $this->db->prepare(
            'SELECT b.*, m.name AS medication_name 
             FROM batches b 
             JOIN medications m ON b.medication_id = m.id 
             WHERE b.id = :id'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return $this->hydrate($row);
    }

    public function findByMedicationId(int $medicationId): array
    {
        $stmt = $this->db->prepare(
            'SELECT b.*, m.name AS medication_name 
             FROM batches b 
             JOIN medications m ON b.medication_id = m.id 
             WHERE b.medication_id = :medication_id 
             ORDER BY b.expiry_date ASC'
        );
        $stmt->execute(['medication_id' => $medicationId]);
        $batches = [];
        while ($row = $stmt->fetch()) {
            $batches[] = $this->hydrate($row);
        }
        return $batches;
    }

    public function findAvailableByMedication(int $medicationId): array
    {
        $stmt = $this->db->prepare(
            "SELECT b.*, m.name AS medication_name 
             FROM batches b 
             JOIN medications m ON b.medication_id = m.id 
             WHERE b.medication_id = :medication_id 
             AND b.status = 'AVAILABLE' 
             AND b.quantity > 0 
             ORDER BY b.expiry_date ASC"
        );
        $stmt->execute(['medication_id' => $medicationId]);
        $batches = [];
        while ($row = $stmt->fetch()) {
            $batches[] = $this->hydrate($row);
        }
        return $batches;
    }

    public function findCriticalBatches(): array
    {
        $stmt = $this->db->prepare(
            "SELECT b.*, m.name AS medication_name 
             FROM batches b 
             JOIN medications m ON b.medication_id = m.id 
             WHERE b.status != 'EXPIRED' 
             AND b.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) 
             ORDER BY b.expiry_date ASC"
        );
        $stmt->execute();
        $batches = [];
        while ($row = $stmt->fetch()) {
            $batches[] = $this->hydrate($row);
        }
        return $batches;
    }

    public function findExpiredBatches(): array
    {
        $stmt = $this->db->query(
            "SELECT b.*, m.name AS medication_name, m.unit_price 
             FROM batches b 
             JOIN medications m ON b.medication_id = m.id 
             WHERE b.status = 'EXPIRED' 
             ORDER BY b.expiry_date DESC"
        );
        $batches = [];
        while ($row = $stmt->fetch()) {
            $batch = $this->hydrate($row);
            $batches[] = [
                'batch' => $batch,
                'unit_price' => (float) $row['unit_price'],
            ];
        }
        return $batches;
    }

    public function create(Batch $batch): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO batches (medication_id, batch_number, expiry_date, quantity, status) 
             VALUES (:medication_id, :batch_number, :expiry_date, :quantity, :status)'
        );
        return $stmt->execute([
            'medication_id' => $batch->getMedicationId(),
            'batch_number' => $batch->getBatchNumber(),
            'expiry_date' => $batch->getExpiryDate(),
            'quantity' => $batch->getQuantity(),
            'status' => $batch->getStatus()->value,
        ]);
    }

    public function update(Batch $batch): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE batches SET quantity = :quantity, status = :status WHERE id = :id'
        );
        return $stmt->execute([
            'quantity' => $batch->getQuantity(),
            'status' => $batch->getStatus()->value,
            'id' => $batch->getId(),
        ]);
    }

    public function recordMovement(int $batchId, string $type, int $quantity, int $userId): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO stock_movements (batch_id, type, quantity, user_id) 
             VALUES (:batch_id, :type, :quantity, :user_id)'
        );
        return $stmt->execute([
            'batch_id' => $batchId,
            'type' => $type,
            'quantity' => $quantity,
            'user_id' => $userId,
        ]);
    }

    private function hydrate(array $row): Batch
    {
        return new Batch(
            id: (int) $row['id'],
            medicationId: (int) $row['medication_id'],
            batchNumber: $row['batch_number'],
            expiryDate: $row['expiry_date'],
            quantity: (int) $row['quantity'],
            status: BatchStatus::from($row['status']),
            receivedAt: $row['received_at'],
            medicationName: $row['medication_name'] ?? null
        );
    }
}
