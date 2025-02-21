<?php

namespace App\Tests\Unit\Dispenser\Domain\Entity;

use App\Dispenser\Domain\Entity\Dispenser;

class DispenserMother
{
    public static function random(): Dispenser
    {
        return new Dispenser();
    }
}
