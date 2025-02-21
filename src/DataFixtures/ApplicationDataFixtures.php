<?php

namespace App\DataFixtures;

use App\Dispenser\Domain\Entity\Dispenser;
use App\Dispenser\Domain\Entity\Service;
use App\Tests\Unit\Dispenser\Domain\Entity\DispenserMother;
use App\Tests\Unit\User\UserMother;
use App\User\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ApplicationDataFixtures extends Fixture
{
    public const DISPENSER_ID_OPENED = '9e622f3f-172a-474f-a0ec-37ec710433a9';
    public const DISPENSER_ID_CLOSED = 'f4a3f0dd-8865-478f-b28d-fd7318ead5c1';
    public const USER_EMAIL_OPENED = 'closeDispenser@user-test.com';
    public function load(ObjectManager $manager): void
    {
        $user = UserMother::create(self::USER_EMAIL_OPENED, ['ROLE_ATTENDEE', 'ROLE_PROMOTER']);
        $manager->getRepository(User::class)->save($user);

        $dispenserOpened = DispenserMother::opened(self::DISPENSER_ID_OPENED, $user->getId());
        $dispenserOpened->pullDomainEvents();
        $manager->getRepository(Dispenser::class)->save($dispenserOpened);

        $dispenserClosed = DispenserMother::closed(self::DISPENSER_ID_CLOSED);
        $dispenserClosed->pullDomainEvents();
        $manager->getRepository(Dispenser::class)->save($dispenserClosed);

        $service = Service::create(
            $user->getId(),
            $dispenserOpened->getId()
        );
        $manager->getRepository(Service::class)->save($service);

        $manager->flush();
        $manager->clear();
    }
}
