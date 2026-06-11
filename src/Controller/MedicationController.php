<?php

require_once __DIR__ . '/../Repository/MedicationRepository.php';

class MedicationController
{
    private MedicationRepository $medicationRepository;

    public function __construct()
    {
        $this->medicationRepository = new MedicationRepository();
    }

    public function list(): void
    {
        $medications = $this->medicationRepository->findAll();
        require __DIR__ . '/../../templates/medications.php';
    }

    public function create(): void
    {
        $this->requireRole(['ADMIN', 'PHARMACIEN']);
        $error = null;
        $success = null;
        $medication = new Medication();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $unitPrice = (float) ($_POST['unit_price'] ?? 0);

            if ($name === '') {
                $error = 'Le nom du médicament est obligatoire.';
            } elseif ($unitPrice < 0) {
                $error = 'Le prix unitaire ne peut pas être négatif.';
            } else {
                $medication->setName($name);
                $medication->setDescription($description ?: null);
                $medication->setUnitPrice($unitPrice);
                $this->medicationRepository->create($medication);
                $success = 'Médicament ajouté avec succès.';
                $medication = new Medication();
            }
        }

        require __DIR__ . '/../../templates/medication_form.php';
    }

    public function edit(): void
    {
        $this->requireRole(['ADMIN', 'PHARMACIEN']);
        $id = (int) ($_GET['id'] ?? 0);
        $medication = $this->medicationRepository->findById($id);

        if (!$medication) {
            header('Location: index.php?page=medications');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $unitPrice = (float) ($_POST['unit_price'] ?? 0);

            if ($name === '') {
                $error = 'Le nom du médicament est obligatoire.';
            } elseif ($unitPrice < 0) {
                $error = 'Le prix unitaire ne peut pas être négatif.';
            } else {
                $medication->setName($name);
                $medication->setDescription($description ?: null);
                $medication->setUnitPrice($unitPrice);
                $this->medicationRepository->update($medication);
                $success = 'Médicament modifié avec succès.';
            }
        }

        require __DIR__ . '/../../templates/medication_form.php';
    }

    public function delete(): void
    {
        $this->requireRole(['ADMIN', 'PHARMACIEN']);
        $id = (int) ($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->medicationRepository->delete($id);
        }

        header('Location: index.php?page=medications');
        exit;
    }

    private function requireRole(array $roles): void
    {
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
}
