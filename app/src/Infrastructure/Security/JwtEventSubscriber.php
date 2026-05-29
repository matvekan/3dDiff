<?php

namespace App\Infrastructure\Security;

use App\Domain\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class JwtEventSubscriber
{
    #[AsEventListener(event: Events::JWT_CREATED)]
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();
        if (!$user instanceof User) {
            return;
        }

        $payload = $event->getData();
        $payload['uid'] = (string) $user->getId();
        $event->setData($payload);
    }

    #[AsEventListener(event: Events::AUTHENTICATION_SUCCESS)]
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();
        if (!$user instanceof User) {
            return;
        }

        $data = $event->getData();
        $data['userId'] = (string) $user->getId();
        $data['role'] = $user->getRole();
        $event->setData($data);
    }
}
