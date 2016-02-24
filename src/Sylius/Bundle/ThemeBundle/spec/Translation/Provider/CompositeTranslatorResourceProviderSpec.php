<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\ThemeBundle\Translation\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ThemeBundle\Translation\Provider\CompositeTranslatorResourceProvider;
use Sylius\Bundle\ThemeBundle\Translation\Provider\TranslationResourceInterface;
use Sylius\Bundle\ThemeBundle\Translation\Provider\TranslatorResourceProviderInterface;

/**
 * @mixin CompositeTranslatorResourceProvider
 *
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
class CompositeTranslatorResourceProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ThemeBundle\Translation\Provider\CompositeTranslatorResourceProvider');
    }

    function it_implements_translator_resource_provider_interface()
    {
        $this->shouldImplement(TranslatorResourceProviderInterface::class);
    }

    function it_aggregates_the_resources(
        TranslatorResourceProviderInterface $firstResourceProvider,
        TranslatorResourceProviderInterface $secondResourceProvider,
        TranslationResourceInterface $firstResource,
        TranslationResourceInterface $secondResource
    ) {
        $this->beConstructedWith([$firstResourceProvider, $secondResourceProvider]);

        $firstResourceProvider->getResources()->willReturn([$firstResource]);
        $secondResourceProvider->getResources()->willReturn([$secondResource]);

        $this->getResources()->shouldReturn([$firstResource, $secondResource]);
    }

    function it_aggregates_the_unique_resources(
        TranslatorResourceProviderInterface $firstResourceProvider,
        TranslatorResourceProviderInterface $secondResourceProvider,
        TranslationResourceInterface $firstResource,
        TranslationResourceInterface $secondResource
    ) {
        $this->beConstructedWith([$firstResourceProvider, $secondResourceProvider]);

        $firstResourceProvider->getResources()->willReturn([$firstResource]);
        $secondResourceProvider->getResources()->willReturn([$secondResource, $firstResource]);

        $this->getResources()->shouldReturn([$firstResource, $secondResource]);
    }

    function it_aggregates_the_resources_locales(
        TranslatorResourceProviderInterface $firstResourceProvider,
        TranslatorResourceProviderInterface $secondResourceProvider
    ) {
        $this->beConstructedWith([$firstResourceProvider, $secondResourceProvider]);

        $firstResourceProvider->getResourcesLocales()->willReturn(['first-locale']);
        $secondResourceProvider->getResourcesLocales()->willReturn(['second-locale']);

        $this->getResourcesLocales()->shouldReturn(['first-locale', 'second-locale']);
    }

    function it_aggregates_the_unique_resources_locales(
        TranslatorResourceProviderInterface $firstResourceProvider,
        TranslatorResourceProviderInterface $secondResourceProvider
    ) {
        $this->beConstructedWith([$firstResourceProvider, $secondResourceProvider]);

        $firstResourceProvider->getResourcesLocales()->willReturn(['first-locale']);
        $secondResourceProvider->getResourcesLocales()->willReturn(['second-locale', 'first-locale', 'second-locale']);

        $this->getResourcesLocales()->shouldReturn(['first-locale', 'second-locale']);
    }
}
