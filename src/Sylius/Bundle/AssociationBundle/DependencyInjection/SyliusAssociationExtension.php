<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\AssociationBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Łukasz Chruściel <lukasz.chrusciel@lakion.com>
 */
final class SyliusAssociationExtension extends AbstractResourceExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $config = $this->processConfiguration($this->getConfiguration($config, $container), $config);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.xml');

        $this->registerResources('sylius', $config['driver'], $this->resolveResources($config['resources'], $container), $container);

        foreach ($config['resources'] as $subjectName => $subjectConfig) {
            $this->resolveFormsConfiguration($subjectConfig, $subjectName, $container);
        }
    }

    /**
     * @param array $resources
     * @param ContainerBuilder $container
     *
     * @return array
     */
    private function resolveResources(array $resources, ContainerBuilder $container)
    {
        $subjects = [];
        $resolvedResources = [];

        foreach ($resources as $subject => $parameters) {
            $subjects[$subject] = $parameters;
        }

        $container->setParameter('sylius.association.subjects', $subjects);

        foreach ($resources as $subjectName => $subjectConfig) {
            $this->resolveResourceConfiguration($subjectConfig, $subjectName);
        }

        return $resolvedResources;
    }

    /**
     * @param array $subjectConfig
     * @param string $subjectName
     */
    private function resolveResourceConfiguration(array $subjectConfig, $subjectName)
    {
        foreach ($subjectConfig as $resourceName => $resourceConfig) {
            if (!is_array($resourceConfig)) {
                continue;
            }

            $resolvedResources[$subjectName.'_'.$resourceName] = $resourceConfig;
            $resolvedResources[$subjectName.'_'.$resourceName]['subject'] = $subjectName;
        }
    }

    /**
     * @param array $subjectConfig
     * @param string $subjectName
     * @param ContainerBuilder $container
     */
    private function resolveFormsConfiguration(array $subjectConfig, $subjectName, ContainerBuilder $container)
    {
        foreach ($subjectConfig as $resourceName => $resourceConfig) {
            if (!is_array($resourceConfig)) {
                continue;
            }

            $formDefinition = $container->getDefinition('sylius.form.type.'.$subjectName.'_'.$resourceName);
            $formDefinition->addArgument($subjectName);
        }
    }
}
