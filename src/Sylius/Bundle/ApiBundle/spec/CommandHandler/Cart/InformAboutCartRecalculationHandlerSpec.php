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

namespace spec\Sylius\Bundle\ApiBundle\CommandHandler\Cart;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ApiBundle\Command\Cart\InformAboutCartRecalculation;
use Sylius\Bundle\ApiBundle\CommandHandler\Cart\InformAboutCartRecalculationHandler;
use Sylius\Bundle\ApiBundle\Exception\OrderNoLongerEligibleForPromotion;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Webmozart\Assert\Assert;

final class InformAboutCartRecalculationHandlerSpec extends ObjectBehavior
{
    function it_is_a_message_handler(): void
    {
        $messageHandlerAttributes = (new \ReflectionClass(InformAboutCartRecalculationHandler::class))
            ->getAttributes(AsMessageHandler::class);

        Assert::count($messageHandlerAttributes, 1);
    }

    function it_throws_order_no_longer_eligible_for_promotion_exception(): void
    {
        $this
            ->shouldThrow(OrderNoLongerEligibleForPromotion::class)
            ->during('__invoke', [new InformAboutCartRecalculation('Holiday Sale')])
        ;
    }
}
