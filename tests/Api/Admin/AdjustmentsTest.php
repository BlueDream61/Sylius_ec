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

namespace Sylius\Tests\Api\Admin;

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
    public function it_gets_adjustments(): void
    {
        $this->loadFixturesFromFiles([
            'authentication/api_administrator.yaml',
            'channel.yaml',
            'cart.yaml', 'country.yaml',
            'shipping_method.yaml',
            'payment_method.yaml'
        ]);

        $this->placeOrder('token');
        $this->placeOrder('token2');

        $this->client->request(
            method: 'GET',
            uri: '/api/v2/admin/adjustments',
            server: $this->headerBuilder()->withJsonLdAccept()->withAdminUserAuthorization('api@example.com')->build(),
        );

        $this->assertResponse(
            $this->client->getResponse(),
            'admin/adjustments/get_adjustments',
            Response::HTTP_OK,
        );
    }

    /** @test */
    public function it_gets_adjustment_by_id(): void
    {
        $this->loadFixturesFromFiles([
            'authentication/api_administrator.yaml',
            'channel.yaml',
            'cart.yaml',
            'country.yaml',
            'shipping_method.yaml',
            'payment_method.yaml'
        ]);

        $order = $this->placeOrder('token');

        $this->client->request(
            method: 'GET',
            uri: sprintf('/api/v2/admin/adjustments/%d', $order->getAdjustments()->first()->getId()),
            server: $this->headerBuilder()->withJsonLdAccept()->withAdminUserAuthorization('api@example.com')->build(),
        );

        $this->assertResponse(
            $this->client->getResponse(),
            'admin/adjustments/get_adjustment',
            Response::HTTP_OK,
        );
    }
}
