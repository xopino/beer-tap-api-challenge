<?php

namespace App\DataFixtures;

use App\Dispenser\Domain\Entity\Dispenser;
use App\Dispenser\Domain\Entity\DispenserSpendingLine;
use App\Tests\Unit\Dispenser\Domain\Entity\DispenserMother;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ApplicationDataFixtures extends Fixture
{
    public const DISPENSER_ID_OPENED = '9e622f3f-172a-474f-a0ec-37ec710433a9';
    public const DISPENSER_ID_CLOSED = 'f4a3f0dd-8865-478f-b28d-fd7318ead5c1';

    public function load(ObjectManager $manager): void
    {
        $dispenserOpened = DispenserMother::opened(self::DISPENSER_ID_OPENED);
        $dispenserOpened->pullDomainEvents();
        $manager->getRepository(Dispenser::class)->save($dispenserOpened);

        $dispenserClosed = DispenserMother::closed(self::DISPENSER_ID_CLOSED);
        $dispenserClosed->pullDomainEvents();
        $manager->getRepository(Dispenser::class)->save($dispenserClosed);

        $dispenserSpendingLine = DispenserSpendingLine::create(
            $dispenserOpened->getId(),
            $dispenserOpened->getFlowVolume(),
            date('Y-m-d\TH:i:s\Z')
        );

        $manager->getRepository(DispenserSpendingLine::class)->save($dispenserSpendingLine);

        $manager->flush();
        $manager->clear();
    }
}
