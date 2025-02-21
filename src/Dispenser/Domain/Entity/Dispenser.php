<?php

namespace App\Dispenser\Domain\Entity;

use App\Dispenser\Domain\Bus\Event\DispenserOpenedDomainEvent;
use App\Dispenser\Infrastructure\Persistence\Doctrine\Repository\DispenserDoctrineRepository;
use App\Shared\Domain\Entity\AggregateRoot;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DispenserDoctrineRepository::class)]
class Dispenser extends AggregateRoot
{
    const STATUS_OPEN   = 'OPEN';
    const STATUS_CLOSED = 'CLOSED';

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private string $id;

    #[ORM\Column]
    private int $flowVolume;

    #[ORM\Column(type: Types::FLOAT)]
    private float $price;

    #[ORM\Column]
    private int $promoterId;

    #[ORM\Column]
    private string $status = self::STATUS_CLOSED;

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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function open(int $attendeeId): static
    {
        if ($this->isOpen()) {
            throw new \Exception('Dispenser already open');
        }


        $this->status = self::STATUS_OPEN;

        $this->record(new DispenserOpenedDomainEvent($this->id, $attendeeId));

        return $this;
    }

    public function close(): static
    {
        $this->status = self::STATUS_CLOSED;

        return $this;
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }
}
