<?php

namespace App\Dispenser\Domain\Entity;

use App\Dispenser\Infrastructure\Persistence\Doctrine\Repository\DispenserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DispenserRepository::class)]
class Dispenser
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private string $id;

    #[ORM\Column]
    private int $flowVolume;

    #[ORM\Column(type: Types::FLOAT)]
    private float $price;

    #[ORM\Column]
    private int $promoterId;

    public function __construct(
        ?string $anUuid = null
    )
    {
        $this->id = $anUuid ?? Uuid::v4()->jsonSerialize();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFlowVolume(): int
    {
        return $this->flowVolume;
    }

    public function setFlowVolume(int $flowVolume): static
    {
        $this->flowVolume = $flowVolume;

        return $this;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPromoterId(): string
    {
        return $this->promoterId;
    }

    public function setPromoterId(int $promoterId): static
    {
        $this->promoterId = $promoterId;

        return $this;
    }
}
