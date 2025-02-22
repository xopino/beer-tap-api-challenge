<?php

namespace App\Dispenser\Application\Query\Spending;

use App\Dispenser\Domain\Entity\DispenserSpendingLine;
use App\Dispenser\Domain\Exception\DispenserDoesNotExist;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;
use App\Dispenser\Domain\Persistence\Repository\DispenserSpendingLineRepositoryInterface;
use App\Dispenser\Domain\Service\MoneySpentCalculator;

class DispenserSpendingQueryHandler
{
    public function __construct(
        private readonly DispenserRepositoryInterface $dispenserRepository,
        private readonly DispenserSpendingLineRepositoryInterface $spendingLineRepository,
        private readonly MoneySpentCalculator $calculator
    ) {
    }

    public function __invoke(DispenserSpendingQuery $query): DispenserSpendingQueryResponse
    {
        $dispenser = $this->dispenserRepository->findById($query->dispenserId);
        if (!$dispenser) {
            throw new DispenserDoesNotExist();
        }

        $spendingLines = $this->spendingLineRepository->searchByDispenserId($dispenser->getId());

        $totalAmount = 0.0;
        $usages = [];

        /** @var DispenserSpendingLine $spendingLine */
        foreach ($spendingLines as $spendingLine) {
            $endDate = $spendingLine->getClosedAt() ?? date('Y-m-d\TH:i:s\Z');

            $moneySpent = $spendingLine->getTotalSpent() ?? $this->calculator->calculate(
                $spendingLine->getOpenedAt(),
                $endDate,
                $dispenser->getFlowVolume()
            );

            $totalAmount += $moneySpent;

            $usages[] = [
                'opened_at'  => $spendingLine->getOpenedAt(),
                'closed_at'  => $spendingLine->getClosedAt(),
                'flow_volume'=> $spendingLine->getFlowVolume(),
                'total_spent'=> $moneySpent,
            ];
        }

        return new DispenserSpendingQueryResponse($totalAmount, $usages);
    }
}
