<?php

namespace App\User\Infrastructure\Security;

use App\User\Domain\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $authHeader = $request->headers->get('Authorization');
        if (null === $authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new AuthenticationException('No API token provided');
        }

        $apiToken = substr($authHeader, 7);

        return new SelfValidatingPassport(new UserBadge($apiToken, function ($token) {
            return $this->em->getRepository(User::class)->findOneBy(['apiToken' => $token]);
        }));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?JsonResponse
    {
        return new JsonResponse(['error' => 'Authentication failed'], 401);
    }

    public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?JsonResponse
    {
        // On success, continue to the controller.
        return null;
    }
}
