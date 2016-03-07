<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\UserBundle\Command;

use Sylius\Component\Core\Model\UserInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class DemoteUserCommand extends AbstractRoleCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sylius:user:demote')
            ->setDescription('Promotes a user by adding a role.')
            ->setDefinition(array(
                new InputArgument('email', InputArgument::REQUIRED, 'Email'),
                new InputArgument('roles', InputArgument::IS_ARRAY, 'RBAC roles'),
                new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Unset the user as super admin'),
            ))
            ->setHelp(<<<EOT
The <info>sylius:user:demote</info> command demotes a user by removing roles

  <info>php app/console fos:user:demote matthieu@email.com</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    public function executeRoleCommand(OutputInterface $output, UserInterface $user, array $roles, array $securityRoles)
    {
        $error = false;

        foreach ($roles as $code) {
            $role = $this->findAuthorizationRole($code);

            if (!$user->hasAuthorizationRole($role)) {
                $output->writeln(sprintf('<error>User "%s" didn\'t have "%s" RBAC role.</error>', (string)$user, $role));
                $error = true;
                continue;
            }

            $user->removeAuthorizationRole($role);
            $output->writeln(sprintf('RBAC role <comment>%s</comment> has been removed from user <comment>%s</comment>', $role, (string)$user));
        }

        foreach ($securityRoles as $securityRole) {
            if (!$user->hasRole($securityRole)) {
                $output->writeln(sprintf('<error>User "%s" didn\'t have "%s" Security role.</error>', (string)$user, $securityRole));
                $error = true;
                continue;
            }

            $user->removeRole($securityRole);
            $output->writeln(sprintf('Security role <comment>%s</comment> has been removed from user <comment>%s</comment>', $securityRole, (string)$user));
        }

        if (!$error) {
            $this->getEntityManager()->flush();
        }
    }
}
