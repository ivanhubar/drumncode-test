<?php

namespace App\Command;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:user:get-auth-token', description: 'Get auth token for users')]
class GetAuthTokenForUserCommand extends Command
{
    public function __construct(
        private readonly JWTTokenManagerInterface $jwtTokenManager,
        private readonly UserRepository $userRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findAll();

        foreach ($users as $user) {
            $output->writeln("\n\n".$user->getUsername().': '.$this->jwtTokenManager->create($user)."\n\n");
        }

        return self::SUCCESS;
    }
}
