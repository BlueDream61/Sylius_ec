<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\CoreBundle\Promotion\Checker;

use PhpSpec\ObjectBehavior;

/**
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class ShippingCountryRuleCheckerSpec extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\CoreBundle\Promotion\Checker\ShippingCountryRuleChecker');
    }

    function it_should_be_Sylius_rule_checker()
    {
        $this->shouldImplement('Sylius\Bundle\PromotionsBundle\Checker\RuleCheckerInterface');
    }

    /**
     * @param Sylius\Bundle\CoreBundle\Model\OrderInterface $subject
     */
    function it_should_recognize_no_shipping_address_as_not_eligible($subject)
    {
        $subject->getShippingAddress()->shouldBeCalled()->willReturn(null);

        $this->isEligible($subject, array())->shouldReturn(false);
    }

    /**
     * @param Sylius\Bundle\CoreBundle\Model\OrderInterface         $subject
     * @param Sylius\Bundle\AddressingBundle\Model\AddressInterface $address
     * @param Sylius\Bundle\AddressingBundle\Model\CountryInterface $country1
     * @param Sylius\Bundle\AddressingBundle\Model\CountryInterface $country2
     */
    function it_should_recognize_subject_as_not_eligible_if_country_does_not_match($subject, $address, $country1, $country2)
    {
        $subject->getShippingAddress()->shouldBeCalled()->willReturn($address);
        $address->getCountry()->shouldBeCalled()->willReturn($country1);
        $country1->getId()->shouldBeCalled()->willReturn(1);
        $country2->getId()->shouldBeCalled()->willReturn(2);

        $this->isEligible($subject, array('country' => $country2))->shouldReturn(false);
    }

    /**
     * @param Sylius\Bundle\CoreBundle\Model\OrderInterface         $subject
     * @param Sylius\Bundle\AddressingBundle\Model\AddressInterface $address
     * @param Sylius\Bundle\AddressingBundle\Model\CountryInterface $country1
     * @param Sylius\Bundle\AddressingBundle\Model\CountryInterface $country2
     */
    function it_should_recognize_subject_as_eligible_if_country_match($subject, $address, $country1, $country2)
    {
        $subject->getShippingAddress()->shouldBeCalled()->willReturn($address);
        $address->getCountry()->shouldBeCalled()->willReturn($country1);
        $country1->getId()->shouldBeCalled()->willReturn(1);
        $country2->getId()->shouldBeCalled()->willReturn(1);

        $this->isEligible($subject, array('country' => $country2))->shouldReturn(true);
    }
}
