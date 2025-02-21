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
}
