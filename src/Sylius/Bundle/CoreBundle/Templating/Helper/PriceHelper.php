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

namespace Sylius\Bundle\CoreBundle\Templating\Helper;

use Sylius\Component\Core\Calculator\ProductVariantPriceCalculator;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Templating\Helper\Helper;
use Webmozart\Assert\Assert;

trigger_deprecation(
    'sylius/core-bundle',
    '1.14',
    'The "%s" class is deprecated, use "%s" instead.',
    PriceHelper::class,
    ProductVariantPriceCalculator::class,
);

/** @deprecated since Sylius 1.14 and will be removed in Sylius 2.0. Use {@see \Sylius\Component\Core\Calculator\ProductVariantPriceCalculator} instead. */
class PriceHelper extends Helper
{
    public function __construct(private ProductVariantPricesCalculatorInterface $productVariantPricesCalculator)
    {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function getPrice(ProductVariantInterface $productVariant, array $context): int
    {
        Assert::keyExists($context, 'channel');

        return $this
            ->productVariantPricesCalculator
            ->calculate($productVariant, $context)
        ;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function getOriginalPrice(ProductVariantInterface $productVariant, array $context): int
    {
        Assert::keyExists($context, 'channel');

        return $this
            ->productVariantPricesCalculator
            ->calculateOriginal($productVariant, $context)
        ;
    }

    public function getLowestPriceBeforeDiscount(ProductVariantInterface $productVariant, array $context): ?int
    {
        Assert::keyExists($context, 'channel');

        return $this
            ->productVariantPricesCalculator
            ->calculateLowestPriceBeforeDiscount($productVariant, $context)
        ;
    }

    public function hasLowestPriceBeforeDiscount(ProductVariantInterface $productVariant, array $context): bool
    {
        Assert::keyExists($context, 'channel');

        return null !== $this->getLowestPriceBeforeDiscount($productVariant, $context);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function hasDiscount(ProductVariantInterface $productVariant, array $context): bool
    {
        Assert::keyExists($context, 'channel');

        return $this->getOriginalPrice($productVariant, $context) > $this->getPrice($productVariant, $context);
    }

    public function getName(): string
    {
        return 'sylius_calculate_price';
    }
}
