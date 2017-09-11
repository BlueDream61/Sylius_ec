<?php

declare(strict_types=1);

namespace Sylius\Bundle\AddressingBundle\Tests\Form\Type;

use PHPUnit\Framework\Assert;
use Prophecy\Prophecy\ProphecyInterface;
use Sylius\Bundle\AddressingBundle\Form\Type\CountryChoiceType;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

final class CountryChoiceTypeTest extends TypeTestCase
{
    /**
     * @var ProphecyInterface|RepositoryInterface
     */
    private $countryRepository;

    /**
     * @var ProphecyInterface|CountryInterface
     */
    private $france;

    /**
     * @var ProphecyInterface|CountryInterface
     */
    private $poland;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->countryRepository = $this->prophesize(RepositoryInterface::class);

        $this->france = $this->prophesize(CountryInterface::class);
        $this->france->getCode()->willReturn('FR');
        $this->france->getName()->willReturn('France');

        $this->poland = $this->prophesize(CountryInterface::class);
        $this->poland->getCode()->willReturn('PL');
        $this->poland->getName()->willReturn('Poland');

        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExtensions()
    {
        $type = new CountryChoiceType($this->countryRepository->reveal());

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    /**
     * @test
     */
    public function it_returns_only_enabled_countries_by_default()
    {
        $this->countryRepository->findBy(['enabled' => true])->willReturn([
            $this->france->reveal(),
            $this->poland->reveal(),
        ]);

        $this->assertChoicesLabels(['France', 'Poland']);
    }

    /**
     * @test
     */
    public function it_returns_all_countries()
    {
        $this->countryRepository->findAll()->willReturn([
            $this->france->reveal(),
            $this->poland->reveal(),
        ]);

        $this->assertChoicesLabels(['France', 'Poland'], ['enabled' => null]);
    }

    /**
     * @test
     */
    public function it_returns_countries_in_an_alphabetical_order()
    {
        $this->countryRepository->findBy(['enabled' => true])->willReturn([
            $this->poland->reveal(),
            $this->france->reveal(),
        ]);

        $this->assertChoicesLabels(['France', 'Poland']);
    }

    /**
     * @test
     */
    public function it_returns_filtered_out_countries()
    {
        $this->countryRepository->findBy(['enabled' => true])->willReturn([
            $this->france->reveal(),
            $this->poland->reveal(),
        ]);

        $this->assertChoicesLabels(['Poland'], ['choice_filter' => function (CountryInterface $country): bool {
            return $country->getName() === 'Poland';
        }]);
    }

    private function assertChoicesLabels(array $expectedLabels, array $formConfiguration = [])
    {
        $form = $this->factory->create(CountryChoiceType::class, null, $formConfiguration);
        $view = $form->createView();

        Assert::assertSame($expectedLabels, array_map(function (ChoiceView $choiceView): string {
            return $choiceView->label;
        }, $view->vars['choices']));
    }
}
