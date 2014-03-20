<?php

/*
 * This file is part of the Sylius package.
*
* (c) Paweł Jędrzejewski
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Sylius\Bundle\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sylius\Bundle\CoreBundle\SyliusOrderEvents;
use Symfony\Component\EventDispatcher\GenericEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\CoreBundle\Repository\OrderRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Command to release expired pending orders
 *
 * @author Foo Pang <foo.pang@gmail.com>
 */
class ReleaseOrdersCommand extends ContainerAwareCommand
{
    /**
     * Order manager.
     *
     * @var ObjectManager
     */
    protected $manager;

    /**
     * Order repository.
     *
     * @var OrderRepository
     */
    protected $repository;

    /**
     * Event dispatcher.
     *
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    protected function configure()
    {
        $this
            ->setName('sylius:order:release')
            ->setDescription('Release expired pending orders')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->manager = $this->getContainer()->get('sylius.manager.order');
        $this->repository = $this->getContainer()->get('sylius.repository.order');
        $this->dispatcher = $this->getContainer()->get('event_dispatcher');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $holdingDuration = $this->getContainer()->getParameter('sylius.inventory.holding.duration');
        $expiresAt = new \DateTime(sprintf('-%s', $holdingDuration));

        if ($input->isInteractive()) {
            $dialog = $this->getHelperSet()->get('dialog');
            $dialog->askAndValidate($output, sprintf('<question>Inventory holding duration (%s)?</question>', $holdingDuration), function ($response) use ($holdingDuration, &$expiresAt) {
                if (null !== $response) {
                    $holdingDuration = $response;
                }
                $expiresAt = new \DateTime(sprintf('-%s', $holdingDuration));

                return $holdingDuration;
            });
        }

        $output->writeln('Release expired pending orders...');

        $orders = $this->repository->findExpiredPendingOrders($expiresAt);

        foreach ($orders as $order) {
            $this->dispatcher->dispatch(SyliusOrderEvents::PRE_RELEASE, new GenericEvent($order));
            $this->manager->persist($order);
        }
        $this->manager->flush();

        foreach ($orders as $order) {
            $this->dispatcher->dispatch(SyliusOrderEvents::POST_RELEASE, new GenericEvent($order));
        }

        $output->writeln('Expired pending orders released.');
    }
}
