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

namespace Sylius\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Attribute\AttributeType\SelectAttributeType;
use Sylius\Component\Attribute\Factory\AttributeFactoryInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductAttributeContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var RepositoryInterface
     */
    private $productAttributeRepository;

    /**
     * @var AttributeFactoryInterface
     */
    private $productAttributeFactory;

    /**
     * @var FactoryInterface
     */
    private $productAttributeValueFactory;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param RepositoryInterface $productAttributeRepository
     * @param AttributeFactoryInterface $productAttributeFactory
     * @param FactoryInterface $productAttributeValueFactory
     * @param ObjectManager $objectManager
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        RepositoryInterface $productAttributeRepository,
        AttributeFactoryInterface $productAttributeFactory,
        FactoryInterface $productAttributeValueFactory,
        ObjectManager $objectManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->productAttributeFactory = $productAttributeFactory;
        $this->productAttributeValueFactory = $productAttributeValueFactory;
        $this->objectManager = $objectManager;

        $this->faker = \Faker\Factory::create();
    }

    /**
     * @Given the store has a :type product attribute :name with code :code
     */
    public function theStoreHasAProductAttributeWithCode($type, $name, $code)
    {
        $productAttribute = $this->createProductAttribute($type, $name, $code);

        $this->saveProductAttribute($productAttribute);
    }

    /**
     * @Given the store has( also) a :type product attribute :name at position :position
     */
    public function theStoreHasAProductAttributeWithPosition($type, $name, $position)
    {
        $productAttribute = $this->createProductAttribute($type, $name);
        $productAttribute->setPosition((int) $position);

        $this->saveProductAttribute($productAttribute);
    }

    /**
     * @Given the store has( also) a/an :type product attribute :name
     */
    public function theStoreHasAProductAttribute(string $type, string $name): void
    {
        $productAttribute = $this->createProductAttribute($type, $name);

        $this->saveProductAttribute($productAttribute);
    }

    /**
     * @Given the store has a select product attribute :name with value :value
     * @Given the store has a select product attribute :name with values :firstValue and :secondValue
     */
    public function theStoreHasASelectProductAttributeWithValue(string $name, string ...$values): void
    {
        $choices = [];
        foreach ($values as $value) {
            $choices[$this->faker->uuid] = $value;
        }

        $productAttribute = $this->createProductAttribute(SelectAttributeType::TYPE, $name);
        $productAttribute->setConfiguration([
            'multiple' => true,
            'choices' => $choices,
            'min' => null,
            'max' => null,
        ]);

        $this->saveProductAttribute($productAttribute);
    }

    /**
     * @Given /^(this product attribute) has set min value as (\d+) and max value as (\d+)$/
     */
    public function thisAttributeHasSetMinValueAsAndMaxValueAs(ProductAttributeInterface $attribute, $min, $max)
    {
        $attribute->setConfiguration(['min' => $min, 'max' => $max]);

        $this->objectManager->flush();
    }

    /**
     * @Given /^(this product) has select attribute "([^"]+)" with value "([^"]+)"$/
     * @Given /^(this product) has select attribute "([^"]+)" with values "([^"]+)" and "([^"]+)"$/
     */
    public function thisProductHasSelectAttributeWithValues(
        ProductInterface $product,
        string $productAttributeName,
        string ...$productAttributeValues
    ): void {
        $values = [];
        foreach ($productAttributeValues as $value) {
            $values[$this->faker->uuid] = $value;
        }

        $this->createSelectProductAttributeValue($product, $productAttributeName, $values);
    }

    /**
     * @param ProductInterface $product
     * @param string $productAttributeName
     * @param array $values
     */
    private function createSelectProductAttributeValue(
        ProductInterface $product,
        string $productAttributeName,
        array $values
    ): void {
        $attribute = $this->provideProductAttribute(SelectAttributeType::TYPE, $productAttributeName);

        $choices = $attribute->getConfiguration()['choices'];
        $choiceKeys = [];
        foreach ($values as $value) {
            $choiceKeys[] = array_search($value, $choices);
        }

        $attributeValue = $this->createProductAttributeValue($choiceKeys, $attribute);
        $product->addAttribute($attributeValue);

        $this->objectManager->flush();
    }

    /**
     * @Given /^(this product) has (text|textarea) attribute "([^"]+)" with value "([^"]+)"$/
     * @Given /^(this product) has (text|textarea) attribute "([^"]+)" with value "([^"]+)" in ("[^"]+" locale)$/
     */
    public function thisProductHasAttributeWithValue(
        ProductInterface $product,
        string $productAttributeType,
        string $productAttributeName,
        string $value,
        string $language = 'en_US'
    ): void {
        $attribute = $this->provideProductAttribute($productAttributeType, $productAttributeName);
        $attributeValue = $this->createProductAttributeValue($value, $attribute, $language);
        $product->addAttribute($attributeValue);

        $this->objectManager->flush();
    }

    /**
     * @Given /^(this product) has percent attribute "([^"]+)" with value ([^"]+)%$/
     */
    public function thisProductHasPercentAttributeWithValue(ProductInterface $product, $productAttributeName, $value)
    {
        $attribute = $this->provideProductAttribute('percent', $productAttributeName);
        $attributeValue = $this->createProductAttributeValue($value / 100, $attribute);
        $product->addAttribute($attributeValue);

        $this->objectManager->flush();
    }

    /**
     * @Given /^(this product) has ([^"]+) attribute "([^"]+)" set to "([^"]+)"$/
     */
    public function thisProductHasCheckboxAttributeWithValue(
        ProductInterface $product,
        $productAttributeType,
        $productAttributeName,
        $value
    ) {
        $attribute = $this->provideProductAttribute($productAttributeType, $productAttributeName);
        $booleanValue = ('Yes' === $value);
        $attributeValue = $this->createProductAttributeValue($booleanValue, $attribute);
        $product->addAttribute($attributeValue);

        $this->objectManager->flush();
    }

    /**
     * @Given /^(this product) has percent attribute "([^"]+)" at position (\d+)$/
     */
    public function thisProductHasPercentAttributeWithValueAtPosition(
        ProductInterface $product,
        $productAttributeName,
        $position
    ) {
        $attribute = $this->provideProductAttribute('percent', $productAttributeName);
        $attribute->setPosition((int) $position);
        $attributeValue = $this->createProductAttributeValue(rand(1, 100) / 100, $attribute);

        $product->addAttribute($attributeValue);

        $this->objectManager->flush();
    }

    /**
     * @Given /^(this product) has ([^"]+) attribute "([^"]+)" with date "([^"]+)"$/
     */
    public function thisProductHasDateTimeAttributeWithDate(
        ProductInterface $product,
        $productAttributeType,
        $productAttributeName,
        $date
    ) {
        $attribute = $this->provideProductAttribute($productAttributeType, $productAttributeName);
        $attributeValue = $this->createProductAttributeValue(new \DateTime($date), $attribute);

        $product->addAttribute($attributeValue);

        $this->objectManager->flush();
    }

    /**
     * @param string $type
     * @param string $name
     * @param string|null $code
     *
     * @return ProductAttributeInterface
     */
    private function createProductAttribute($type, $name, $code = null)
    {
        $productAttribute = $this->productAttributeFactory->createTyped($type);

        $code = $code ?: StringInflector::nameToCode($name);

        $productAttribute->setCode($code);
        $productAttribute->setName($name);

        return $productAttribute;
    }

    /**
     * @param string $type
     * @param string $name
     * @param string|null $code
     *
     * @return ProductAttributeInterface
     */
    private function provideProductAttribute($type, $name, $code = null)
    {
        $code = $code ?: StringInflector::nameToCode($name);

        /** @var ProductAttributeInterface $productAttribute */
        $productAttribute = $this->productAttributeRepository->findOneBy(['code' => $code]);
        if (null !== $productAttribute) {
            return $productAttribute;
        }

        $productAttribute = $this->createProductAttribute($type, $name, $code);
        $this->saveProductAttribute($productAttribute);

        return $productAttribute;
    }

    /**
     * @param mixed $value
     * @param ProductAttributeInterface $attribute
     * @param string $localeCode
     *
     * @return ProductAttributeValueInterface
     */
    private function createProductAttributeValue(
        $value,
        ProductAttributeInterface $attribute,
        string $localeCode = 'en_US'
    ): ProductAttributeValueInterface {
        /** @var ProductAttributeValueInterface $attributeValue */
        $attributeValue = $this->productAttributeValueFactory->createNew();
        $attributeValue->setAttribute($attribute);
        $attributeValue->setValue($value);
        $attributeValue->setLocaleCode($localeCode);

        $this->objectManager->persist($attributeValue);

        return $attributeValue;
    }

    /**
     * @param ProductAttributeInterface $productAttribute
     */
    private function saveProductAttribute(ProductAttributeInterface $productAttribute)
    {
        $this->productAttributeRepository->add($productAttribute);
        $this->sharedStorage->set('product_attribute', $productAttribute);
    }
}
