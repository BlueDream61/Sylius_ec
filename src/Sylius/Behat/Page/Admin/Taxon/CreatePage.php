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

use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;
use Sylius\Behat\Service\DriverHelper;
use Sylius\Behat\Service\JQueryHelper;
use Sylius\Component\Core\Model\TaxonInterface;

class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    public function getTaxonsNames(): array
    {
        $treeTaxons = $this->getElement('tree_taxons');
        $taxons = [];

        foreach ($treeTaxons->findAll('css', '[data-test-tree-taxon]') as $taxon) {
            $taxons[] = $taxon->getText();
        }

        return $taxons;
    }

    public function countTaxons(): int
    {
        return count($this->getElement('tree_taxons')->findAll('css', '[data-test-tree-taxon]'));
    }

    public function isTaxonOnTheList(string $taxonName): bool
    {
        $taxons = $this->getElement('tree_taxons')->findAll('css', '[data-test-tree-taxon]');

        foreach ($taxons as $taxon) {
            if ($taxonName === $taxon->getText()) {
                return true;
            }
        }

        return false;
    }

    public function getFirstTaxonOnTheList(): string
    {
        return $this->getElement('first_tree_taxon')->getText();
    }

    public function getLastTaxonOnTheList(): string
    {
        return $this->getElement('last_tree_taxon')->getText();
    }

    public function moveUpTaxon(string $name): void
    {
        $this->getElement('tree_taxon_actions', ['%name%' => $name])->click();
        $this->getElement('tree_taxon_move_up', ['%name%' => $name])->click();
        $this->waitForUpdate();
    }

    public function moveDownTaxon(string $name): void
    {
        $this->getElement('tree_taxon_actions', ['%name%' => $name])->click();
        $this->getElement('tree_taxon_move_down', ['%name%' => $name])->click();
        $this->waitForUpdate();
    }

    public function deleteTaxonOnPageByName(string $name): void
    {
        $leaves = $this->getLeaves();
        /** @var NodeElement $leaf */
        foreach ($leaves as $leaf) {
            if ($leaf->find('css', '.sylius-tree__title')->getText() === $name) {
                $leaf->find('css', '.sylius-tree__action__trigger')->click();
                JQueryHelper::waitForAsynchronousActionsToFinish($this->getSession());
                $leaf->find('css', '.sylius-tree__action button')->press();
                $this->getElement('confirmation_button')->click();

                return;
            }
        }

        throw new ElementNotFoundException($this->getDriver(), 'Delete button');
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'tree_taxons' => '[data-test-tree-taxons]',
            'tree_taxon_actions' => '[data-test-tree-taxons] [data-test-tree-taxon="%name%"] [data-test-actions]',
            'tree_taxon_move_down' => '[data-test-tree-taxons] [data-test-tree-taxon="%name%"] [data-test-move-down]',
            'tree_taxon_move_up' => '[data-test-tree-taxons] [data-test-tree-taxon="%name%"] [data-test-move-up]',
            'first_tree_taxon' => '[data-test-tree-taxons] [data-test-tree-taxon]:first-child',
            'last_tree_taxon' => '[data-test-tree-taxons] [data-test-tree-taxon]:last-child',
            'tree_taxon_component' => '[data-live-name-value="sylius_admin:taxon:tree"]',
        ]);
    }

    protected function waitForUpdate(): void
    {
        $treeTaxonComponent = $this->getElement('tree_taxon_component');
        sleep(1); // we need to sleep, as sometimes the check below is executed faster than the treeTaxonComponent sets the busy attribute
        $treeTaxonComponent->waitFor(1500, function () use ($treeTaxonComponent) {
            return !$treeTaxonComponent->hasAttribute('busy');
        });
    }
}
