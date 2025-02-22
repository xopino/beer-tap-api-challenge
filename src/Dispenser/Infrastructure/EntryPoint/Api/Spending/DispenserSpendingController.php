<?php

namespace App\Dispenser\Infrastructure\EntryPoint\Api\Spending;

use App\Dispenser\Application\Query\Spending\DispenserSpendingQuery;
use App\Dispenser\Domain\Exception\DispenserDoesNotExist;
use App\Shared\Infrastructure\EntryPoint\Api\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class DispenserSpendingController extends BaseController
{
    public function __invoke($id): JsonResponse
    {
        try {
            $query = new DispenserSpendingQuery($id);

            $response = $this->ask($query);

            return $this->json($response->result, Response::HTTP_OK);
        } catch (HandlerFailedException $exception) {
            foreach ($exception->getNestedExceptions() as $nestedException) {
                if ($nestedException instanceof DispenserDoesNotExist) {
                    return $this->json(null, Response::HTTP_NOT_FOUND);
                }
            }

            return $this->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $throwable) {
            return $this->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
