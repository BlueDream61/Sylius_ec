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
use Sylius\Component\Core\ProductReviewTransitions;
use Sylius\Component\Review\Model\ReviewInterface;

final readonly class ProductReviewStateMachineTransitionApplicator implements ProductReviewStateMachineTransitionApplicatorInterface
{
    public function __construct(private StateMachineInterface $stateMachineFactory)
    {
    }

    public function accept(ReviewInterface $data): ReviewInterface
    {
        $this->applyTransition($data, ProductReviewTransitions::TRANSITION_ACCEPT);

        return $data;
    }

    public function reject(ReviewInterface $data): ReviewInterface
    {
        $this->applyTransition($data, ProductReviewTransitions::TRANSITION_REJECT);

        return $data;
    }

    private function applyTransition(ReviewInterface $review, string $transition): void
    {
        $this->stateMachineFactory->apply($review, ProductReviewTransitions::GRAPH, $transition);
    }
}
