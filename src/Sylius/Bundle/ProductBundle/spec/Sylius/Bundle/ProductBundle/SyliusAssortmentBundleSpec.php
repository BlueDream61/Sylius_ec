<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\ProductBundle;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Sylius product bundle spec.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class SyliusProductBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ProductBundle\SyliusProductBundle');
    }

    function it_is_a_bundle()
    {
        $this->shouldHaveType('Symfony\Component\HttpKernel\Bundle\Bundle');
    }

    function it_supports_correct_drivers()
    {
        $this->getSupportedDrivers()->shouldReturn(array('doctrine/orm'));
    }
}
