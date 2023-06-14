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

namespace Sylius\Bundle\CoreBundle\Fixture;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ProductReviewFixture extends AbstractResourceFixture
{
    public function getName(): string
    {
        return 'product_review';
    }

    protected function configureResourceNode(ArrayNodeDefinition $resourceNode): void
    {
        $resourceNode
            ->children()
                ->scalarNode('title')->cannotBeEmpty()->end()
                ->scalarNode('rating')->cannotBeEmpty()->end()
                ->scalarNode('comment')->cannotBeEmpty()->end()
                ->scalarNode('author')->cannotBeEmpty()->end()
                ->scalarNode('product')->cannotBeEmpty()->end()
                ->scalarNode('status')->end()
        ;
    }
}
