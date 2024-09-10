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

namespace Sylius\Behat\Context\Api\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\Client\ApiClientInterface;
use Sylius\Behat\Client\ResponseCheckerInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Webmozart\Assert\Assert;

final readonly class PaymentContext implements Context
{
    public function __construct(
        private ApiClientInterface $client,
        private ResponseCheckerInterface $responseChecker,
        private SharedStorageInterface $sharedStorage,
    ) {
    }

    /**
     * @When I try to see the payment of the order placed by a customer :customer
     */
    public function iTryToSeeThePaymentOfTheOrderPlacedByACustomer(CustomerInterface $customer): void
    {
        /** @var OrderInterface $order */
        $order = $this->sharedStorage->get('order');
        Assert::eq($order->getCustomer(), $customer);

        /** @var PaymentInterface $payment */
        $payment = $order->getPayments()->first();

        $this->client->requestGet(
            uri: sprintf('api/v2/shop/orders/%s/payments/%s', $order->getTokenValue(), $payment->getId()),
        );
    }

    /**
     * @Then I should not be able to see that payment
     */
    public function iShouldNotBeAbleToSeeThatPayment(): void
    {
        Assert::false($this->responseChecker->isShowSuccessful($this->client->getLastResponse()));
    }

    /**
     * @Then I should see its payment state as :state
     */
    public function iShouldSeeItsPaymentStateAs(string $state): void
    {
        $response = $this->client->getLastResponse();
        $payments = $this->responseChecker->getValue($response, 'payments');
        $token = $this->responseChecker->getValue($response, 'tokenValue');

        $response = $this->client->requestGet(sprintf('/api/v2/shop/orders/%s/payments/%s', $token, $payments[0]['id']));

        Assert::true($this->responseChecker->hasValue($response, 'state', $state, isCaseSensitive: false));
    }
}
