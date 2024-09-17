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

namespace Sylius\Bundle\ApiBundle\Validator\CatalogPromotion;

use Sylius\Bundle\ApiBundle\SectionResolver\AdminApiSection;
use Sylius\Bundle\CoreBundle\SectionResolver\SectionProviderInterface;
use Sylius\Bundle\PromotionBundle\Validator\CatalogPromotionScope\ScopeValidatorInterface;
use Sylius\Bundle\PromotionBundle\Validator\Constraints\CatalogPromotionScope;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Webmozart\Assert\Assert;

trigger_deprecation(
    'sylius/api-bundle',
    '1.14',
    'The "%s" class is deprecated and will be removed in Sylius 2.0, use the usual symfony logic for validation.',
    ForTaxonsScopeValidator::class,
);
final class ForTaxonsScopeValidator implements ScopeValidatorInterface
{
    public function __construct(
        private ScopeValidatorInterface $baseScopeValidator,
        private SectionProviderInterface $sectionProvider,
    ) {
    }

    public function validate(array $configuration, Constraint $constraint, ExecutionContextInterface $context): void
    {
        /** @var CatalogPromotionScope $constraint */
        Assert::isInstanceOf($constraint, CatalogPromotionScope::class);

        if (
            $this->sectionProvider->getSection() instanceof AdminApiSection &&
            (!isset($configuration['taxons']) || empty($configuration['taxons']))
        ) {
            $context
                ->buildViolation('sylius.catalog_promotion_scope.for_taxons.not_empty')
                ->atPath('configuration.taxons')
                ->addViolation()
            ;

            return;
        }

        $this->baseScopeValidator->validate($configuration, $constraint, $context);
    }
}
