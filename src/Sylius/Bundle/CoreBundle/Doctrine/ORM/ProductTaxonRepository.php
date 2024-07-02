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

namespace Sylius\Bundle\CoreBundle\Doctrine\ORM;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Core\Repository\ProductTaxonRepositoryInterface;

/**
 * @template T of ProductTaxonInterface
 *
 * @implements ProductTaxonRepositoryInterface<T>
 */
class ProductTaxonRepository extends EntityRepository implements ProductTaxonRepositoryInterface
{
    public function createListQueryBuilderForTaxon(string $locale, int|string $taxonId): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.product', 'product')
            ->innerJoin('product.translations', 'translations', Join::WITH, 'translations.locale = :locale')
            ->andWhere('o.taxon = :taxonId')
            ->setParameter('taxonId', $taxonId)
            ->setParameter('locale', $locale)
        ;
    }

    public function findOneByProductCodeAndTaxonCode(string $productCode, string $taxonCode): ?ProductTaxonInterface
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.product', 'product')
            ->andWhere('product.code = :productCode')
            ->setParameter('productCode', $productCode)
            ->innerJoin('o.taxon', 'taxon')
            ->andWhere('taxon.code = :taxonCode')
            ->setParameter('taxonCode', $taxonCode)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
