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

namespace Sylius\Tests\Api\Shop;

use Sylius\Tests\Api\JsonApiTestCase;
use Sylius\Tests\Api\Utils\OrderPlacerTrait;
use Symfony\Component\HttpFoundation\Response;

final class AdjustmentsTest extends JsonApiTestCase
{
    use OrderPlacerTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpOrderPlacer();
    }

    /** @test */
    public function it_gets_adjustment_by_id(): void
    {
        $this->loadFixturesFromFiles(['channel.yaml', 'cart.yaml', 'country.yaml', 'shipping_method.yaml', 'payment_method.yaml']);

        $order = $this->placeOrder('token');

        $this->client->request(
            method: 'GET',
            uri: '/api/v2/shop/adjustments/' . $order->getAdjustments()->first()->getId(),
            server: $this->headerBuilder()->withJsonLdAccept()->build(),
        );

        $this->assertResponse(
            $this->client->getResponse(),
            'shop/adjustments/get_adjustment_response',
            Response::HTTP_OK,
        );
    }
}
