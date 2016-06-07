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

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
interface AddressingPageInterface extends SymfonyPageInterface
{
    /**
     * @throws \RuntimeException
     */
    public function chooseDifferentBillingAddress();

    /**
     * @param string $element
     * @param string $message
     * 
     * @return bool
     */
    public function checkValidationMessageFor($element, $message);

    /**
     * @param AddressInterface $billingAddress
     */
    public function specifyBillingAddress(AddressInterface $billingAddress);

    /**
     * @param AddressInterface $shippingAddress
     */
    public function specifyShippingAddress(AddressInterface $shippingAddress);

    public function nextStep();
}
