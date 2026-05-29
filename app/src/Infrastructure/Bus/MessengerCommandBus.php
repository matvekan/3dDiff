<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class MessengerCommandBus implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function execute(CommandInterface $command): mixed
    {
        $envelope = $this->messageBus->dispatch($command);

        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp?->getResult();
    }
}
