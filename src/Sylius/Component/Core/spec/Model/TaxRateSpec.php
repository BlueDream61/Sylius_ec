<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Component\Core\Model;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\TaxRateInterface;
use Sylius\Component\Taxation\Model\TaxRate;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class TaxRateSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Component\Core\Model\TaxRate');
    }

    function it_should_implement_Sylius_core_tax_rate_interface()
    {
        $this->shouldImplement(TaxRateInterface::class);
    }

    function it_should_extend_Sylius_tax_rate_mapped_superclass()
    {
        $this->shouldHaveType(TaxRate::class);
    }

    function it_should_not_have_any_zone_defined_by_default()
    {
        $this->getZone()->shouldReturn(null);
    }

    function it_should_allow_defining_zone(ZoneInterface $zone)
    {
        $this->setZone($zone);
        $this->getZone()->shouldReturn($zone);
    }
}
