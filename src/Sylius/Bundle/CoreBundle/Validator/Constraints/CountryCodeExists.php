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

namespace Sylius\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class CountryCodeExists extends Constraint
{
    public string $message = 'sylius.country.code.no_exist';

    public function validatedBy(): string
    {
        return 'sylius_country_code_exists';
    }

    public function getTargets(): string
    {
        return Constraint::PROPERTY_CONSTRAINT;
    }
}
