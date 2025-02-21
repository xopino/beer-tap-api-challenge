<?php

namespace App\Dispenser\Infrastructure\EntryPoint\Api\Open;

use App\Dispenser\Domain\Bus\Command\OpenDispenser\OpenDispenserCommand;
use App\Shared\Infrastructure\EntryPoint\Api\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OpenDispenserController extends BaseController
{
    public function __invoke(
        Request $request
    ): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['dispenser_id'])) {
                throw new \Exception('Dispenser id is required');
            }

            $user = $this->getUser();

            $command = new OpenDispenserCommand(
                $data['dispenser_id'],
                $user->getId()
            );

            $this->dispatch($command);

            return $this->json(['message' => 'Dispenser open, enjoy!'], Response::HTTP_OK);
        } catch (\Throwable $throwable) {

            //TODO: Use Domain Errors
            return $this->json(['error' => $throwable->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
