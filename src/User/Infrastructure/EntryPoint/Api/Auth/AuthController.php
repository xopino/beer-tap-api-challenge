<?php

namespace App\User\Infrastructure\EntryPoint\Api\Auth;

use App\User\Domain\Entity\User;
use App\User\Infrastructure\Persistence\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class AuthController extends AbstractController
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
