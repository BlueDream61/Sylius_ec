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

namespace Sylius\Bundle\ShopBundle\Twig\Component\Cart;

use Sylius\Bundle\UiBundle\Twig\Component\ResourceLivePropTrait;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Context\CartNotFoundException;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsLiveComponent]
class WidgetComponent
{
    /** @use ResourceLivePropTrait<OrderInterface> */
    use ResourceLivePropTrait;

    #[LiveProp(hydrateWith: 'hydrateResource', dehydrateWith: 'dehydrateResource')]
    public ?ResourceInterface $cart = null;

    /** @param OrderRepositoryInterface<OrderInterface> $orderRepository */
    public function __construct(
        private readonly CartContextInterface $cartContext,
        OrderRepositoryInterface $orderRepository,
    ) {
        $this->initialize($orderRepository);
    }

    #[PreMount]
    public function initializeCart(): void
    {
        $this->cart = $this->getCart();
    }

    #[LiveListener(FormComponent::SYLIUS_SHOP_CART_CHANGED)]
    #[LiveListener(FormComponent::SYLIUS_SHOP_CART_CLEARED)]
    public function refreshCart(#[LiveArg] ?OrderInterface $cart = null): void
    {
        $this->cart = $cart ?? $this->getCart();
    }

    public function dehydrate(OrderInterface|null $order): mixed
    {
        return $order?->getId();
    }

    public function hydrate(): OrderInterface
    {
        return $this->getCart();
    }

    private function getCart(): ?OrderInterface
    {
        try {
            /** @var OrderInterface $cart */
            $cart = $this->cartContext->getCart();
        } catch (CartNotFoundException) {
            return null;
        }

        return $cart;
    }
}
