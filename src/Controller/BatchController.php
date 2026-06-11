<?php

require_once __DIR__ . '/../Repository/BatchRepository.php';
require_once __DIR__ . '/../Repository/MedicationRepository.php';
require_once __DIR__ . '/../Enum/BatchStatus.php';

class BatchController
{
    private BatchRepository $batchRepository;
    private MedicationRepository $medicationRepository;

    public function __construct()
    {
        $this->batchRepository = new BatchRepository();
        $this->medicationRepository = new MedicationRepository();
    }

    public function list(): void
    {
        $batches = $this->batchRepository->findAll();
        require __DIR__ . '/../../templates/batches.php';
    }

    public function alerts(): void
    {
        $batches = $this->batchRepository->findAll();
        $filterCritical = isset($_GET['filter']) && $_GET['filter'] === 'critical';
        require __DIR__ . '/../../templates/alerts.php';
    }

    public function receive(): void
    {
        $error = null;
        $success = null;
        $medications = $this->medicationRepository->findAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $medicationId = (int) ($_POST['medication_id'] ?? 0);
            $batchNumber = trim($_POST['batch_number'] ?? '');
            $expiryDate = $_POST['expiry_date'] ?? '';
            $quantity = (int) ($_POST['quantity'] ?? 0);

            if ($medicationId <= 0 || $batchNumber === '' || $quantity <= 0) {
                $error = 'Veuillez remplir tous les champs correctement.';
            } elseif ($expiryDate === '') {
                $error = 'La date de péremption est obligatoire.';
            } elseif ($expiryDate <= date('Y-m-d')) {
                $error = 'La date de péremption doit être postérieure à aujourd\'hui.';
            } else {
                $batch = new Batch();
                $batch->setMedicationId($medicationId);
                $batch->setBatchNumber($batchNumber);
                $batch->setExpiryDate($expiryDate);
                $batch->setQuantity($quantity);
                $batch->setStatus(BatchStatus::AVAILABLE);
                $this->batchRepository->create($batch);

                $this->batchRepository->recordMovement(
                    $this->getLastInsertId(),
                    'IN',
                    $quantity,
                    (int) $_SESSION['user_id']
                );

                $success = 'Lot reçu avec succès.';
            }
        }

        require __DIR__ . '/../../templates/batch_receive.php';
    }

    public function expire(): void
    {
        $this->requireRole(['ADMIN', 'PHARMACIEN']);
        $id = (int) ($_GET['id'] ?? 0);
        $batch = $this->batchRepository->findById($id);

        if ($batch && $batch->getStatus() !== BatchStatus::EXPIRED) {
            $batch->setStatus(BatchStatus::EXPIRED);
            $batch->setQuantity(0);
            $this->batchRepository->update($batch);
        }

        header('Location: index.php?page=batches');
        exit;
    }

    private function getLastInsertId(): int
    {
        return (int) Database::getConnection()->lastInsertId();
    }

    private function requireRole(array $roles): void
    {
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
}
