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

namespace Sylius\Bundle\PromotionBundle\Validator;

use Sylius\Bundle\PromotionBundle\Validator\Constraints\PromotionRule;
use Sylius\Component\Promotion\Model\PromotionRuleInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class PromotionRuleValidator extends ConstraintValidator
{
    /** @param array<string, string> $ruleTypes */
    public function __construct(private array $ruleTypes)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PromotionRule) {
            throw new UnexpectedTypeException($constraint, PromotionRule::class);
        }

        if (!$value instanceof PromotionRuleInterface) {
            throw new UnexpectedValueException($value, PromotionRuleInterface::class);
        }

        $type = $value->getType();
        if (!array_key_exists($type, $this->ruleTypes)) {
            $this->context->buildViolation($constraint->invalidType)->atPath('type')->addViolation();
        }
    }
}
