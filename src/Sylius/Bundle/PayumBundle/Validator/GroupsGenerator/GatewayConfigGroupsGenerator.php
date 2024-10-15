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

namespace Sylius\Bundle\PayumBundle\Validator\GroupsGenerator;

use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\Form\FormInterface;
use Webmozart\Assert\Assert;

trigger_deprecation(
    'sylius/payum-bundle',
    '1.14',
    'The "%s" class is deprecated and will be moved to the PaymentBundle in Sylius 2.0.',
    GatewayConfigGroupsGenerator::class,
);
/**
 * @deprecated since Sylius 1.14 and will be moved to the PaymentBundle in Sylius 2.0.
 * @internal
 */
class GatewayConfigGroupsGenerator
{
    /** @param array<string, array<string, string>> $validationGroups */
    public function __construct(private array $validationGroups)
    {
    }

    /**
     * @param FormInterface|PaymentMethodInterface $object
     *
     * @return array<string>
     */
    public function __invoke($object): array
    {
        if ($object instanceof FormInterface) {
            $object = $object->getData();
        }

        Assert::isInstanceOf($object, PaymentMethodInterface::class);

        if ($object->getGatewayConfig()?->getFactoryName() === null) {
            return ['sylius'];
        }

        return $this->validationGroups[$object->getGatewayConfig()->getFactoryName()] ?? ['sylius'];
    }
}
