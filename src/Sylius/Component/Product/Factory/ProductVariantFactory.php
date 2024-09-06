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

namespace Sylius\Component\Product\Factory;

use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Resource\Factory\FactoryInterface;

/**
 * @template T of ProductVariantInterface
 *
 * @implements ProductVariantFactoryInterface<T>
 */
class ProductVariantFactory implements ProductVariantFactoryInterface
{
    /** @param FactoryInterface<T> $factory */
    public function __construct(private FactoryInterface $factory)
    {
    }

    public function createNew(): ProductVariantInterface
    {
        return $this->factory->createNew();
    }

    public function createForProduct(ProductInterface $product): ProductVariantInterface
    {
        $variant = $this->createNew();
        $variant->setProduct($product);

        return $variant;
    }
}
