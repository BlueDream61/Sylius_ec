<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\TaxationBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class TaxRateType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('name', TextType::class, [
                'label' => 'sylius.form.tax_rate.name',
            ])
            ->add('category', 'sylius_tax_category_choice', [
                'label' => 'sylius.form.tax_rate.category',
            ])
            ->add('calculator', 'sylius_tax_calculator_choice', [
                'label' => 'sylius.form.tax_rate.calculator',
            ])
            ->add('amount', PercentType::class, [
                'label' => 'sylius.form.tax_rate.amount',
                'precision' => 3,
            ])
            ->add('includedInPrice', CheckboxType::class, [
                'label' => 'sylius.form.tax_rate.included_in_price',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_tax_rate';
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sylius_tax_rate';
    }
}
