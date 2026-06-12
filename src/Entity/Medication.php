<?php

class Medication
{
    private ?int $id;
    private string $name;
    private ?string $description;
    private float $unitPrice;
    private ?string $createdAt;

    public function __construct(
        ?int $id = null,
        string $name = '',
        ?string $description = null,
        float $unitPrice = 0.00,
        ?string $createdAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->unitPrice = $unitPrice;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): void
    {
        $this->unitPrice = $unitPrice;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
