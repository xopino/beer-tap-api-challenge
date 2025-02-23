<?php

namespace App\Dispenser\Application\UseCase\Create;

use App\Dispenser\Domain\Entity\Dispenser;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;

class CreateDispenserUseCase
{
    public function __construct(
        public readonly DispenserRepositoryInterface $dispenserRepository
    )
    {
    }

    public function execute(CreateDispenserUseCaseRequest $request): CreateDispenserUseCaseResponse
    {
        try {
            $dispenser = new Dispenser();
            $dispenser
                ->setFlowVolume($request->flowVolume);

            $this->dispenserRepository->save($dispenser);

            return CreateDispenserUseCaseResponse::createValidResponse(
                $dispenser->getId(),
                $dispenser->getFlowVolume()
            );
        } catch (\Throwable $throwable) {
            //TODO: Use Domain Error Messages
            //TODO: Log
            return CreateDispenserUseCaseResponse::createInvalidResponse(
                $throwable->getMessage()
            );
        }
    }
}
