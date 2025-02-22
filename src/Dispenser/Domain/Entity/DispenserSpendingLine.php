<?php

namespace App\Dispenser\Domain\Entity;

use App\Dispenser\Domain\Service\MoneySpentCalculator;
use App\Dispenser\Infrastructure\Persistence\Doctrine\Repository\DispenserSpendingLineDoctrineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DispenserSpendingLineDoctrineRepository::class)]
class DispenserSpendingLine
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private string $id;

    #[ORM\Column(length: 255)]
    private string $dispenserId;

    #[ORM\Column(length: 255)]
    private string $openedAt;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $closedAt = null;

    #[ORM\Column(type: Types::FLOAT)]
    private float $flowVolume;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $totalSpent = null;

    private function __construct(
        ?string $anUuid = null
    )
    {
        $this->id = $anUuid ?? Uuid::v4()->jsonSerialize();
    }

    public static function create(
        string $dispenserId,
        float  $flowVolume,
        string $openedAt
    ): static
    {
        $dispenserSpendingLine = new self();

        return $dispenserSpendingLine
            ->setDispenserId($dispenserId)
            ->setFlowVolume($flowVolume)
            ->setOpenedAt($openedAt);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDispenserId(): string
    {
        return $this->dispenserId;
    }

    public function setDispenserId(string $dispenserId): self
    {
        $this->dispenserId = $dispenserId;

        return $this;
    }


    public function getOpenedAt(): string
    {
        return $this->openedAt;
    }

    public function setOpenedAt(string $openedAt): static
    {
        $this->openedAt = $openedAt;

        return $this;
    }

    public function getClosedAt(): ?string
    {
        return $this->closedAt;
    }

    public function setClosedAt(?string $closedAt): static
    {
        $this->closedAt = $closedAt;

        return $this;
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

    public function getTotalSpent(): ?float
    {
        return $this->totalSpent;
    }

    public function finish(string $closedAt, MoneySpentCalculator $calculator): static
    {
        $this->setClosedAt($closedAt);

        $this->totalSpent = $calculator->calculate(
            $this->openedAt,
            $this->closedAt,
            $this->flowVolume,
        );

        return $this;
    }
}
