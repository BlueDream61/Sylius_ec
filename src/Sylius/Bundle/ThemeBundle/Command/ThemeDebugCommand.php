<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ThemeBundle\Command;

use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
class ThemeDebugCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('sylius:theme:debug')
            ->setDescription('Shows list of detected themes.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ThemeInterface[] $themes */
        $themes = $this->getContainer()->get('sylius.theme.repository')->findAll();

        if (0 === count($themes)) {
            $output->writeln('<error>There are no themes.</error>');
            return;
        }

        $output->writeln('<question>Succesfully loaded themes:</question>');

        $table = new Table($output);
        $table->setHeaders(['Name', 'Slug', 'Path']);

        foreach ($themes as $theme) {
            $table->addRow([$theme->getTitle(), $theme->getName(), $theme->getPath()]);
        }

        $table->setStyle('borderless');
        $table->render();
    }
}
