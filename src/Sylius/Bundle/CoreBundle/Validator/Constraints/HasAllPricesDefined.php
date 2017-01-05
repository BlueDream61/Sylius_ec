<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
final class HasAllPricesDefined extends Constraint
{
    /**
     * @var string
     */
    public $message = 'sylius.product_variant.channel_pricing.all_defined';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'sylius_has_all_prices_defined';
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}
