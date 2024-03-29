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

namespace Sylius\Bundle\ApiBundle\Applicator;

use Sylius\Abstraction\StateMachine\StateMachineInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\OrderTransitions;

final readonly class OrderStateMachineTransitionApplicator implements OrderStateMachineTransitionApplicatorInterface
{
    public function __construct(private StateMachineInterface $stateMachineFactory)
    {
    }

    public function cancel(OrderInterface $data): OrderInterface
    {
        $this->stateMachineFactory->apply($data, OrderTransitions::GRAPH, OrderTransitions::TRANSITION_CANCEL);

        return $data;
    }
}
