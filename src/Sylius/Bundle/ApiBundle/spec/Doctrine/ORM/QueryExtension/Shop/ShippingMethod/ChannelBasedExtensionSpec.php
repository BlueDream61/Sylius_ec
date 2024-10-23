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

namespace spec\Sylius\Bundle\ApiBundle\Doctrine\ORM\QueryExtension\Shop\ShippingMethod;

use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ApiBundle\SectionResolver\AdminApiSection;
use Sylius\Bundle\ApiBundle\SectionResolver\ShopApiSection;
use Sylius\Bundle\ApiBundle\Serializer\ContextKeys;
use Sylius\Bundle\CoreBundle\SectionResolver\SectionProviderInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;

final class ChannelBasedExtensionSpec extends ObjectBehavior
{
    function let(SectionProviderInterface $sectionProvider)
    {
        $this->beConstructedWith($sectionProvider);
    }

    function it_filters_shipping_method_by_current_channel(
        SectionProviderInterface $sectionProvider,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        ChannelInterface $channel,
    ): void {
        $sectionProvider->getSection()->willReturn(new ShopApiSection());

        $queryNameGenerator->generateParameterName('channel')->shouldBeCalled()->willReturn('channel');

        $queryBuilder->getRootAliases()->willReturn(['o']);
        $queryBuilder->andWhere(':channel MEMBER OF o.channels')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('channel', $channel)->shouldBeCalled()->willReturn($queryBuilder);

        $this->applyToItem(
            $queryBuilder,
            $queryNameGenerator,
            ShippingMethodInterface::class,
            [],
            new Get(),
            [ContextKeys::CHANNEL => $channel],
        );
    }

    function it_filters_shipping_methods_by_current_channel(
        SectionProviderInterface $sectionProvider,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        ChannelInterface $channel,
    ): void {
        $sectionProvider->getSection()->willReturn(new ShopApiSection());

        $queryNameGenerator->generateParameterName('channel')->shouldBeCalled()->willReturn('channel');

        $queryBuilder->getRootAliases()->willReturn(['o']);
        $queryBuilder->andWhere(':channel MEMBER OF o.channels')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->setParameter('channel', $channel)->shouldBeCalled()->willReturn($queryBuilder);

        $this->applyToCollection(
            $queryBuilder,
            $queryNameGenerator,
            ShippingMethodInterface::class,
            new GetCollection(),
            [ContextKeys::CHANNEL => $channel],
        );
    }

    function it_does_nothing_if_the_current_resource_is_not_a_shipping_method_for_item(
        SectionProviderInterface $sectionProvider,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
    ): void {
        $sectionProvider->getSection()->willReturn(new ShopApiSection());

        $queryBuilder->getRootAliases()->shouldNotBeCalled();
        $queryBuilder->andWhere(Argument::any())->shouldNotBeCalled();

        $this->applyToItem($queryBuilder, $queryNameGenerator, \stdClass::class, [], new Get());
    }

    function it_does_nothing_if_the_current_resource_is_not_a_shipping_method_for_collection(
        SectionProviderInterface $sectionProvider,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
    ): void {
        $sectionProvider->getSection()->willReturn(new ShopApiSection());

        $queryBuilder->getRootAliases()->shouldNotBeCalled();
        $queryBuilder->andWhere(Argument::any())->shouldNotBeCalled();

        $this->applyToCollection($queryBuilder, $queryNameGenerator, \stdClass::class, new GetCollection());
    }

    function it_does_nothing_if_the_current_section_is_not_a_shop_for_item(
        SectionProviderInterface $sectionProvider,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
    ): void {
        $sectionProvider->getSection()->willReturn(new AdminApiSection());

        $queryBuilder->getRootAliases()->shouldNotBeCalled();
        $queryBuilder->andWhere(Argument::any())->shouldNotBeCalled();

        $this->applyToItem($queryBuilder, $queryNameGenerator, ShippingMethodInterface::class, [], new Get());
    }

    function it_does_nothing_if_the_current_section_is_not_a_shop_for_collection(
        SectionProviderInterface $sectionProvider,
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
    ): void {
        $sectionProvider->getSection()->willReturn(new AdminApiSection());

        $queryBuilder->getRootAliases()->shouldNotBeCalled();
        $queryBuilder->andWhere(Argument::any())->shouldNotBeCalled();

        $this->applyToCollection($queryBuilder, $queryNameGenerator, ShippingMethodInterface::class, new GetCollection());
    }
}
