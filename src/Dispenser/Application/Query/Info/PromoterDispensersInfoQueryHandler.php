<?php

namespace App\Dispenser\Application\Query\Info;

use App\Dispenser\Domain\Bus\Query\Info\PromoterDispensersInfoQuery;
use App\Dispenser\Domain\Entity\Service;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;
use App\Dispenser\Domain\Persistence\Repository\ServiceRepositoryInterface;
use App\Dispenser\Domain\Service\MoneySpentCalculator;

class PromoterDispensersInfoQueryHandler
{
    public function __construct(
        private readonly DispenserRepositoryInterface $dispenserRepository,
        private readonly ServiceRepositoryInterface   $serviceRepository,
        private readonly MoneySpentCalculator         $calculator
    )
    {
    }

    public function __invoke(PromoterDispensersInfoQuery $query): PromoterDispensersInfoQueryResponse
    {
        $dispensers          = $this->dispenserRepository->searchByPromoterId($query->promoterId);
        $totalOfServices     = 0;
        $totalSecondsElapsed = 0;
        $totalMoneySpent     = 0.0;
        $dispenserInfoList   = [];

        foreach ($dispensers as $dispenser) {
            $services                 = $this->serviceRepository->searchByDispenserId($dispenser->getId());
            $dispenserTotalMoneySpent = 0.0;
            $serviceInfoList          = [];

            /** @var Service $service */
            foreach ($services as $service) {
                $endDate        = $service->getEndDate() ?? date('Y-m-d H:i:s');
                $secondsElapsed = strtotime($endDate) - strtotime($service->getStartDate());
                $moneySpent     = $service->getMoneySpent() ?? $this->calculator->calculate(
                    $service->getStartDate(),
                    $endDate,
                    $dispenser->getFlowVolume(),
                    $dispenser->getPrice()
                );

                $serviceInfoList[] = new ServiceInfoDTO(
                    (string) $secondsElapsed,
                    $moneySpent
                );

                $dispenserTotalMoneySpent += $moneySpent;
                $totalSecondsElapsed      += $secondsElapsed;
            }

            $totalOfServices += count($services);
            $totalMoneySpent += $dispenserTotalMoneySpent;

            $dispenserInfoList[] = new DispenserInfoDTO(
                count($services),
                $dispenserTotalMoneySpent,
                $serviceInfoList
            );
        }

        return new PromoterDispensersInfoQueryResponse(
            $dispenserInfoList,
            $totalOfServices,
            $totalSecondsElapsed,
            $totalMoneySpent
        );
    }
}
