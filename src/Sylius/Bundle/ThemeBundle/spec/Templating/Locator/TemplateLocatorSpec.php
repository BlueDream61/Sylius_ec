<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\ThemeBundle\Templating\Locator;

use Sylius\Bundle\ThemeBundle\Context\ThemeContextInterface;
use Sylius\Bundle\ThemeBundle\HierarchyProvider\ThemeHierarchyProviderInterface;
use Sylius\Bundle\ThemeBundle\Locator\ResourceLocatorInterface;
use Sylius\Bundle\ThemeBundle\PhpSpec\FixtureAwareObjectBehavior;
use Sylius\Bundle\ThemeBundle\Repository\ThemeRepositoryInterface;
use Sylius\Bundle\ThemeBundle\Templating\Locator\TemplateLocator;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @mixin TemplateLocator
 *
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
class TemplateLocatorSpec extends FixtureAwareObjectBehavior
{
    function let(
        ThemeContextInterface $themeContext,
        ThemeHierarchyProviderInterface $themeHierarchyProvider,
        ResourceLocatorInterface $bundleResourceLocator,
        ResourceLocatorInterface $applicationResourceLocator
    ) {
        $this->beConstructedWith($themeContext, $themeHierarchyProvider, $bundleResourceLocator, $applicationResourceLocator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ThemeBundle\Templating\Locator\TemplateLocator');
    }

    function it_implements_file_locator_interface()
    {
        $this->shouldImplement(FileLocatorInterface::class);
    }

    /**
     * BundleResources & AppResources: every path, considering themes order
     */
}
