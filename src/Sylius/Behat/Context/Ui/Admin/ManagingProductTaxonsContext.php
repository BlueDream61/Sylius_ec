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

namespace Sylius\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\Element\Admin\Product\TaxonomyFormElementInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Webmozart\Assert\Assert;

final class ManagingProductTaxonsContext implements Context
{
    public function __construct(private TaxonomyFormElementInterface $taxonomyFormElement)
    {
    }

    /**
     * @When I add :taxon taxon to the :product product
     * @When I assign the :taxon taxon to the :product product
     */
    public function iAddTaxonToTheProduct(ProductInterface $product, TaxonInterface $taxon): void
    {
        $this->taxonomyFormElement->checkProductTaxon($taxon);
    }

    /**
     * @When I change that the :product product does not belong to the :taxon taxon
     */
    public function iChangeThatTheProductDoesNotBelongToTheTaxon(
        ProductInterface $product,
        TaxonInterface $taxon,
    ): void {
        $this->taxonomyFormElement->uncheckProductTaxon($taxon);
    }

    /**
     * @Then the product :product should have the :taxon taxon
     */
    public function thisProductTaxonShouldHaveTheTaxon(TaxonInterface $taxon): void
    {
        Assert::true($this->taxonomyFormElement->isTaxonChosen($taxon->getCode()));
    }

    /**
     * @Then the product :product should not have the :taxon taxon
     */
    public function thisProductTaxonShouldNotHaveTheTaxon(TaxonInterface $taxon): void
    {
        Assert::false($this->taxonomyFormElement->isTaxonChosen($taxon->getCode()));
    }
}
