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

namespace spec\Sylius\Bundle\CoreBundle\Listener;

use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use SM\Factory\FactoryInterface;
use Sylius\Bundle\CoreBundle\Processor\RequestProductVariantCatalogPromotionRecalculateInterface;
use Sylius\Component\Core\Model\CatalogPromotionInterface;
use Sylius\Component\Promotion\Event\CatalogPromotionUpdated;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class CatalogPromotionUpdatedListenerSpec extends ObjectBehavior
{
    function let(
        RequestProductVariantCatalogPromotionRecalculateInterface $catalogPromotionReprocessor,
        RepositoryInterface                                       $catalogPromotionRepository,
        EntityManagerInterface                                    $entityManager,
        FactoryInterface                                          $stateMachine
    ): void {
        $this->beConstructedWith($catalogPromotionReprocessor, $catalogPromotionRepository, $entityManager, $stateMachine);
    }

    function it_processes_catalog_promotion_that_has_just_been_updated(
        RequestProductVariantCatalogPromotionRecalculateInterface $catalogPromotionReprocessor,
        RepositoryInterface                                       $catalogPromotionRepository,
        EntityManagerInterface                                    $entityManager,
        CatalogPromotionInterface                                 $catalogPromotion
    ): void {
        $catalogPromotionRepository->findOneBy(['code' => 'WINTER_MUGS_SALE'])->willReturn($catalogPromotion);

        $catalogPromotionReprocessor->recalculate()->shouldBeCalled();

        $entityManager->flush()->shouldBeCalled();

        $this(new CatalogPromotionUpdated('WINTER_MUGS_SALE'));
    }

    function it_does_nothing_if_there_is_no_catalog_promotion_with_given_code(
        RequestProductVariantCatalogPromotionRecalculateInterface $catalogPromotionReprocessor,
        RepositoryInterface                                       $catalogPromotionRepository,
        EntityManagerInterface                                    $entityManager
    ): void {
        $catalogPromotionRepository->findOneBy(['code' => 'WINTER_MUGS_SALE'])->willReturn(null);
        $catalogPromotionRepository->findAll()->shouldNotBeCalled();

        $catalogPromotionReprocessor->recalculate()->shouldNotBeCalled();
        $entityManager->flush()->shouldNotBeCalled();

        $this(new CatalogPromotionUpdated('WINTER_MUGS_SALE'));
    }
}
