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

namespace Sylius\Bundle\TaxonomyBundle\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class TaxonTreeRepository implements TaxonTreeRepositoryInterface
{
    public function __construct (
        private NestedTreeRepository $nestedTreeRepository,
    ) {
    }

    public function children(?object $node = null, bool $direct = false, array|string|null $sortByField = null, array|string $direction = 'ASC', bool $includeNode = false,): array|null
    {
        return $this->nestedTreeRepository->children($node, $direct, $sortByField, $direction, $includeNode);
    }
}
