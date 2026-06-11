<?php

require_once __DIR__ . '/../Repository/BatchRepository.php';
require_once __DIR__ . '/../Repository/MedicationRepository.php';
require_once __DIR__ . '/../Enum/BatchStatus.php';

class StockController
{
    private BatchRepository $batchRepository;
    private MedicationRepository $medicationRepository;

    public function __construct()
    {
        $this->batchRepository = new BatchRepository();
        $this->medicationRepository = new MedicationRepository();
    }

    public function out(): void
    {
        $error = null;
        $success = null;
        $medications = $this->medicationRepository->findAll();
        $selectedBatch = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $medicationId = (int) ($_POST['medication_id'] ?? 0);
            $quantity = (int) ($_POST['quantity'] ?? 0);

            if ($medicationId <= 0 || $quantity <= 0) {
                $error = 'Veuillez remplir tous les champs correctement.';
            } else {
                // Règle FEFO : sélectionner le lot avec la date de péremption la plus proche
                $availableBatches = $this->batchRepository->findAvailableByMedication($medicationId);

                if (empty($availableBatches)) {
                    $error = 'Aucun lot disponible pour ce médicament.';
                } else {
                    $remainingQty = $quantity;

                    foreach ($availableBatches as $batch) {
                        if ($remainingQty <= 0) {
                            break;
                        }

                        $batchQty = $batch->getQuantity();
                        $toDeduct = min($batchQty, $remainingQty);

                        $batch->setQuantity($batchQty - $toDeduct);
                        if ($batch->getQuantity() === 0) {
                            $batch->setStatus(BatchStatus::LOW);
                        }
                        $this->batchRepository->update($batch);

                        $this->batchRepository->recordMovement(
                            $batch->getId(),
                            'OUT',
                            $toDeduct,
                            (int) $_SESSION['user_id']
                        );

                        $remainingQty -= $toDeduct;
                        $selectedBatch = $batch;
                    }

                    if ($remainingQty > 0) {
                        $error = 'Stock insuffisant. ' . ($quantity - $remainingQty) . ' unité(s) déduites.';
                    } else {
                        $success = 'Sortie de stock effectuée avec succès (règle FEFO appliquée).';
                    }
                }
            }
        }

        require __DIR__ . '/../../templates/stock_out.php';
    }
}
