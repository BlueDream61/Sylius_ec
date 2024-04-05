<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\AdminBundle\Form\Extension;

use Sylius\Bundle\AdminBundle\Form\Type\TaxonAutocompleteChoiceType;
use Sylius\Bundle\CoreBundle\Form\Type\ChannelPriceHistoryConfigType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;

final class ChannelPriceHistoryConfigTypeExtension extends AbstractTypeExtension
{
    public function __construct(private DataMapperInterface $dataMapper)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('taxonsExcludedFromShowingLowestPrice', TaxonAutocompleteChoiceType::class, [
                'label' => 'sylius.ui.taxons_for_which_the_lowest_price_is_not_displayed',
                'required' => false,
                'multiple' => true,
                'expanded' => false,
            ])
        ;

        $builder->setDataMapper($this->dataMapper);
    }

    public static function getExtendedTypes(): iterable
    {
        yield ChannelPriceHistoryConfigType::class;
    }
}
