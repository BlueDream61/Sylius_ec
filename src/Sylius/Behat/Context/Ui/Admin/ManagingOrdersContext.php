<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Sylius\Behat\Page\Admin\Crud\IndexPageInterface;
use Sylius\Behat\Page\Admin\Order\ShowPageInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Webmozart\Assert\Assert;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Grzegorz Sadowski <grzegorz.sadowski@lakion.com>
 */
final class ManagingOrdersContext implements Context
{
    const RESOURCE_NAME = 'order';

    /**
     * @var IndexPageInterface
     */
    private $indexPage;

    /**
     * @var ShowPageInterface
     */
    private $showPage;

    /**
     * @param IndexPageInterface $indexPage
     * @param ShowPageInterface $showPage
     */
    public function __construct(
        IndexPageInterface $indexPage,
        ShowPageInterface $showPage
    ) {
        $this->indexPage = $indexPage;
        $this->showPage = $showPage;
    }

    /**
     * @When I browse new orders
     */
    public function iBrowseNewOrders()
    {
        $this->indexPage->open();
    }

    /**
     * @When I see the :order order
     */
    public function iSeeTheOrder(OrderInterface $order)
    {
        $this->showPage->open(['id' => $order->getId()]);
    }

    /**
     * @Then I should see a single order from customer :customer
     */
    public function iShouldSeeASingleOrderFromCustomer(CustomerInterface $customer)
    {
        Assert::true(
            $this->indexPage->isSingleResourceOnPage(['customer' => $customer->getEmail()]),
            sprintf('Cannot find order for customer "%s" in the list.', $customer->getEmail())
        );
    }

    /**
     * @Then I should see :customerEmail customer
     */
    public function iShouldSeeCustomer($customerEmail)
    {
        Assert::true(
            $this->showPage->hasCustomer($customerEmail),
            sprintf('Cannot find customer "%s".', $customerEmail)
        );
    }

    /**
     * @Then I should see :customerName, :street, :postcode, :city, :countryName shipping address
     */
    public function iShouldSeeShippingAddress($customerName, $street, $postcode, $city, $countryName)
    {
        Assert::true(
            $this->showPage->hasShippingAddress($customerName, $street, $postcode, $city, $countryName),
            sprintf('Cannot find shipping address "%s, %s %s, %s".', $street, $postcode, $city, $countryName)
        );
    }

    /**
     * @Then I should see :customerName, :street, :postcode, :city, :countryName billing address
     */
    public function iShouldSeeBillingAddress($customerName, $street, $postcode, $city, $countryName)
    {
        Assert::true(
            $this->showPage->hasBillingAddress($customerName, $street, $postcode, $city, $countryName),
            sprintf('Cannot find shipping address "%s, %s %s, %s".', $street, $postcode, $city, $countryName)
        );
    }

    /**
     * @Then I should see :shippingMethodName shipment
     */
    public function iShouldSeeShipment($shippingMethodName)
    {
        Assert::true(
            $this->showPage->hasShipment($shippingMethodName),
            sprintf('Cannot find shipment "%s".', $shippingMethodName)
        );
    }

    /**
     * @Then I should see :paymentMethodName payment
     */
    public function iShouldSeePayment($paymentMethodName)
    {
        Assert::true(
            $this->showPage->hasPayment($paymentMethodName),
            sprintf('Cannot find payment "%s".', $paymentMethodName)
        );
    }

    /**
     * @Then /^I should see (\d+) items in the list$/
     */
    public function iShouldSeeItemsInTheList($amount)
    {
        $itemsCount = $this->showPage->countItems();

        Assert::eq(
            $amount,
            $itemsCount,
            sprintf('There should be %d items in the list, but get %d.', $amount, $itemsCount)
        );
    }

    /**
     * @Then I should see the product named :productName in the list
     * @Then the product named :productName should be in the items list
     */
    public function theProductShouldBeInTheItemsList($productName)
    {
        Assert::true(
            $this->showPage->isProductInTheList($productName),
            sprintf('Product %s is not in the item list.', $productName)
        );
    }

    /**
     * @Then the order's items total should be :itemsTotal
     */
    public function theOrdersItemsTotalShouldBe($itemsTotal)
    {
        $itemsTotalOnPage = $this->showPage->getItemsTotal();

        Assert::eq(
            $itemsTotal,
            $itemsTotalOnPage,
            sprintf('Items total is "%s", but should be "%s".', $itemsTotalOnPage, $itemsTotal)
        );
    }

    /**
     * @Then the order's total should be :total
     */
    public function theOrdersTotalShouldBe($total)
    {
        $totalOnPage = $this->showPage->getTotal();

        Assert::eq(
            $total,
            $totalOnPage,
            sprintf('Total is "%s", but should be "%s".', $totalOnPage, $total)
        );
    }

    /**
     * @Then the order's shipping charges should be :shippingCharge
     */
    public function theOrdersShippingChargesShouldBe($shippingCharge)
    {
        Assert::true(
            $this->showPage->hasShippingCharge($shippingCharge),
            sprintf('Shipping charges is not "%s".', $shippingCharge)
        );
    }

    /**
     * @Then the order's shipping total should be :shippingTotal
     */
    public function theOrdersShippingTotalShouldBe($shippingTotal)
    {
        $shippingTotalOnPage = $this->showPage->getShippingTotal();

        Assert::eq(
            $shippingTotal,
            $shippingTotalOnPage,
            sprintf('Shipping total is "%s", but should be "%s".', $shippingTotalOnPage, $shippingTotal)
        );
    }
}
