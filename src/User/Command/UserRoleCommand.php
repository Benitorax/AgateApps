<?php

declare(strict_types=1);

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace User\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use User\Repository\UserRepository;

class UserRoleCommand extends Command
{
    protected static $defaultName = 'user:role';

    private $userRepository;
    private $em;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        parent::__construct(static::$defaultName);
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Allow adding or removing roles for a user')
            ->addArgument('username-or-email', InputArgument::REQUIRED)
            ->addArgument('roles', InputArgument::REQUIRED | InputArgument::IS_ARRAY)
            ->addOption('promote', null, InputOption::VALUE_NONE)
            ->addOption('demote', null, InputOption::VALUE_NONE)
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        if (
            (!$input->hasOption('promote') && !$input->hasOption('demote'))
            ||
            (!$input->getOption('promote') && !$input->getOption('demote'))
            ||
            ($input->getOption('promote') && $input->getOption('demote'))
        ) {
            throw new \RuntimeException('You must at least specify the --promote or --demote option.');
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $usernameOrEmail = $input->getArgument('username-or-email');

        $user = $this->userRepository->findByUsernameOrEmail($usernameOrEmail);

        if (!$user) {
            throw new \InvalidArgumentException(\sprintf('User with username or email %s does not exist.', $usernameOrEmail));
        }

        $roles = $input->getArgument('roles');

        foreach ($roles as &$role) {
            $role = \mb_strtoupper(\trim($role));
            if (0 !== \mb_strpos($role, 'ROLE_')) {
                throw new \InvalidArgumentException('Only attributes starting with "ROLE_" are valid roles.');
            }
        }

        if ($input->getOption('promote')) {
            $io->block(\sprintf('Adding roles to %s:', $user->getUsername()));
            $io->listing($roles);
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    $io->warning('User already has role "'.$role.'".');
                    continue;
                }
                $user->addRole($role);
            }
        }

        if ($input->getOption('demote')) {
            $io->block(\sprintf('Removing roles to %s:', $user->getUsername()));
            $io->listing($roles);
            foreach ($roles as $role) {
                if (!$user->hasRole($role)) {
                    $io->warning('User does not have role "'.$role.'".');
                    continue;
                }
                $user->removeRole($role);
            }
        }

        $io->block('Final roles:');

        $io->table([], \array_map(function ($item) { return [$item]; }, $user->getRoles()));

        if ($io->confirm('Save these roles for this user?', true)) {
            $this->em->flush();
        }

        $io->success('Done!');
    }
}
