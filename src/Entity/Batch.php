<?php

require_once __DIR__ . '/../Enum/BatchStatus.php';

class Batch
{
    private ?int $id;
    private int $medicationId;
    private string $batchNumber;
    private string $expiryDate;
    private int $quantity;
    private BatchStatus $status;
    private ?string $receivedAt;

    // Champ supplémentaire pour affichage (nom du médicament)
    private ?string $medicationName;

    public function __construct(
        ?int $id = null,
        int $medicationId = 0,
        string $batchNumber = '',
        string $expiryDate = '',
        int $quantity = 0,
        BatchStatus $status = BatchStatus::AVAILABLE,
        ?string $receivedAt = null,
        ?string $medicationName = null
    ) {
        $this->id = $id;
        $this->medicationId = $medicationId;
        $this->batchNumber = $batchNumber;
        $this->expiryDate = $expiryDate;
        $this->quantity = $quantity;
        $this->status = $status;
        $this->receivedAt = $receivedAt;
        $this->medicationName = $medicationName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getMedicationId(): int
    {
        return $this->medicationId;
    }

    public function setMedicationId(int $medicationId): void
    {
        $this->medicationId = $medicationId;
    }

    public function getBatchNumber(): string
    {
        return $this->batchNumber;
    }

    public function setBatchNumber(string $batchNumber): void
    {
        $this->batchNumber = $batchNumber;
    }

    public function getExpiryDate(): string
    {
        return $this->expiryDate;
    }

    public function setExpiryDate(string $expiryDate): void
    {
        $this->expiryDate = $expiryDate;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getStatus(): BatchStatus
    {
        return $this->status;
    }

    public function setStatus(BatchStatus $status): void
    {
        $this->status = $status;
    }

    public function getReceivedAt(): ?string
    {
        return $this->receivedAt;
    }

    public function setReceivedAt(?string $receivedAt): void
    {
        $this->receivedAt = $receivedAt;
    }

    public function getMedicationName(): ?string
    {
        return $this->medicationName;
    }

    public function setMedicationName(?string $medicationName): void
    {
        $this->medicationName = $medicationName;
    }
}
