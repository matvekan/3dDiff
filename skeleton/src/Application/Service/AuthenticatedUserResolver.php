<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final readonly class AuthenticatedUserResolver
{
    public function __construct(
        private AuthTokenService $tokenService,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function fromRequest(Request $request): User
    {
        $header = (string) $request->headers->get('Authorization', '');
        if (!str_starts_with($header, 'Bearer ')) {
            throw new UnauthorizedHttpException('Bearer', 'Missing bearer token');
        }

        return $this->fromToken(trim(substr($header, 7)));
    }

    public function fromToken(string $token): User
    {
        $payload = $this->tokenService->parse($token);
        if (null === $payload) {
            throw new UnauthorizedHttpException('Bearer', 'Invalid token');
        }

        try {
            $user = $this->userRepository->getById((string) $payload['uid']);
        } catch (UserNotFoundException) {
            throw new UnauthorizedHttpException('Bearer', 'User not found');
        }

        return $user;
    }
}
