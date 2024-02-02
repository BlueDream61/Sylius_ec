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

namespace Sylius\Bundle\PromotionBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class CatalogPromotionActionGroup extends Constraint
{
    public function validatedBy(): string
    {
        return 'sylius_catalog_promotion_action_group';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
