<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Tests\Api\Admin;

use Sylius\Tests\Api\JsonApiTestCase;
use Sylius\Tests\Api\Utils\AdminUserLoginTrait;
use Sylius\Tests\Api\Utils\OrderPlacerTrait;
use Symfony\Component\HttpFoundation\Response;

final class OrdersTest extends JsonApiTestCase
{
    use AdminUserLoginTrait;
    use OrderPlacerTrait;

    /** @test */
    public function it_gets_an_order(): void
    {
        $this->loadFixturesFromFiles(['authentication/api_administrator.yaml', 'cart.yaml', 'country.yaml', 'shipping_method.yaml', 'payment_method.yaml']);

        $token = $this->logInAdminUser('api@example.com', 'sylius');
        $authorizationHeader = self::$container->getParameter('sylius.api.authorization_header');
        $header['HTTP_' . $authorizationHeader] = 'Bearer ' . $token;
        $header = array_merge($header, self::CONTENT_TYPE_HEADER);

        $tokenValue = 'nAWw2jewpA';

        $this->placeOrder($tokenValue, $header);

        $this->client->request('GET', '/api/v2/admin/orders/nAWw2jewpA', [], [], $header);
        $response = $this->client->getResponse();
        $this->assertResponse($response, 'admin/get_order_response', Response::HTTP_OK);
    }
}
