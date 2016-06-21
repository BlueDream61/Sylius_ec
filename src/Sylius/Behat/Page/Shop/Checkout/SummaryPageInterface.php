<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Behat\Page\Shop\Checkout;

use Sylius\Behat\Page\SymfonyPageInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\ProductInterface;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
interface SummaryPageInterface extends SymfonyPageInterface
{
    /**
     * @param string $productName
     * @param string $quantity
     *
     * @return bool
     */
    public function hasItemWithProductAndQuantity($productName, $quantity);

    /**
     * @param AddressInterface $address
     *
     * @return bool
     */
    public function hasShippingAddress(AddressInterface $address);

    /**
     * @param AddressInterface $address
     *
     * @return bool
     */
    public function hasBillingAddress(AddressInterface $address);

    /**
     * @param ProductInterface $product
     * @param float $amount
     *
     * @return bool
     */
    public function hasProductDiscountedUnitPriceBy(ProductInterface $product, $amount);

    /**
     * @param float $total
     * 
     * @return bool
     */
    public function hasOrderTotal($total);

    /**
     * @param string $taxTotal
     * 
     * @return bool
     */
    public function hasTaxTotal($taxTotal);
}
