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

namespace Sylius\Bundle\CoreBundle\OrderPay\Provider;

use Sylius\Bundle\CoreBundle\OrderPay\Processor\RouteParametersProcessorInterface;
use Sylius\Component\Payment\Model\PaymentRequestInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/** @experimental */
final class AfterPayUrlProvider implements AfterPayUrlProviderInterface
{
    /**
     * @param array<string, string> $afterPayRouteParameters
     */
    public function __construct(
        private RouteParametersProcessorInterface $routeParametersProcessor,
        private string $afterPayRoute,
        private array $afterPayRouteParameters,
    ) {
    }

    public function getUrl(
        PaymentRequestInterface $paymentRequest,
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH,
    ): string {
        $context = [
            'paymentRequest' => $paymentRequest,
            'payment' => $paymentRequest->getPayment(),
            'method' => $paymentRequest->getMethod(),
        ];

        return $this->routeParametersProcessor->process(
            $this->afterPayRoute,
            $this->afterPayRouteParameters,
            $referenceType,
            $context,
        );
    }
}
