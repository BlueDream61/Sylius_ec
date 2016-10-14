<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\GridBundle\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\GridBundle\Doctrine\ORM\DataSource;
use Sylius\Bundle\GridBundle\Doctrine\ORM\Driver;
use Sylius\Component\Grid\Data\DriverInterface;
use Sylius\Component\Grid\Parameters;

/**
 * @mixin Driver
 *
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
final class DriverSpec extends ObjectBehavior
{
    function let(EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($entityManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Driver::class);
    }

    function it_implements_grid_driver()
    {
        $this->shouldImplement(DriverInterface::class);
    }

    function it_throws_exception_if_class_is_undefined()
    {
        $this
            ->shouldThrow(\InvalidArgumentException::class)
            ->during('getDataSource', [[], new Parameters()])
        ;
    }

    function it_creates_data_source_via_doctrine_orm_query_builder(
        EntityManagerInterface $entityManager,
        EntityRepository $entityRepository,
        QueryBuilder $queryBuilder
    ) {
        $entityManager->getRepository('App:Book')->willReturn($entityRepository);
        $entityRepository->createQueryBuilder('o')->willReturn($queryBuilder);

        $this->getDataSource(['class' => 'App:Book'], new Parameters())->shouldHaveType(DataSource::class);
    }
}
