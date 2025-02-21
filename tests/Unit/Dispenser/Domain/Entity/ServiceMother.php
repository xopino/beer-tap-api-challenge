<?php

namespace App\Tests\Unit\Dispenser\Domain\Entity;

use App\Dispenser\Domain\Entity\Service;

class ServiceMother
{
    public static function random(): Service
    {
        return Service::create(
            1,
            'aDispenserId'
        );
    }
}
