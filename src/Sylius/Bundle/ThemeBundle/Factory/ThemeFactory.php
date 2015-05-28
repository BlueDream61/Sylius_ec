<?php

namespace Sylius\Bundle\ThemeBundle\Factory;

use Sylius\Bundle\ThemeBundle\Exception\InvalidArgumentException;
use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\ValidatorInterface;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
class ThemeFactory implements ThemeFactoryInterface
{
    /**
     * @var string
     */
    private $themeClassName;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @param string $themeClassName
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function __construct($themeClassName, PropertyAccessorInterface $propertyAccessor)
    {
        $this->themeClassName = $themeClassName;
        $this->propertyAccessor = $propertyAccessor;

        $this->optionsResolver = new OptionsResolver();
        $this->optionsResolver
            ->setRequired([
                'name',
                'logical_name',
            ])
            ->setDefined([
                'description'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromArray(array $themeData)
    {
        /** @var ThemeInterface $theme */
        $theme = new $this->themeClassName();

        $themeData = $this->optionsResolver->resolve($themeData);

        foreach ($themeData as $attributeKey => $attributeValue)
        {
            try {
                $this->propertyAccessor->setValue($theme, $attributeKey, $attributeValue);
            } catch (NoSuchPropertyException $exception) {
                // Ignore properties that does not exist in given theme model.
            }
        }

        return $theme;
    }

}