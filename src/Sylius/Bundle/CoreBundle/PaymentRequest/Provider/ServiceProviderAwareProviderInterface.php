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

namespace Sylius\Bundle\CoreBundle\PaymentRequest\Provider;

interface ServiceProviderAwareProviderInterface extends HttpResponseProviderInterface
{
    public function getHttpResponseProvider(string $index): ?HttpResponseProviderInterface;

    /** @return string[] */
    public function getProviderIndex(): array;
}
