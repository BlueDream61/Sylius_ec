<?php

namespace spec\Sylius\Bundle\ShippingBundle;

use PHPSpec2\ObjectBehavior;

/**
 * Sylius shipping bundle spec.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class SyliusShippingBundle extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ShippingBundle\SyliusShippingBundle');
    }

    function it_should_be_a_bundle()
    {
        $this->shouldHaveType('Symfony\Component\HttpKernel\Bundle\Bundle');
    }
}
