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

namespace Sylius\Bundle\CoreBundle\Fixture;

use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class LocaleFixture extends AbstractFixture
{
    private FactoryInterface $localeFactory;

    private ObjectManager $localeManager;

    private string $baseLocaleCode;

    /**
     * @param string $baseLocaleCode
     */
    public function __construct(FactoryInterface $localeFactory, ObjectManager $localeManager, $baseLocaleCode)
    {
        $this->localeFactory = $localeFactory;
        $this->localeManager = $localeManager;
        $this->baseLocaleCode = $baseLocaleCode;
    }

    public function load(array $options): void
    {
        $localesCodes = $options['locales'];

        if ($options['load_default_locale']) {
            array_unshift($localesCodes, $this->baseLocaleCode);
        }

        $localesCodes = array_unique($localesCodes);

        foreach ($localesCodes as $localeCode) {
            /** @var LocaleInterface $locale */
            $locale = $this->localeFactory->createNew();

            $locale->setCode($localeCode);

            $this->localeManager->persist($locale);
        }

        $this->localeManager->flush();
    }

    public function getName(): string
    {
        return 'locale';
    }

    protected function configureOptionsNode(ArrayNodeDefinition $optionsNode): void
    {
        $optionsNode
            ->children()
                ->scalarNode('load_default_locale')->defaultTrue()->end()
                ->arrayNode('locales')->scalarPrototype()->end()
        ;
    }
}
