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

namespace Sylius\Bundle\ApiBundle\CommandHandler\Cart;

use Sylius\Bundle\ApiBundle\Command\Cart\ChangeItemQuantityInCart;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Sylius\Component\Order\Repository\OrderItemRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

final readonly class ChangeItemQuantityInCartHandler implements MessageHandlerInterface
{
    /** @param OrderItemRepositoryInterface<OrderItemInterface> $orderItemRepository */
    public function __construct(
        private OrderItemRepositoryInterface $orderItemRepository,
        private OrderItemQuantityModifierInterface $orderItemQuantityModifier,
        private OrderProcessorInterface $orderProcessor,
    ) {
    }

    public function __invoke(ChangeItemQuantityInCart $command): OrderInterface
    {
        /** @var OrderItemInterface|null $orderItem */
        $orderItem = $this->orderItemRepository->findOneByIdAndCartTokenValue(
            $command->getOrderItemId(),
            $command->getOrderTokenValue(),
        );

        Assert::notNull($orderItem);

        /** @var OrderInterface $cart */
        $cart = $orderItem->getOrder();

        Assert::same($cart->getTokenValue(), $command->getOrderTokenValue());

        $this->orderItemQuantityModifier->modify($orderItem, $command->getQuantity());
        $this->orderProcessor->process($cart);

        return $cart;
    }
}
