<?php

declare(strict_types=1);

namespace Sylius\Component\Product\Resolver;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

interface AvailableProductOptionValuesResolverInterface
{
    /**
     * @return Collection|ProductOptionValueInterface[]
     *
     * @psalm-return Collection<array-key, ProductOptionValueInterface>
     */
    public function resolve(ProductInterface $product, ProductOptionInterface $productOption): Collection;
}
