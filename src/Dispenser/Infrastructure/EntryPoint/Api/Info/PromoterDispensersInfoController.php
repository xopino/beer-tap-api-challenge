<?php

namespace App\Dispenser\Infrastructure\EntryPoint\Api\Info;

use App\Dispenser\Domain\Bus\Query\Info\PromoterDispensersInfoQuery;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Bus\Query\QueryBusInterface;
use App\Shared\Infrastructure\EntryPoint\Api\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PromoterDispensersInfoController extends BaseController
{
    public function __invoke(): JsonResponse
    {
        try {
            $user = $this->getUser();

            $query = new PromoterDispensersInfoQuery($user->getId());

            $response = $this->ask($query);

            return $this->json($response->result, Response::HTTP_OK);
        } catch (\Throwable $throwable) {
            return $this->json(['error' => $throwable->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
