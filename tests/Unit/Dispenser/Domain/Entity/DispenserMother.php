<?php

namespace App\Tests\Unit\Dispenser\Domain\Entity;

use App\Dispenser\Domain\Entity\Dispenser;

class DispenserMother
{
    public static function random(): Dispenser
    {
        $dispenser = new Dispenser();
        $dispenser
            ->setFlowVolume(0.5)
            ->setPrice(0.5)
            ->setPromoterId(1);

        return $dispenser;
    }

    public static function opened(string $id, int $attendeeId): Dispenser
    {
        $dispenser = new Dispenser($id);
        $dispenser
            ->setFlowVolume(0.5)
            ->setPrice(0.5)
            ->setPromoterId(1)
            ->open($attendeeId);

        return $dispenser;
    }

    public static function closed(string $id): Dispenser
    {
        $dispenser = new Dispenser($id);
        $dispenser
            ->setFlowVolume(0.5)
            ->setPrice(0.5)
            ->setPromoterId(1)
            ->close();

        return $dispenser;
    }
}
