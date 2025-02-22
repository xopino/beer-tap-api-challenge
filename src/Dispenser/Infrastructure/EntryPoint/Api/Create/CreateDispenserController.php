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
    public function __invoke(
        Request $request,
        CreateDispenserUseCase $useCase,
    ): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['flow_volume'])) {
                return $this->json(['error' => 'Missing flow_volume'], Response::HTTP_BAD_REQUEST);
            }

            $request = new CreateDispenserUseCaseRequest(
                (float) $data['flow_volume']
            );

            $response = $useCase->execute($request);

            if (!$response->isValid()) {
                return $this->json(['error' => $response->message], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->json(
                  [
                      'id' => $response->dispenserId,
                      'flow_volume' => $response->flowVolume
                  ]
                , Response::HTTP_OK
            );
        } catch (\Throwable $throwable) {

            //TODO: Use Domain Exceptions
            return $this->json(['error' => $throwable->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
