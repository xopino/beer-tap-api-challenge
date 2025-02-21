<?php

namespace App\User\Infrastructure\EntryPoint\Api\Auth;

use App\Shared\Infrastructure\EntryPoint\Api\BaseController;
use App\User\Domain\Entity\User;
use App\User\Infrastructure\Persistence\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends BaseController
{
    public function __invoke(
        Request                     $request,
        UserRepository              $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data['email'] || !$data['password'] || !$data['roles']) {
            return $this->json(['error' => 'Email and password are required.'], 400);
        }

        //TODO: Use Role VO
        if (!is_array($data['roles']) || empty($data['roles']) || array_diff($data['roles'], ['ROLE_PROMOTER', 'ROLE_ATTENDEE'])) {
            return $this->json(['error' => 'Invalid roles'], 400);
        }


        //TODO: Create RegisterUserUseCase
        $existingUser = $userRepository->findOneBy(['email' => $data['email']]);

        if ($existingUser) {
            return $this->json(['error' => 'A user with this email already exists.'], 400);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setRoles($data['roles']);
        $user->setPassword(
            $passwordHasher->hashPassword($user, $data['password'])
        );

        $apiToken = bin2hex(random_bytes(32));
        $user->setApiToken($apiToken);

        $userRepository->save($user);

        return $this->json(['apiToken' => $apiToken]);
    }
}
