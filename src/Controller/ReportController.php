<?php

require_once __DIR__ . '/../Repository/BatchRepository.php';

class ReportController
{
    private BatchRepository $batchRepository;

    public function __construct()
    {
        $this->batchRepository = new BatchRepository();
    }

    public function losses(): void
    {
        $this->requireRole(['ADMIN', 'PHARMACIEN']);
        $expiredData = $this->batchRepository->findExpiredBatches();

        $totalLoss = 0.0;
        $reportLines = [];

        foreach ($expiredData as $item) {
            $batch = $item['batch'];
            $unitPrice = $item['unit_price'];
            // Pour les pertes, on utilise la quantité originale enregistrée dans les mouvements
            $lostQty = $this->getOriginalQuantity($batch->getId());
            $loss = $lostQty * $unitPrice;
            $totalLoss += $loss;

            $reportLines[] = [
                'batch' => $batch,
                'unit_price' => $unitPrice,
                'lost_quantity' => $lostQty,
                'loss' => $loss,
            ];
        }

        require __DIR__ . '/../../templates/report_losses.php';
    }

    private function getOriginalQuantity(int $batchId): int
    {
        $db = Database::getConnection();
        $stmt = $db->prepare(
            "SELECT COALESCE(SUM(quantity), 0) AS total 
             FROM stock_movements 
             WHERE batch_id = :batch_id AND type = 'IN'"
        );
        $stmt->execute(['batch_id' => $batchId]);
        $row = $stmt->fetch();
        return (int) $row['total'];
    }

    private function requireRole(array $roles): void
    {
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
}
