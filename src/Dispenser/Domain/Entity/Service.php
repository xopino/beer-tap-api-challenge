<?php

namespace App\Dispenser\Domain\Entity;

use App\Dispenser\Domain\Service\MoneySpentCalculator;
use App\Dispenser\Infrastructure\Persistence\Doctrine\Repository\ServiceDoctrineRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ServiceDoctrineRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private string $id;

    #[ORM\Column]
    private int $attendeeId;

    #[ORM\Column(length: 255)]
    private string $dispenserId;

    #[ORM\Column(length: 255)]
    private string $startDate;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $endDate = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $moneySpent = null;

    private function __construct(
        ?string $anUuid = null
    )
    {
        $this->id = $anUuid ?? Uuid::v4()->jsonSerialize();
    }

    public static function create(
        int $attendeeId,
        string $dispenserId,
    ): static
    {
        $service = new self();

        return $service
            ->setAttendeeId($attendeeId)
            ->setDispenserId($dispenserId)
            ->setStartDate(date('Y-m-d H:i:s'));
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAttendeeId(): int
    {
        return $this->attendeeId;
    }

    public function setAttendeeId(int $attendeeId): static
    {
        $this->attendeeId = $attendeeId;

        return $this;
    }

    public function getDispenserId(): string
    {
        return $this->dispenserId;
    }

    public function setDispenserId(string $dispenserId): static
    {
        $this->dispenserId = $dispenserId;

        return $this;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function setStartDate(string $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    public function setEndDate(?string $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getMoneySpent(): ?float
    {
        return $this->moneySpent;
    }

    public function finish(Dispenser $dispenser, MoneySpentCalculator $calculator): static
    {
        $this->setEndDate(date('Y-m-d H:i:s'));

        $this->moneySpent = $calculator->calculate(
            $this->startDate,
            $this->endDate,
            $dispenser->getFlowVolume(),
            $dispenser->getPrice()
        );

        return $this;
    }
}
