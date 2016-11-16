<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\OrderBundle\Controller;

use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Model\OrderItemInterface;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class AddCartItemCommand implements AddCartItemCommandInterface
{
    /**
     * @var OrderInterface
     */
    private $cart;

    /**
     * @var OrderItemInterface
     */
    private $cartItem;

    /**
     * @param OrderInterface $cart
     * @param OrderItemInterface $cartItem
     *
     * @return AddCartItemCommand
     */
    public static function createForCartAndCartItem(OrderInterface $cart, OrderItemInterface $cartItem)
    {
        return new self($cart, $cartItem);
    }

    /**
     * @param OrderInterface $cart
     * @param OrderItemInterface $cartItem
     */
    private function __construct(OrderInterface $cart, OrderItemInterface $cartItem)
    {
        $this->cart = $cart;
        $this->cartItem = $cartItem;
    }

    /**
     * @return OrderInterface
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @return OrderItemInterface
     */
    public function getCartItem()
    {
        return $this->cartItem;
    }
}
