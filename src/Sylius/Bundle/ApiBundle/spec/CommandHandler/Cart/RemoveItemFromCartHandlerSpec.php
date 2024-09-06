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
use Sylius\Bundle\ApiBundle\Command\Cart\RemoveItemFromCart;
use Sylius\Bundle\ApiBundle\CommandHandler\Cart\RemoveItemFromCartHandler;
use Sylius\Bundle\OrderBundle\Doctrine\ORM\OrderItemRepository;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Order\Modifier\OrderModifierInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Webmozart\Assert\Assert;

final class RemoveItemFromCartHandlerSpec extends ObjectBehavior
{
    function let(
        OrderItemRepository $orderItemRepository,
        OrderModifierInterface $orderModifier,
        ProductVariantResolverInterface $variantResolver,
    ): void {
        $this->beConstructedWith(
            $orderItemRepository,
            $orderModifier,
            $variantResolver,
        );
    }

    function it_is_a_message_handler(): void
    {
        $messageHandlerAttributes = (new \ReflectionClass(RemoveItemFromCartHandler::class))
            ->getAttributes(AsMessageHandler::class);

        Assert::count($messageHandlerAttributes, 1);
    }

    function it_removes_order_item_from_cart(
        OrderItemRepository $orderItemRepository,
        OrderModifierInterface $orderModifier,
        OrderInterface $cart,
        OrderItemInterface $cartItem,
    ): void {
        $orderItemRepository->findOneByIdAndCartTokenValue(
            'ORDER_ITEM_ID',
            'TOKEN_VALUE',
        )->willReturn($cartItem);

        $cartItem->getOrder()->willReturn($cart);

        $cart->getTokenValue()->willReturn('TOKEN_VALUE');

        $orderModifier->removeFromOrder($cart, $cartItem)->shouldBeCalled();

        $this(new RemoveItemFromCart(orderTokenValue: 'TOKEN_VALUE', itemId: 'ORDER_ITEM_ID'))->shouldReturn($cart);
    }

    function it_throws_an_exception_if_order_item_was_not_found(
        OrderItemRepository $orderItemRepository,
        OrderItemInterface $cartItem,
    ): void {
        $orderItemRepository->findOneByIdAndCartTokenValue(
            'ORDER_ITEM_ID',
            'TOKEN_VALUE',
        )->willReturn(null);

        $cartItem->getOrder()->shouldNotBeCalled();

        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during(
                '__invoke',
                [new RemoveItemFromCart(orderTokenValue: 'TOKEN_VALUE', itemId: 'ORDER_ITEM_ID')],
            )
        ;
    }

    function it_throws_an_exception_if_cart_token_value_was_not_properly(
        OrderItemRepository $orderItemRepository,
        OrderModifierInterface $orderModifier,
        OrderItemInterface $cartItem,
        OrderInterface $cart,
    ): void {
        $orderItemRepository->findOneByIdAndCartTokenValue(
            'ORDER_ITEM_ID',
            'TOKEN_VALUE',
        )->willReturn($cartItem);

        $cartItem->getOrder()->willReturn($cart);

        $cart->getTokenValue()->willReturn('WRONG_TOKEN_VALUE_');

        $orderModifier->removeFromOrder(null, $cartItem)->shouldNotBeCalled();

        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during(
                '__invoke',
                [new RemoveItemFromCart(orderTokenValue: 'TOKEN_VALUE', itemId: 'ORDER_ITEM_ID')],
            )
        ;
    }
}
