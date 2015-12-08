<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\AttributeBundle\Form\Type\AttributeType;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
class DateAttributeTypeOptionsTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\AttributeBundle\Form\Type\AttributeType\DateAttributeTypeOptionsType');
    }

    function it_is_abstract_type()
    {
        $this->shouldHaveType('Symfony\Component\Form\AbstractType');
    }

    function it_builds_options_form(FormBuilderInterface $builder)
    {
        $builder
            ->add('format', 'text', array('label' => 'sylius.attribute_type_options.date.format'))
            ->willReturn($builder)
        ;

        $this->buildForm($builder, array());
    }

    function it_has_name()
    {
        $this->getName()->shouldReturn('sylius_attribute_type_options_date');
    }
}
