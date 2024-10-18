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

namespace Sylius\Bundle\ShopBundle\Form\Type;

use Sylius\Bundle\CustomerBundle\Form\Type\CustomerProfileType as BaseCustomerProfileType;
use Symfony\Component\Form\AbstractType;

final class CustomerProfileType extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return 'sylius_shop_customer_profile';
    }

    public function getParent(): string
    {
        return BaseCustomerProfileType::class;
    }
}
