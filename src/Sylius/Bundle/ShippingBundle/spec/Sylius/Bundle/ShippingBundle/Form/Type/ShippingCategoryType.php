<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\ShippingBundle\Form\Type;

use PHPSpec2\ObjectBehavior;

/**
 * Shipping category form type.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class ShippingCategoryType extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('ShippingCategory', array('sylius'));
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ShippingBundle\Form\Type\ShippingCategoryType');
    }

    function it_should_be_a_form_type()
    {
        $this->shouldImplement('Symfony\Component\Form\FormTypeInterface');
    }

    /**
     * @param Symfony\Component\Form\FormBuilder $builder
     */
    function it_should_build_form_with_name_and_description_fields($builder)
    {
        $builder
            ->add('name', 'text', ANY_ARGUMENT)
            ->shouldBeCalled()
            ->willReturn($builder)
        ;

        $builder
            ->add('description', 'textarea', ANY_ARGUMENT)
            ->shouldBeCalled()
            ->willReturn($builder)
        ;

        $this->buildForm($builder, array());
    }

    /**
     * @param Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    function it_should_define_assigned_data_class($resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class'        => 'ShippingCategory',
                'validation_groups' => array('sylius'),
            ))
            ->shouldBeCalled()
        ;

        $this->setDefaultOptions($resolver);
    }
}

