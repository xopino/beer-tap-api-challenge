<?php

namespace App\Dispenser\Infrastructure\EntryPoint\Api\Status;

use App\Dispenser\Application\Command\ChangeStatus\ChangeStatusCommand;
use App\Dispenser\Domain\Exception\DispenserAlreadyClosedException;
use App\Dispenser\Domain\Exception\DispenserAlreadyOpenException;
use App\Shared\Infrastructure\EntryPoint\Api\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class ChangeDispenserStatusController extends BaseController
{
    public function __invoke(
        Request $request,
        string $id
    ): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['status']) || !isset($data['updated_at'])) {
                return $this->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $command = new ChangeStatusCommand(
                $id,
                $data['status'],
                $data['updated_at']
            );

            $this->dispatch($command);

            return $this->json(null, Response::HTTP_ACCEPTED);
        } catch (HandlerFailedException $exception) {
            foreach ($exception->getNestedExceptions() as $nestedException) {
                if ($nestedException instanceof DispenserAlreadyOpenException ||
                    $nestedException instanceof DispenserAlreadyClosedException) {
                    return $this->json(null, Response::HTTP_CONFLICT);
                }
            }
            return $this->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $throwable) {
            return $this->json(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
