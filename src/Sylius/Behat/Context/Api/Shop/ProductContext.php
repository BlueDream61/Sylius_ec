<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Behat\Context\Api\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\Client\ApiClientInterface;
use Sylius\Behat\Client\Request;
use Sylius\Behat\Client\ResponseCheckerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class ProductContext implements Context
{
    /** @var ApiClientInterface */
    private $client;

    /** @var ResponseCheckerInterface */
    private $responseChecker;

    public function __construct(ApiClientInterface $client, ResponseCheckerInterface $responseChecker)
    {
        $this->client = $client;
        $this->responseChecker = $responseChecker;
    }

    /**
     * @When /^I check (this product)'s details$/
     * @When I view product :product
     */
    public function iOpenProductPage(ProductInterface $product): void
    {
        $this->client->show($product->getCode());
    }

    /**
     * @When /^I browse products from (taxon "([^"]+)")$/
     */
    public function iCheckListOfProductsForTaxon(TaxonInterface $taxon): void
    {
        $this->client->index();
        $this->client->addFilter('productTaxons.taxon.code', $taxon->getCode());
        $this->client->filter();
    }

    /**
     * @Then I should see the product price :productPrice
     */
    public function iShouldSeeTheProductPrice(string $productPrice): void
    {
        $price = (int) filter_var($productPrice, FILTER_SANITIZE_NUMBER_INT);

        Assert::true($this->hasProductWithPrice([$this->responseChecker->getResponseContent($this->client->getLastResponse())], $price));
    }

    /**
     * @Then I should see the product :product with price :productPrice
     */
    public function iShouldSeeTheProductWithPrice(ProductInterface $product, string $productPrice): void
    {
        $price = (int) filter_var($productPrice, FILTER_SANITIZE_NUMBER_INT);

        Assert::true($this->hasProductWithPrice($this->responseChecker->getCollection($this->client->getLastResponse()), $price, $product->getCode()));
    }

    /**
     * @Then I should see the product name :name
     */
    public function iShouldSeeProductName(string $name): void
    {
        Assert::true(
            $this->responseChecker->hasItemWithTranslation(
                $this->client->getLastResponse(),
                'en_US',
                'name',
                $name
            )
        );

        Assert::same($this->responseChecker->getTranslationValue($this->client->getLastResponse(), 'name'), $name);
    }

    /**
     * @Then its current variant should be named :variantName
     */
    public function itsCurrentVariantShouldBeNamed(string $variantName): void
    {
        $response = $this->client->getLastResponse();

        $productVariant = $this->responseChecker->getValue($response, 'variants');
        $this->client->executeCustomRequest(Request::custom($productVariant[0], HttpRequest::METHOD_GET));

        Assert::true(
            $this->responseChecker->hasTranslation(
                $this->client->getLastResponse(),
                'en_US',
                'name',
                $variantName
            )
        );
    }

    private function hasProductWithPrice(array $resource, int $price, ?string $productCode = null): bool
    {
        foreach ($resource as $product) {
            if ($productCode && $product['code'] !== $productCode) {
                continue;
            }

            foreach ($product['variants'] as $variant) {
                foreach ($variant['channelPricings'] as $channelPricing) {
                    if ($channelPricing['price'] === $price) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
