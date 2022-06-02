<?php

namespace spec\Sylius\Bundle\ApiBundle\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ApiBundle\Exception\ProvinceCannotBeRemoved;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Addressing\Model\ProvinceInterface;
use Sylius\Component\Addressing\Model\ZoneMemberInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class CountryDataPersisterSpec extends ObjectBehavior
{
    function let(
        ContextAwareDataPersisterInterface $decoratedDataPersister,
        RepositoryInterface $provinceRepository,
        RepositoryInterface $zoneMemberRepository,
    ): void {
        $this->beConstructedWith($decoratedDataPersister, $provinceRepository, $zoneMemberRepository);
    }

    function it_supports_only_zone_entity(CountryInterface $country, ProductInterface $product): void
    {
        $this->supports($country)->shouldReturn(true);
        $this->supports($product)->shouldReturn(false);
    }

    function it_uses_decorated_data_persister_to_remove_country(
        ContextAwareDataPersisterInterface $decoratedDataPersister,
        CountryInterface $country,
    ): void {
        $decoratedDataPersister->remove($country, [])->shouldBeCalled();

        $this->remove($country, []);
    }

    function it_uses_decorated_data_persister_to_persist_country(
        ContextAwareDataPersisterInterface $decoratedDataPersister,
        RepositoryInterface $provinceRepository,
        RepositoryInterface $zoneMemberRepository,
        CountryInterface $country,
        ProvinceInterface $firstProvince,
        ProvinceInterface $secondProvince,
        ProvinceInterface $thirdProvince
    ): void {
        $firstProvince->getCode()->willReturn('FIRST_PROVINCE');
        $secondProvince->getCode()->willReturn('SECOND_PROVINCE');
        $thirdProvince->getCode()->willReturn('THIRD_PROVINCE');

        $country->getProvinces()->willReturn(new ArrayCollection([$secondProvince->getWrappedObject()]));
        $provinceRepository->findBy(['country' => $country])->willReturn([
            $firstProvince->getWrappedObject(),
            $secondProvince->getWrappedObject(),
            $thirdProvince->getWrappedObject(),
        ]);

        $zoneMemberRepository
            ->findOneBy(['code' => [0 => 'FIRST_PROVINCE', 2 => 'THIRD_PROVINCE']])
            ->willReturn(null)
        ;

        $decoratedDataPersister->persist($country, [])->shouldBeCalled();

        $this->persist($country, []);
    }

    function it_throws_an_error_if_the_province_within_a_country_is_in_use(
        ContextAwareDataPersisterInterface $decoratedDataPersister,
        RepositoryInterface $provinceRepository,
        RepositoryInterface $zoneMemberRepository,
        CountryInterface $country,
        ProvinceInterface $firstProvince,
        ProvinceInterface $secondProvince,
        ProvinceInterface $thirdProvince,
        ZoneMemberInterface $zoneMember
    ): void {
        $firstProvince->getCode()->willReturn('FIRST_PROVINCE');
        $secondProvince->getCode()->willReturn('SECOND_PROVINCE');
        $thirdProvince->getCode()->willReturn('THIRD_PROVINCE');

        $country->getProvinces()->willReturn(new ArrayCollection([$secondProvince->getWrappedObject()]));
        $provinceRepository->findBy(['country' => $country])->willReturn([
            $firstProvince->getWrappedObject(),
            $secondProvince->getWrappedObject(),
            $thirdProvince->getWrappedObject(),
        ]);

        $zoneMemberRepository
            ->findOneBy(['code' => [0 => 'FIRST_PROVINCE', 2 => 'THIRD_PROVINCE']])
            ->willReturn($zoneMember)
        ;

        $decoratedDataPersister->persist($country, [])->shouldNotBeCalled();

        $this
            ->shouldThrow(ProvinceCannotBeRemoved::class)
            ->during('persist', [$country, []])
        ;
    }
}
