<?php

namespace App\Tests\Unit\User;

use App\User\Domain\Entity\User;

class UserMother
{
    public static function create(string $email, array $roles): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword(
            'randomPassword'
        );

        $apiToken = bin2hex(random_bytes(32));
        $user->setApiToken($apiToken);

        return $user;
    }
}
