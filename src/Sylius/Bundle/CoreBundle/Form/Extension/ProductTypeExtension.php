<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Form\Extension;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\CoreBundle\Form\Type\Product\ProductImageType;
use Sylius\Bundle\CoreBundle\Form\Type\ProductTaxonChoiceType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductType;
use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonChoiceType;
use Sylius\Component\Core\Model\Product;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 * @author Gonzalo Vilaseca <gvilaseca@reiss.co.uk>
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
class ProductTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('channels', ChannelChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'label' => 'sylius.form.product.channels',
            ])
            ->add('mainTaxon', TaxonChoiceType::class)
            ->add('productTaxons', ProductTaxonChoiceType::class, [
                'label' => 'sylius.form.product.taxons',
                'multiple' => true,
            ])
            ->add('variantSelectionMethod', ChoiceType::class, [
                'choices' => array_flip(Product::getVariantSelectionMethodLabels()),
                'label' => 'sylius.form.product.variant_selection_method',
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ProductImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'sylius.form.product.images',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ProductType::class;
    }
}
