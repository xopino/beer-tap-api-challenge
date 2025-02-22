<?php

namespace App\Tests\Unit\Dispenser\Domain\Entity;

use App\Dispenser\Domain\Entity\Dispenser;

class DispenserMother
{
    public static function random(): Dispenser
    {
        $dispenser = new Dispenser();
        $dispenser
            ->setFlowVolume(0.5);

        return $dispenser;
    }

    public static function opened(string $id): Dispenser
    {
        $dispenser = new Dispenser($id);
        $dispenser
            ->setFlowVolume(0.5)
            ->changeStatus(Dispenser::STATUS_OPEN, date('Y-m-d\TH:i:s\Z'));

        return $dispenser;
    }

    public static function closed(string $id): Dispenser
    {
        $dispenser = new Dispenser($id);
        $dispenser
            ->setFlowVolume(0.5);

        return $dispenser;
    }
}
