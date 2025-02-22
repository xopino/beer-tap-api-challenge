<?php

namespace App\Dispenser\Domain\Entity;

use App\Dispenser\Domain\Event\DispenserClosedDomainEvent;
use App\Dispenser\Domain\Event\DispenserOpenedDomainEvent;
use App\Dispenser\Domain\Exception\DispenserAlreadyClosedException;
use App\Dispenser\Domain\Exception\DispenserAlreadyOpenException;
use App\Dispenser\Infrastructure\Persistence\Doctrine\Repository\DispenserDoctrineRepository;
use App\Shared\Domain\Entity\AggregateRoot;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DispenserDoctrineRepository::class)]
class Dispenser extends AggregateRoot
{
    const STATUS_OPEN   = 'open';
    const STATUS_CLOSED = 'close';

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private string $id;

    #[ORM\Column(type: Types::FLOAT)]
    private float $flowVolume;

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

    public function getFlowVolume(): float
    {
        return $this->flowVolume;
    }

    public function setFlowVolume(float $flowVolume): static
    {
        $this->flowVolume = $flowVolume;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @throws DispenserAlreadyClosedException
     * @throws DispenserAlreadyOpenException
     */
    public function changeStatus(string $status, string $updatedAt): static
    {
        if ($status === Dispenser::STATUS_OPEN) {
            $this->open($updatedAt);
        } elseif ($status === Dispenser::STATUS_CLOSED) {
            $this->close($updatedAt);
        }

        return $this;
    }

    private function open(string $updatedAt): static
    {
        if ($this->isOpen()) {
            throw new DispenserAlreadyOpenException();
        }

        $this->status = self::STATUS_OPEN;

        $this->record(
            new DispenserOpenedDomainEvent(
                $this->id,
                $this->flowVolume,
                $updatedAt
            )
        );

        return $this;
    }

    private function close(string $updatedAt): static
    {
        if (!$this->isOpen()) {
            throw new DispenserAlreadyClosedException();
        }

        $this->status = self::STATUS_CLOSED;

        $this->record(
            new DispenserClosedDomainEvent(
                $this->id,
                $this->flowVolume,
                $updatedAt
            )
        );

        return $this;
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }
}
