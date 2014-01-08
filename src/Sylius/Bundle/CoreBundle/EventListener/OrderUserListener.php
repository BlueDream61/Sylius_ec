<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\EventListener;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\CoreBundle\Model\OrderInterface;

class OrderUserListener
{
    protected $securityContext;
    protected $orderManager;

    public function __construct(SecurityContextInterface $securityContext, ObjectManager $orderManager)
    {
        $this->securityContext = $securityContext;
        $this->orderManager = $orderManager;
    }

    public function setOrderUser(GenericEvent $event)
    {
        $order = $event->getSubject();

        if (!$order instanceof OrderInterface) {
            throw new \InvalidArgumentException(
                'Order user listener requires event subject to be instance of "Sylius\Bundle\CoreBundle\Model\OrderInterface"'
            );
        }

        if (null === $user = $this->getUser()) {
            return;
        }

        $order->setUser($user);

        $this->orderManager->persist($order);
        $this->orderManager->flush();
    }

    protected function getUser()
    {
        if ((null !== $token = $this->securityContext->getToken()) && is_object($user = $token->getUser())) {
            return $user;
        }
    }
}
