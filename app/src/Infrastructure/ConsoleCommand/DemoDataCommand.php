<?php

declare(strict_types=1);

namespace App\Infrastructure\ConsoleCommand;

use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Name;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'DemoData', description: 'just for demo')]
class DemoDataCommand extends Command
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->getByName("Mat");

        dd($users);


        return Command::SUCCESS;
    }


}
