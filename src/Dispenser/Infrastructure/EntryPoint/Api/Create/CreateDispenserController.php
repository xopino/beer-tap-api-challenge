<?php

namespace App\Dispenser\Infrastructure\EntryPoint\Api\Create;

use App\Dispenser\Application\UseCase\Create\CreateDispenserUseCase;
use App\Dispenser\Application\UseCase\Create\CreateDispenserUseCaseRequest;
use App\Shared\Infrastructure\EntryPoint\Api\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateDispenserController extends BaseController
{
    public function __construct(
        public readonly CreateDispenserUseCase $useCase
    )
    {
    }

    public function __invoke(
        Request $request,
    ): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['flow_volume']) || !isset($data['price'])) {
                return $this->json(['error' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
            }

            $user = $this->getUser();
            if (!$user) {
                return $this->json(
                    ['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED
                );
            }

            $flowVolume = (int) $data['flow_volume'];
            $price      = (float) $data['price'];

            $request = new CreateDispenserUseCaseRequest(
                $flowVolume,
                $price,
                $user->getId()
            );

            $response = $this->useCase->execute($request);

            if (!$response->isValid()) {
                return $this->json(['error' => $response->message], Response::HTTP_INTERNAL_SERVER_ERROR);

            }

            return $this->json(
                  [
                      'message'      => $response->message,
                      'dispenser_id' => $response->dispenserId
                  ]
                , Response::HTTP_CREATED
            );
        } catch (\Throwable $throwable) {

            //TODO: Use Domain Exceptions
            return $this->json(['error' => $throwable->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
