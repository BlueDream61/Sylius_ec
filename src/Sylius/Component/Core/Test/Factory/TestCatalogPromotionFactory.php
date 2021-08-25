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

namespace Sylius\Component\Core\Test\Factory;

use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Promotion\Model\CatalogPromotionInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class TestCatalogPromotionFactory implements TestCatalogPromotionFactoryInterface
{
    private FactoryInterface $catalogPromotionFactory;

    public function __construct(FactoryInterface $catalogPromotionFactory)
    {
        $this->catalogPromotionFactory = $catalogPromotionFactory;
    }

    public function create(string $name): CatalogPromotionInterface
    {
        /** @var CatalogPromotionInterface $catalogPromotion */
        $catalogPromotion = $this->catalogPromotionFactory->createNew();

        $catalogPromotion->setName($name);
        $catalogPromotion->setCode(StringInflector::nameToLowercaseCode($name));

        return $catalogPromotion;
    }
}
