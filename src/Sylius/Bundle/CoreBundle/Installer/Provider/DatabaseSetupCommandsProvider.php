<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Installer\Provider;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Webmozart\Assert\Assert;

final class DatabaseSetupCommandsProvider implements DatabaseSetupCommandsProviderInterface
{
    public function __construct(private Registry $doctrineRegistry)
    {
    }

    /**
     * @return array<string, array>
     */
    public function getCommands(InputInterface $input, OutputInterface $output, QuestionHelper $questionHelper): array
    {
        $outputStyle = new SymfonyStyle($input, $output);

        if ($this->isSchemaHasAnyTable()) {
            $outputStyle->writeln(sprintf('The database <info>%s</info> exists and it contains some tables.', $this->getDatabaseName()));
            $outputStyle->writeln('<error>Warning! This action will erase your database.</error>');

            $question = new ConfirmationQuestion('Do you want to drop all of them? (y/N) ', false);
            if ($questionHelper->ask($input, $output, $question)) {
                return [
                    'doctrine:schema:drop' => ['--force' => true],
                    'doctrine:migrations:version' => ['--delete' => true, '--all' => true, '--no-interaction' => true],
                    'doctrine:migrations:migrate' => ['--no-interaction' => true],
                ];
            }

            return [];
        }

        if ($this->isEmptyDatabasePresent()) {
            $outputStyle->writeln(
                sprintf('The database <info>%s</info> already exists and it has no tables.', $this->getDatabaseName()),
            );

            return ['doctrine:migrations:migrate' => ['--no-interaction' => true]];
        }

        return [
            'doctrine:database:create',
            'doctrine:migrations:migrate' => ['--no-interaction' => true],
        ];
    }

    private function isEmptyDatabasePresent(): bool
    {
        try {
            return 0 === count($this->getEntityManager()->getConnection()->createSchemaManager()->listTableNames());
        } catch (\Exception) {
            return false;
        }
    }

    private function isSchemaHasAnyTable(): bool
    {
        try {
            return 0 !== count($this->getEntityManager()->getConnection()->createSchemaManager()->listTableNames());
        } catch (\Exception) {
            return false;
        }
    }

    private function getDatabaseName(): string
    {
        return $this->getEntityManager()->getConnection()->getDatabase();
    }

    private function getEntityManager(): EntityManagerInterface
    {
        $objectManager = $this->doctrineRegistry->getManager();
        Assert::isInstanceOf($objectManager, EntityManagerInterface::class);

        return $objectManager;
    }
}
