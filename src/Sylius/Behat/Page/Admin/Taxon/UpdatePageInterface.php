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

namespace Sylius\Behat\Page\Admin\Taxon;

use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Page\Admin\Crud\UpdatePageInterface as BaseUpdatePageInterface;
use Sylius\Component\Core\Model\TaxonInterface;

interface UpdatePageInterface extends BaseUpdatePageInterface
{
    public function describeItAs(string $description, string $languageCode): void;

    public function chooseParent(TaxonInterface $taxon): void;

    public function isCodeDisabled(): bool;

    public function isSlugReadonly(string $languageCode = 'en_US'): bool;

    public function getParent(): string;

    public function getSlug(string $languageCode = 'en_US'): string;

    /**
     * @throws ElementNotFoundException
     */
    public function getValidationMessageForImage(): string;

    /**
     * @throws ElementNotFoundException
     */
    public function getValidationMessageForImageAtPlace(int $place): string;

    public function activateLanguageTab(string $locale): void;

    public function enable(): void;

    public function disable(): void;

    public function isEnabled(): bool;
}
